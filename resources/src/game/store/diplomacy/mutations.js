/******************************************************************************
 * Vuex mutations
 *****************************************************************************/
import { saveState } from "@/game/store/persistState";

//import { saveState } from "@/game/store/persistState";

export default {
    /**
     * @function SET/UNSET "requesting"
     * @param {Object} state - vuex module "diplomacy" state
     * @param {Boolean} payload
     */
    SET_REQUESTING: (state, payload) => {
        state.requesting = payload;
    },

    /**
     * @function SET all players
     * @param {Object} state - vuex module "diplomacy" state
     * @param {Array} payload
     */
    SET_PLAYERS: (state, payload) => {
        state.players = payload;
    },

    /**
     * @function SET relations of this player
     * @param {Object} state - vuex module "diplomacy" state
     * @param {Array} payload
     */
    SET_RELATIONS: (state, payload) => {
        state.relations = payload;
    },

    /**
     * @function SET relationChanges of this player
     * @param {Object} state - vuex module "diplomacy" state
     * @param {Array} payload
     */
    SET_RELATION_CHANGES: (state, payload) => {
        state.relationChanges = payload;
    },

    /**
     * filter empires state
     */

    /**
     * @function SET show allies
     * @param {Object} state - vuex module "diplomacy" state
     * @param {Boolean} payload
     */
    SET_SHOW_ALLIES: (state, payload) => {
        state.showAllies = payload;
        saveState(payload, "diplomacyShowAllies");
    },

    /**
     * @function SET show neutrals
     * @param {Object} state - vuex module "diplomacy" state
     * @param {Boolean} payload
     */
    SET_SHOW_NEUTRALS: (state, payload) => {
        state.showNeutrals = payload;
        saveState(payload, "diplomacyShowNeutrals");
    },

    /**
     * @function SET show hostiles
     * @param {Object} state - vuex module "diplomacy" state
     * @param {Boolean} payload
     */
    SET_SHOW_HOSTILES: (state, payload) => {
        state.showHostiles = payload;
        saveState(payload, "diplomacyShowHostiles");
    },

    /**
     * @function SET sort direction
     * @param {Object} state - vuex module "diplomacy" state
     * @param {String} payload
     */
    SET_SORT_DIRECTION: (state, payload) => {
        state.sort = payload;
        saveState(payload, "diplomacySort");
    },

    /**
     * @function SET show hostiles
     * @param {Object} state - vuex module "diplomacy" state
     * @param {String} payload
     */
    SET_FILTER_TICKER: (state, payload) => {
        state.filterByTicker = payload;
    },
};
