/******************************************************************************
 *
 * Webpack Development config
 * @type {NodeJS}
 *
 *****************************************************************************/
// node packages
const { merge } = require("webpack-merge");
const webpack = require("webpack");
// imports
const common = require("./config.common");

module.exports = merge(common, {
    // https://webpack.js.org/configuration/mode/
    mode: "development",

    devtool: "eval-cheap-module-source-map",

    output: {
        filename: "[name].js",
        publicPath: "http://localhost:8888/",
    },

    // https://webpack.js.org/configuration/module/
    module: {
        // An array of Rules which are matched to requests when modules are created.
        // These rules can modify how the module is created.
        // They can apply loaders to the module, or modify the parser.
        rules: [
            {
                test: /\.scss$/,
                use: [
                    // https://github.com/vuejs/vue-style-loader
                    {
                        loader: require.resolve("vue-style-loader"),
                        options: {
                            sourceMap: true,
                            shadowMode: false,
                        },
                    },
                    // https://webpack.js.org/loaders/css-loader/
                    {
                        loader: require.resolve("css-loader"),
                        options: {
                            sourceMap: true,
                            importLoaders: 2,
                            esModule: false,
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
                            name: "[name].[ext]",
                            fallback: "file-loader",
                        },
                    },
                ],
            },
        ],
    },

    plugins: [
        // https://webpack.js.org/plugins/hot-module-replacement-plugin/
        new webpack.HotModuleReplacementPlugin(),
    ],
});
