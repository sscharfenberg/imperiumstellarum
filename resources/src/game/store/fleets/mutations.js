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
     * @functon SET ships
     * @param {Object} state - vuex module "fleets" state
     * @param {Array} payload
     * @constructor
     */
    SET_SHIPS: (state, payload) => {
        state.ships = payload;
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

    /**************************************************************************
     * CREATE FLEET MUTATIONS
     *************************************************************************/

    /**
     * @functon SET create fleet location
     * @param {Object} state - vuex module "fleets" state
     * @param {Boolean} payload
     * @constructor
     */
    SET_SHOW_CREATE: (state, payload) => {
        state.create.show = payload;
    },

    /**
     * @functon SET create fleet location
     * @param {Object} state - vuex module "fleets" state
     * @param {String} payload
     * @constructor
     */
    SET_CREATE_FLEET_LOCATION: (state, payload) => {
        state.create.location = payload;
    },

    /**
     * @functon SET create fleet name
     * @param {Object} state - vuex module "fleets" state
     * @param {String} payload
     * @constructor
     */
    SET_CREATE_FLEET_NAME: (state, payload) => {
        state.create.name = payload;
    },

    /**************************************************************************
     * FLEET TRANSFER MUTATIONS
     *************************************************************************/

    /**
     * @functon SET transferSourceId
     * @param {Object} state - vuex module "fleets" state
     * @param {String} payload
     * @constructor
     */
    SET_TRANSFER_SOURCE_ID: (state, payload) => {
        console.log("setting sourceid to", payload);
        state.transferSourceId = payload;
        console.log("sourceid now", state.transferSourceId);
    },

    /**
     * @functon SET transferSourceShipIds
     * @param {Object} state - vuex module "fleets" state
     * @param {String} payload
     * @constructor
     */
    SET_TRANSFER_SOURCE_SHIP_IDS: (state, payload) => {
        state.transferSourceShipIds = payload;
    },

    /**
     * @functon SET transferTargetId
     * @param {Object} state - vuex module "fleets" state
     * @param {String} payload
     * @constructor
     */
    SET_TRANSFER_TARGET_ID: (state, payload) => {
        state.transferTargetId = payload;
    },

    /**
     * @functon SET transferTargetShipIds
     * @param {Object} state - vuex module "fleets" state
     * @param {String} payload
     * @constructor
     */
    SET_TRANSFER_TARGET_SHIP_IDS: (state, payload) => {
        state.transferTargetShipIds = payload;
    },
};
