/******************************************************************************
 *
 * Webpack base config
 * @type {NodeJS}
 *
 *****************************************************************************/
// node internals
const path = require("path");
const fs = require("fs");
// node packages
const { VueLoaderPlugin } = require("vue-loader");
const HtmlWebpackPlugin = require("html-webpack-plugin");
const HtmlWebpackHarddiskPlugin = require("html-webpack-harddisk-plugin");
const StyleLintPlugin = require("stylelint-webpack-plugin");
const ESLintPlugin = require("eslint-webpack-plugin");
// imports
const cfg = require("../config");
// variables
const PROJECTROOT = fs.realpathSync(process.cwd());
const isProd = process.env.NODE_ENV === "production";

const webpackCommonConfig = {
    // https://webpack.js.org/configuration/target/
    target: "web",

    context: path.resolve(PROJECTROOT, "resources/src"),

    // https://webpack.js.org/configuration/entry-context/#entry
    entry: {
        app: path.resolve(PROJECTROOT, "resources/src/app/entry.js"),
        game: path.resolve(PROJECTROOT, "resources/src/game/entry.js"),
        admin: path.resolve(PROJECTROOT, "resources/src/admin/entry.js"),
    },

    // https://webpack.js.org/configuration/output/
    output: {
        path: path.resolve(PROJECTROOT, "public", "assets"),
        hashDigestLength: 16,
        filename: "[name].[chunkhash].js",
        publicPath: "/assets/",
    },

    // https://webpack.js.org/configuration/resolve/
    resolve: {
        // https://webpack.js.org/configuration/resolve/#resolveextensions
        extensions: [".js", ".vue", ".json", ".scss"],
        // https://webpack.js.org/configuration/resolve/#resolvealias
        alias: {
            vue$: "vue/dist/vue.runtime.esm-bundler.js",
            "@": path.resolve(PROJECTROOT, "resources/src"),
            Components: path.resolve(PROJECTROOT, "resources/src/components"),
        },
    },

    // https://webpack.js.org/configuration/module/
    module: {
        // https://webpack.js.org/configuration/module/#modulenoparse
        noParse: /^(vue|vue-router|vuex|vuex-router-sync)$/,

        // An array of Rules which are matched to requests when modules are created.
        // These rules can modify how the module is created.
        // They can apply loaders to the module, or modify the parser.
        rules: [
            {
                // https://github.com/vuejs/vue-loader
                // https://vue-loader.vuejs.org/en/
                test: /\.vue$/,
                use: [
                    {
                        loader: require.resolve("vue-loader"),
                        options: {
                            cacheDirectory: path.resolve(
                                PROJECTROOT,
                                "node_modules/.cache/vue-loader"
                            ),
                            babelParserPlugins: [
                                "jsx",
                                "classProperties",
                                "decorators-legacy",
                            ],
                        },
                    },
                ],
            },

            {
                // https://github.com/babel/babel-loader
                test: /\.(js)$/,
                exclude: /node_modules/,
                loader: require.resolve("babel-loader"),
                options: {
                    cacheDirectory: path.resolve(
                        PROJECTROOT,
                        "node_modules/.cache/babel-loader"
                    ),
                },
            },

            {
                // loader for fonts
                test: /\.(woff2?|eot|ttf|otf)(\?.*)?$/i,
                use: [
                    {
                        loader: require.resolve("url-loader"),
                        options: {
                            limit: 4096,
                            fallback: {
                                loader: require.resolve("file-loader"),
                                options: {
                                    name: "fonts/[name].[ext]",
                                },
                            },
                        },
                    },
                ],
            },
        ],
    },

    // https://webpack.js.org/configuration/optimization/
    optimization: {
        // https://webpack.js.org/plugins/split-chunks-plugin/
        splitChunks: {
            cacheGroups: {
                vendors: {
                    name: "chunk-vendors",
                    test: /[\\/]node_modules[\\/]((?!(flatpickr|nouislider|poisson-disk-sampling)).*)[\\/]/,
                    priority: -10,
                    chunks: "initial",
                },
                //common: {
                //    name: "chunk-common",
                //    minChunks: 2,
                //    priority: -20,
                //    chunks: "initial",
                //    reuseExistingChunk: true,
                //},
            },
        },
    },

    plugins: [
        // http://eslint.org
        // https://github.com/webpack-contrib/eslint-webpack-plugin
        new ESLintPlugin({
            formatter: "stylish",
            overrideConfigFile: path.resolve(
                PROJECTROOT,
                "resources/build/config/.eslintrc.js"
            ),
        }),

        // https://vue-loader.vuejs.org/guide/#manual-setup
        new VueLoaderPlugin(),

        // https://github.com/stylelint/stylelint
        new StyleLintPlugin({
            files: ["**/*.?(vue|scss)"],
            syntax: "scss",
            reporters: [{ formatter: "verbose", console: true }],
            configFile: path.resolve(
                PROJECTROOT,
                "resources/build/config/.stylelintrc.js"
            ),
        }),

        // https://github.com/jantimon/html-webpack-plugin
        new HtmlWebpackPlugin({
            inject: false,
            template: path.resolve(
                PROJECTROOT,
                "resources/build/templates/app.ejs"
            ),
            filename: path.resolve(
                PROJECTROOT,
                "resources/views/layouts/app.blade.php"
            ),
            alwaysWriteToDisk: true,
            devServer: isProd
                ? false
                : `${cfg.devServerProto}://${cfg.devServerHost}:${cfg.devServerPort}`,
            minify: false, // temp
        }),

        // https://github.com/jantimon/html-webpack-harddisk-plugin
        new HtmlWebpackHarddiskPlugin(),
    ],
};

// create a seperate layout template for each chunk
cfg.chunks.forEach((chunk) => {
    webpackCommonConfig.plugins.push(
        // https://github.com/jantimon/html-webpack-plugin
        new HtmlWebpackPlugin({
            inject: false,
            template: path.resolve(
                PROJECTROOT,
                `resources/build/templates/${chunk}.ejs`
            ),
            filename: path.resolve(
                PROJECTROOT,
                `resources/views/layouts/${chunk}.blade.php`
            ),
            alwaysWriteToDisk: true,
            devServer: isProd
                ? false
                : `${cfg.devServerProto}://${cfg.devServerHost}:${cfg.devServerPort}`,
            chunkName: chunk,
            minify: false, // temp
        })
    );
});

module.exports = webpackCommonConfig;
