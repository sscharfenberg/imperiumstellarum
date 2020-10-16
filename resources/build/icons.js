/******************************************************************************
 *
 * merge svg icons into a single svg file with symbols
 * @type {NodeJS}
 *
 *****************************************************************************/
// node internals
const path = require("path");
const fs = require("fs");
// node packages
const svgo = require("svgo");
const svgstore = require("svgstore");
const chalk = require("chalk");
// variables/functions/objects
const PROJECTROOT = fs.realpathSync(process.cwd());
const writeSpriteSheet = (sprites) => {
    console.log(
        chalk.cyan(
            `Prepared SVG sprite with ${chalk.yellow(
                baseFiles.length
            )} Symbols.`
        )
    );
    fs.writeFileSync(OUT_PATH, sprites.toString({ inline: true }));
    console.log(
        chalk.green(
            `Done writing sprite with ${chalk.yellow(
                baseFiles.length
            )} symbol IDs: ${chalk.magenta(
                JSON.stringify(
                    baseFiles.map((file) => file.replace(".svg", "")),
                    null,
                    2
                )
            )}`
        )
    );
};
const svgoInstance = new svgo({
    plugins: [
        { cleanupAttrs: true },
        { removeDoctype: true },
        { removeXMLProcInst: true },
        { removeComments: true },
        { removeMetadata: true },
        { removeTitle: true },
        { removeDesc: true },
        { removeUselessDefs: true },
        { removeEditorsNSData: true },
        { removeEmptyAttrs: true },
        { removeHiddenElems: true },
        { removeEmptyText: true },
        { removeEmptyContainers: true },
        { removeViewBox: false },
        { cleanUpEnableBackground: true },
        { convertStyleToAttrs: true },
        { convertColors: true },
        { convertPathData: true },
        { convertTransform: true },
        { removeUnknownsAndDefaults: true },
        { removeNonInheritableGroupAttrs: true },
        { removeUselessStrokeAndFill: true },
        { removeUnusedNS: true },
        { cleanupIDs: true },
        { cleanupNumericValues: true },
        { moveElemsAttrsToGroup: true },
        { moveGroupAttrsToElems: true },
        { collapseGroups: true },
        { removeRasterImages: false },
        { mergePaths: true },
        { convertShapeToPath: true },
        { sortAttrs: true },
        { transformsWithOnePath: false },
        { removeDimensions: true },
    ],
});

const BASE_PATH = path.resolve(PROJECTROOT, "resources/src/icons");
const SVGO_PATH = path.resolve(PROJECTROOT, "public", "svgo");
const OUT_PATH = path.resolve(PROJECTROOT, "storage/app/public/iconsprite.svg");
const baseFiles = fs.readdirSync(BASE_PATH);

//console.log(SVGO_PATH);
//console.log(fs.existsSync(SVGO_PATH));
//process.exit(0);

if (!fs.existsSync(SVGO_PATH)) {
    console.log(`creating tmp dir ${chalk.yellow(SVGO_PATH)}...`);
    fs.mkdirSync(SVGO_PATH);
}

// prepare svgStore object
let sprites = svgstore({
    cleanDefs: true,
    cleanSymbols: true,
    svgAttrs: {
        class: "iconsprite",
        "aria-hidden": "true",
        xmlns: "http://www.w3.org/2000/svg",
    },
});

// go through SVGs and add them one by one
let counter = 0;
baseFiles.forEach((file) => {
    const filePath = path.join(BASE_PATH, file);
    const id = file.replace(".svg", "");
    const svgFile = fs.readFileSync(filePath, "utf8");
    svgoInstance.optimize(svgFile, { path: filePath }).then(function (result) {
        counter++;
        sprites.add(id, result.data);
        if (counter === baseFiles.length) {
            // all svgs optimized.
            writeSpriteSheet(sprites);
            fs.rmdirSync(SVGO_PATH);
        }
    });
});
