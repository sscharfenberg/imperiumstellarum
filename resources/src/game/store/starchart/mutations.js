/******************************************************************************
 * Vuex mutations
 *****************************************************************************/
import { saveState } from "@/game/store/persistState";
import {
    filterStarsByViewport,
    zoomToTileSize,
    convertCenteredCoordsToCamera,
} from "Pages/Starchart/Map/useMap";

export default {
    /**
     * @function SET/UNSET "requesting"
     * @param {Object} state - vuex module "starchart" state
     * @param {Boolean} payload
     */
    SET_REQUESTING: (state, payload) => {
        state.requesting = payload;
    },

    /**
     * @function SET ALL stars
     * @param {Object} state - vuex module "starchart" state
     * @param {Array} payload
     */
    SET_STARS: (state, payload) => {
        state.stars = payload;
    },

    /**
     * @function SET PLAYER stars
     * @param {Object} state - vuex module "starchart" state
     * @param {Array} payload
     */
    SET_PLAYER_STARS: (state, payload) => {
        state.playerStars = payload;
    },

    /**
     * @function SET ALL players
     * @param {Object} state - vuex module "starchart" state
     * @param {Array} payload
     */
    SET_PLAYERS: (state, payload) => {
        state.players = payload;
    },

    /**
     * @function SET game dimensions
     * @param {Object} state - vuex module "starchart" state
     * @param {Array} payload
     */
    SET_DIMENSIONS: (state, payload) => {
        state.dimensions = payload;
    },

    /**
     * @function SET map zoom
     * @param {Object} state - vuex module "starchart" state
     * @param {Number} payload
     */
    SET_ZOOM: (state, payload) => {
        state.zoomLevel = payload;
        saveState(state.zoomLevel, "zoomLevel");
    },

    /**
     * @function SET camera
     * @param {Object} state - vuex module "starchart" state
     * @param {Object} payload
     * @param {Number} payload.x
     * @param {Number} payload.y
     */
    SET_CAMERA: (state, payload) => {
        state.cameraX = payload.x;
        state.cameraY = payload.y;
        saveState(state.cameraX, "cameraX");
        saveState(state.cameraY, "cameraY");
    },

    /**
     * @function SET filtered stars
     * @param {Object} state - vuex module "starchart" state
     * @param {Array} payload - array of all stars
     */
    SET_STARS_SHOWN: (state, payload) => {
        state.starsShown = filterStarsByViewport(
            payload,
            state.cameraX,
            state.cameraY,
            zoomToTileSize(state.zoomLevel),
            document.getElementById("mapWrapper").offsetWidth,
            state.dimensions
        );
    },

    /**
     * @function FOCUS THE MAP
     * @param {Object} state - vuex module "starchart" state
     * @param {Object} payload
     * @param {Number} payload.x - coordinate
     * @param {Number} payload.y - coordinate
     */
    FOCUS_COORDS: (state, payload) => {
        console.log(`focus camera on coords x=${payload.x}, y=${payload.y}`);
        // center map on coordinates
        const camera = convertCenteredCoordsToCamera(
            payload.x,
            payload.y,
            zoomToTileSize(state.zoomLevel),
            document.getElementById("mapWrapper").offsetWidth,
            state.dimensions
        );
        // set camera offset and save it.
        state.cameraX = camera.x;
        state.cameraY = camera.y;
        saveState(state.cameraX, "cameraX");
        saveState(state.cameraY, "cameraY");
        // re-filter stars after moving the map
        state.starsShown = filterStarsByViewport(
            state.stars,
            state.cameraX,
            state.cameraY,
            zoomToTileSize(state.zoomLevel),
            document.getElementById("mapWrapper").offsetWidth,
            state.dimensions
        );
        // add flash coords and remove them after the flash
        state.flashCoordX = payload.x;
        state.flashCoordY = payload.y;
        window.setTimeout(() => {
            state.flashCoordX = null;
            state.flashCoordY = null;
        }, 1000);
    },
};