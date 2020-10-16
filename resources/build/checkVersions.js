/******************************************************************************
 *
 * check node and npm versions
 * @type {NodeJS}
 *
 *****************************************************************************/
const chalk = require("chalk");
const semver = require("semver");
const pkg = require("../../package.json");
function exec(cmd) {
    return require("child_process").execSync(cmd).toString().trim();
}

const versionRequirements = [
    {
        name: "node",
        currentVersion: semver.clean(process.version),
        versionRequirement: pkg.engines.node,
    },
    {
        name: "npm",
        currentVersion: exec("npm --version"),
        versionRequirement: pkg.engines.npm,
    },
];

const warnings = [];
for (let i = 0; i < versionRequirements.length; i++) {
    const mod = versionRequirements[i];
    if (!semver.satisfies(mod.currentVersion, mod.versionRequirement)) {
        warnings.push(
            mod.name +
                ": " +
                chalk.red(mod.currentVersion) +
                " should be " +
                chalk.green(mod.versionRequirement)
        );
    }
}

if (warnings.length) {
    console.log();
    console.log(
        chalk.yellow(
            "In order to use these build tools, please update the following software:"
        )
    );
    console.log();
    for (let i = 0; i < warnings.length; i++) {
        const warning = warnings[i];
        console.log(warning);
    }
    console.log();
    process.exit(1);
} else {
    console.log(chalk.yellow("Node and npm versions are ok.\n"));
}
