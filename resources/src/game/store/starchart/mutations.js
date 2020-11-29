/******************************************************************************
 * Vuex mutations
 *****************************************************************************/
import { saveState } from "@/game/store/persistState";

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
};
