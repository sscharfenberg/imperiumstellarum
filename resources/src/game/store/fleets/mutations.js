/******************************************************************************
 * Vuex mutations
 *****************************************************************************/
//import { saveState } from "@/game/store/persistState";

export default {
    /**
     * @function SET/UNSET "requesting"
     * @param {Object} state - vuex module "fleets" state
     * @param {Boolean} payload
     */
    SET_REQUESTING: (state, payload) => {
        state.requesting = payload;
    },

    /**
     * @functon SET shipyards
     * @param {Object} state - vuex module "fleets" state
     * @param {Array} payload
     * @constructor
     */
    SET_SHIPYARDS: (state, payload) => {
        state.shipyards = payload;
    },

    /**
     * @functon SET fleets
     * @param {Object} state - vuex module "fleets" state
     * @param {Array} payload
     * @constructor
     */
    SET_FLEETS: (state, payload) => {
        state.fleets = payload;
    },

    /**
     * @functon SET stars
     * @param {Object} state - vuex module "fleets" state
     * @param {Array} payload
     * @constructor
     */
    SET_STARS: (state, payload) => {
        state.stars = payload;
    },

    /**
     * @functon SET stars
     * @param {Object} state - vuex module "fleets" state
     * @param {Number} payload
     * @constructor
     */
    SET_MAX_FLEETS: (state, payload) => {
        state.maxFleets = payload;
    },
};
