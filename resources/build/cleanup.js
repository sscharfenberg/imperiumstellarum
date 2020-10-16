/******************************************************************************
 *
 * delete generated frontend files
 * @type {NodeJS}
 *
 *****************************************************************************/
const chalk = require("chalk");
const rimraf = require("rimraf");

const globs = ["public/assets"];

globs.forEach((glob) => {
    console.log("deleting " + chalk.red(glob));
    rimraf(glob, (err) => {
        if (err) throw err;
    });
    console.log();
});
