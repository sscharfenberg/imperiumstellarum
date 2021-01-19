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
     * @functon SET players
     * @param {Object} state - vuex module "fleets" state
     * @param {Array} payload
     * @constructor
     */
    SET_PLAYERS: (state, payload) => {
        state.players = payload;
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
     * @param {String} payload
     * @constructor
     */
    SET_CREATE_FLEET_LOCATION: (state, payload) => {
        state.createLocation = payload;
    },

    /**
     * @functon SET create fleet name
     * @param {Object} state - vuex module "fleets" state
     * @param {String} payload
     * @constructor
     */
    SET_CREATE_FLEET_NAME: (state, payload) => {
        state.createName = payload;
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
        state.transferSourceId = payload;
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

    /**
     * @functon transfer shipId from source to target (left to right)
     * @param {Object} state - vuex module "fleets" state
     * @param {String} payload
     * @constructor
     */
    TRANSFER_SOURCE_TO_TARGET: (state, payload) => {
        state.transferSourceShipIds.splice(
            state.transferSourceShipIds.indexOf(payload),
            1
        );
        state.transferTargetShipIds.push(payload);
    },

    /**
     * @functon transfer shipId from target to source (right to left)
     * @param {Object} state - vuex module "fleets" state
     * @param {String} payload
     * @constructor
     */
    TRANSFER_TARGET_TO_SOURCE: (state, payload) => {
        state.transferTargetShipIds.splice(
            state.transferTargetShipIds.indexOf(payload),
            1
        );
        state.transferSourceShipIds.push(payload);
    },

    /**
     * @functon transfer all ships from source to target (left to right)
     * @param {Object} state - vuex module "fleets" state
     * @param {Array} payload
     * @constructor
     */
    TRANSFER_ALL_SOURCE_TO_TARGET: (state, payload) => {
        state.transferSourceShipIds = [];
        state.transferTargetShipIds = state.transferTargetShipIds.concat(
            payload
        );
    },

    /**
     * @functon transfer all ships from target to source (right to left)
     * @param {Object} state - vuex module "fleets" state
     * @param {Array} payload
     * @constructor
     */
    TRANSFER_ALL_TARGET_TO_SOURCE: (state, payload) => {
        state.transferSourceShipIds = state.transferSourceShipIds.concat(
            payload
        );
        state.transferTargetShipIds = [];
    },

    /**
     * @functon activate transfer submit
     * @param {Object} state - vuex module "fleets" state
     * @param {Boolean} payload
     * @constructor
     */
    SET_TRANSFER_SUBMIT_ACTIVE: (state, payload) => {
        state.transferSubmitActive = payload;
    },

    /**************************************************************************
     * FLEET MOVE MUTATIONS
     *************************************************************************/

    /**
     * @functon SET destination star id
     * @param {Object} state - vuex module "fleets" state
     * @param {String} payload
     * @constructor
     */
    SET_DESTINATION_STAR_ID: (state, payload) => {
        state.moveDestinationStarId = payload;
    },

    /**
     * @functon SET destination star id
     * @param {Object} state - vuex module "fleets" state
     * @param {String} payload
     * @constructor
     */
    SET_DESTINATION_COORD_X: (state, payload) => {
        state.moveCoordX = payload;
    },

    /**
     * @functon SET destination star id
     * @param {Object} state - vuex module "fleets" state
     * @param {String} payload
     * @constructor
     */
    SET_DESTINATION_COORD_Y: (state, payload) => {
        state.moveCoordY = payload;
    },

    /**
     * @functon SET destination star
     * @param {Object} state - vuex module "fleets" state
     * @param {Object} payload
     * @constructor
     */
    SET_DESTINATION_STAR: (state, payload) => {
        console.log("destination!", payload);
        state.destinationStar = payload;
        console.log("after mutating", state.destinationStar);
    },

    /**
     * @functon SET destination star
     * @param {Object} state - vuex module "fleets" state
     * @param {Object} payload
     * @constructor
     */
    SET_DESTINATION_OWNER: (state, payload) => {
        console.log("destination owner!", payload);
        state.destinationOwner = payload;
        console.log("owner after mutating", state.destinationOwner);
    },
};
