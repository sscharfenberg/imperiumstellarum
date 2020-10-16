/******************************************************************************
 *
 * Webpack configuration
 * @type {NodeJS}
 *
 *****************************************************************************/

module.exports = {
    chunks: ["app", "game", "admin"],
    devServerProto: "http",
    devServerHost: "localhost",
    devServerPort: 7777,
    webpackStats: {
        excludeAssets: [
            (name) => {
                return (
                    name.match(/\.(gz?|php|map|svg)(\?.*)?$/i) ||
                    name.includes("favicons") ||
                    name.includes("fonts")
                );
            },
        ],
        colors: true,
        modules: false,
        children: false,
        chunks: false,
        chunkModules: false,
    },
};
