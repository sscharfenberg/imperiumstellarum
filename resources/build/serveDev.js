/******************************************************************************
 *
 * start development server and serve files this way.
 * @type {NodeJS}
 *
 *****************************************************************************/
// Do this as the first thing so that any code reading it knows the right env.
process.env.BABEL_ENV = "development";
process.env.NODE_ENV = "development";
// node internals
const path = require("path");
const fs = require("fs");
// node packages
const chalk = require("chalk");
const webpack = require("webpack");
const WebpackDevServer = require("webpack-dev-server");
// imports
const webpackConfig = require("./webpack/config.dev");
const cfg = require("./config");
// execute sync scripts before doing anything
require("./checkVersions"); // check node/npm versions
// variables
const PROJECTROOT = fs.realpathSync(process.cwd());
const compiler = webpack(webpackConfig);
const devServerUrl = `${cfg.devServerProto}://${cfg.devServerHost}:${cfg.devServerPort}`;
let initialCompile = true;

console.log(
    `${chalk.cyan("Preparing")} ${chalk.red("development")} ${chalk.cyan(
        "bundle.\n"
    )}`
);

/*
 * create the webpack-dev-server
 * https://webpack.js.org/configuration/dev-server/
 */
let dev = new WebpackDevServer(compiler, {
    proxy: {
        // https://webpack.js.org/configuration/dev-server/#devserverproxy
        "*": devServerUrl,
    },
    headers: {
        // https://webpack.js.org/configuration/dev-server/#devserverheaders-
        "X-Powered-By": "Webpack Dev Server",
        "Access-Control-Allow-Origin": "*",
    },
    hot: true,
    compress: true,
    quiet: false,
    overlay: true,
    stats: cfg.webpackStats,
    host: cfg.devServerHost,
    port: cfg.devServerPort,
    contentBase: path.resolve(PROJECTROOT, "public/assets/"),
    https: false,
    disableHostCheck: true,
});

/*
 * startup webpack-dev-server
 */
dev.listen(cfg.devServerPort, cfg.devServerHost, (err) => {
    if (err) throw err;
    console.log(
        `[webpack] site is now in ${chalk.red(
            process.env.NODE_ENV.toUpperCase()
        )} mode.`
    );
    console.log(
        `[webpack] css is served via ${chalk.yellow(
            "vue-style-loader"
        )} injected as style blob into head.`
    );
});

/*
 * log finished compilations to console
 */
compiler.hooks.done.tap("compilation", () => {
    console.log(
        chalk.red(
            `[webpack] ${chalk.green(
                initialCompile ? "initial" : "change"
            )} compilation done:`
        )
    );
    initialCompile = false;
});
