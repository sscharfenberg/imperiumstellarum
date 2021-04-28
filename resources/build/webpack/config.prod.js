/******************************************************************************
 *
 * Webpack production config
 * @type {NodeJS}
 *
 *****************************************************************************/
// node internals
const path = require("path");
const fs = require("fs");
// node packages
const { merge } = require("webpack-merge");
const webpack = require("webpack");
const MiniCssExtractPlugin = require("mini-css-extract-plugin");
const CompressionPlugin = require("compression-webpack-plugin");
const dateformat = require("dateformat");
const BundleAnalyzerPlugin = require("webpack-bundle-analyzer")
    .BundleAnalyzerPlugin;
const ImageMinimizerPlugin = require("image-minimizer-webpack-plugin");
const CopyPlugin = require("copy-webpack-plugin");
const { extendDefaultPlugins } = require("svgo");
// imports
const pkg = require("../../../package.json");
const common = require("./config.common");
// variables
const PROJECTROOT = fs.realpathSync(process.cwd());

module.exports = merge(common, {
    // https://webpack.js.org/configuration/mode/
    mode: "production",

    devtool: "source-map",

    stats: "verbose",

    // https://webpack.js.org/configuration/module/
    module: {
        // An array of Rules which are matched to requests when modules are created.
        // These rules can modify how the module is created.
        // They can apply loaders to the module, or modify the parser.
        rules: [
            {
                test: /\.scss$/,
                use: [
                    // https://webpack.js.org/plugins/mini-css-extract-plugin/
                    {
                        loader: MiniCssExtractPlugin.loader,
                        options: {
                            esModule: false,
                        },
                    },
                    // https://webpack.js.org/loaders/css-loader/
                    {
                        loader: require.resolve("css-loader"),
                        options: {
                            sourceMap: true,
                            importLoaders: 2,
                        },
                    },
                    // https://github.com/webpack-contrib/postcss-loader
                    {
                        loader: require.resolve("postcss-loader"),
                        options: {
                            sourceMap: true,
                            postcssOptions: {
                                plugins: [["postcss-preset-env"]],
                            },
                        },
                    },
                    // https://webpack.js.org/loaders/sass-loader/
                    {
                        loader: require.resolve("sass-loader"),
                        options: {
                            sourceMap: true,
                            additionalData: `@import "~@/theme/abstracts/config";
@import "~@/theme/abstracts/functions";
@import "~@/theme/abstracts/mixins";`,
                        },
                    },
                ],
            },

            {
                // loader for images
                // https://github.com/webpack-contrib/url-loader
                test: /\.(png|jpe?g|gif|svg)$/,
                use: [
                    {
                        loader: "url-loader",
                        options: {
                            limit: 1024,
                            name: "[name].[hash:16].[ext]",
                            fallback: "file-loader",
                        },
                    },
                ],
            },
        ],
    },

    plugins: [
        // https://webpack.js.org/plugins/compression-webpack-plugin/
        new CompressionPlugin({
            test: /\.(js|css)$/,
            threshold: 8192,
            minRatio: 0.8,
            filename: "[path][base].gz",
            //cache: path.resolve(
            //    PROJECTROOT,
            //    "node_modules/.cache/compression-webpack-plugin"
            //),
        }),

        // https://webpack.js.org/plugins/mini-css-extract-plugin/
        new MiniCssExtractPlugin({
            filename: "[name].[chunkhash].css",
            ignoreOrder: true, // Enable to remove warnings about conflicting order
        }),

        // https://webpack.js.org/plugins/copy-webpack-plugin/
        new CopyPlugin({
            patterns: [
                {
                    from: path.resolve(PROJECTROOT, "resources/src/static"),
                    to: path.resolve(PROJECTROOT, "public/assets/"),
                },
            ],
        }),

        new ImageMinimizerPlugin({
            minimizerOptions: {
                // Lossless optimization with custom option
                // Feel free to experiment with options for better result for you
                plugins: [
                    ["gifsicle", { interlaced: true }],
                    ["jpegtran", { progressive: true }],
                    ["optipng", { optimizationLevel: 5 }],
                    [
                        "svgo",
                        {
                            plugins: extendDefaultPlugins([
                                {
                                    name: "removeViewBox",
                                    active: false,
                                },
                            ]),
                        },
                    ],
                ],
            },
        }),

        // https://webpack.js.org/plugins/banner-plugin/
        new webpack.BannerPlugin({
            banner: `@name Imperium Stellarum
@description ${pkg.description}
@version v${pkg.version}
@author ${pkg.author.name} <${pkg.author.email}>
@copyright ${pkg.author.name} | https://www.imperiumstellarum.io
@license ${pkg.license}
@compiled ${dateformat(new Date(), "isoDateTime")}
@chunkHash [chunkhash]
█▀▀▄░░░░░░░░░░░▄▀▀█
░█░░░▀▄░▄▄▄▄▄░▄▀░░░█
░░▀▄░░░▀░░░░░▀░░░▄▀
░░░░▌░▄▄░░░▄▄░▐▀▀
░░░▐░░█▄░░░▄█░░▌▄▄▀▀▀▀█
░░░▌▄▄▀▀░▄░▀▀▄▄▐░░░░░░█
▄▀▀▐▀▀░▄▄▄▄▄░▀▀▌▄▄▄░░░█
█░░░▀▄░█░░░█░▄▀░░░░█▀▀▀
░▀▄░░▀░░▀▀▀░░▀░░░▄█▀
░░░█░░░░░░░░░░░▄▀▄░▀▄
░░░█░░░░░░░░░▄▀█░░█░░█
░░░█░░░░░░░░░░░█▄█░░▄▀
░░░█░░░░░░░░░░░████▀
`,
        }),

        // https://www.npmjs.com/package/webpack-bundle-analyzer
        new BundleAnalyzerPlugin({
            analyzerMode: "static",
            reportFilename: path.resolve(
                PROJECTROOT,
                "webpack-bundles.report.html"
            ),
            defaultSizes: "gzip",
            openAnalyzer: false,
            generateStatsFile: true,
            statsFilename: path.resolve(PROJECTROOT, "webpack-stats.json"),
        }),
    ],
});
