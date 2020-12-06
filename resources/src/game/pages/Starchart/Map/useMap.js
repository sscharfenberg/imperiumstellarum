/******************************************************************************
 * common map functions
 *****************************************************************************/

/**
 * @function get tileSize from zoom level
 * @param zoom
 * @returns {number}
 */
export const zoomToTileSize = (zoom) => {
    switch (zoom) {
        case 0:
            return 20;
        case 1:
            return 35;
        case 2:
        default:
            return 50;
        case 3:
            return 65;
        case 4:
            return 80;
    }
};

/**
 * @function get the approximately centered coords from camera offset
 * @param {Number} x
 * @param {Number} y
 * @param {Number} tileSize
 * @param {Number} currentViewPortSize
 * @returns {Object}
 */
export const convertCameraToCenteredCoords = (
    x,
    y,
    tileSize,
    currentViewPortSize
) => {
    return {
        x: Math.round(x / tileSize + currentViewPortSize / (tileSize * 2)),
        y: Math.round(y / tileSize + currentViewPortSize / (tileSize * 2)),
    };
};

/**
 * @function get the camera offsets from centered coords
 * @param {Number} x
 * @param {Number} y
 * @param {Number} tileSize
 * @param {Number} currentViewPortSize
 * @param {Number} mapDimensions
 * @returns {Object}
 */
export const convertCenteredCoordsToCamera = (
    x,
    y,
    tileSize,
    currentViewPortSize,
    mapDimensions
) => {
    const centerCoord = currentViewPortSize / tileSize / 2;
    const coordsOffScreenX = x - centerCoord;
    const coordsOffScreenY = y - centerCoord;
    let cameraX = parseInt(coordsOffScreenX * tileSize, 10);
    let cameraY = parseInt(coordsOffScreenY * tileSize, 10);
    // check if we would have empty space to the right
    if (tileSize * mapDimensions < cameraX + currentViewPortSize) {
        cameraX = parseInt(tileSize * mapDimensions - currentViewPortSize, 10);
    }
    // ... or below the map
    if (tileSize * mapDimensions < cameraY + currentViewPortSize) {
        cameraY = parseInt(tileSize * mapDimensions - currentViewPortSize, 10);
    }
    // make sure we don't get negative numbers (negative camera => outside of map)
    if (cameraX < 0) cameraX = 0;
    if (cameraY < 0) cameraY = 0;
    return {
        x: cameraX,
        y: cameraY,
    };
};

/**
 * @function filter stars
 * for performance reasons we want to only have stars in the current viewport
 * and one viewport to top/right/left/bottom
 * the bounding rect for the filter function is
 * ###
 * #*#
 * ###
 * (* = current viewport, # = currently offscreen, 1/4 of viewPort size)
 * as a maximum we only want to render 3x the size of the current viewport.
 * @param {Array} stars
 * @param {Number} cameraX
 * @param {Number} cameraY
 * @param {Number} tileSize
 * @param {Number} viewPortSize
 * @param {Number} mapDimensions
 * @returns {Array}
 */
export const filterStarsByViewport = (
    stars,
    cameraX,
    cameraY,
    tileSize,
    viewPortSize,
    mapDimensions
) => {
    console.log("stars before filtering:", stars.length);
    console.log(
        `current camera: x=${cameraX}, y=${cameraY}, tileSize=${tileSize}, viewPortSize=${viewPortSize}, dim=${mapDimensions}`
    );

    // don't filter anything if there is 200 stars or less.
    if (stars.length <= 200) {
        console.log("less than 200 stars, not filtering.");
        return stars;
    }

    // calculate the coordinates in the top left.
    const topLeftX = Math.floor(cameraX / tileSize);
    const topLeftY = Math.floor(cameraY / tileSize);
    const viewPortCoords = Math.ceil(viewPortSize / tileSize);
    // calculate bounds: xMin, xMax, yMin, yMax.
    const bounds = {
        xMin: topLeftX - Math.floor(viewPortCoords * 0.75),
        xMax:
            topLeftX + Math.ceil(viewPortCoords * 1.25) > mapDimensions
                ? mapDimensions
                : topLeftX + Math.ceil(viewPortCoords * 1.25),
        yMin: topLeftY - Math.floor(viewPortCoords * 0.75),
        yMax:
            topLeftY + Math.ceil(viewPortCoords * 1.25) > mapDimensions
                ? mapDimensions
                : topLeftY + Math.ceil(viewPortCoords * 1.25),
    };

    // filter stars according to bounds
    stars = stars.filter(
        (star) =>
            star.x >= bounds.xMin &&
            star.x <= bounds.xMax &&
            star.y >= bounds.yMin &&
            star.y <= bounds.yMax
    );

    console.log(
        "bounds for filtered stars:",
        bounds,
        "resulting in",
        stars.length,
        "remaining stars"
    );

    // filter stars and return them
    return stars;
};
