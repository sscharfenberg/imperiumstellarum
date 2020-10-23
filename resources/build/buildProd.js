/******************************************************************************
 *
 * create production bundle and corresponding files.
 * @type {NodeJS}
 *
 *****************************************************************************/
// Do this as the first thing so that any code reading it knows the right env.
process.env.BABEL_ENV = "production";
process.env.NODE_ENV = "production";
// node packages
const chalk = require("chalk");
const webpack = require("webpack");
// imports
const webpackConfig = require("./webpack/config.prod");
const cfg = require("./config");
// execute sync scripts before doing anything
require("./checkVersions"); // check node/npm versions
require("./cleanup"); // clean up generated files prior to creating new ones.

console.log(
    `${chalk.cyan("Preparing")} ${chalk.red("production")} ${chalk.cyan(
        "bundle.\n"
    )}`
);

webpack(webpackConfig, function (err, stats) {
    if (err) throw err;
    console.log();
    console.log(stats.toString(cfg.webpackStats));
    console.log(
        `[webpack] now serving files via ${chalk.yellow("Apache / Laravel")}.`
    );
    console.log(
        `[webpack] site is now in ${chalk.red(
            process.env.NODE_ENV.toUpperCase()
        )} mode.`
    );
    console.log(
        `[webpack] css is served via ${chalk.yellow("external css files")}.`
    );
});
