/******************************************************************************
 * Vuex mutations
 *****************************************************************************/
//import { saveState } from "@/game/store/persistState";

export default {
    /**
     * @function SET/UNSET "requesting"
     * @param {Object} state - vuex module "encounters" state
     * @param {Boolean} payload
     */
    SET_REQUESTING: (state, payload) => {
        state.requesting = payload;
    },

    /**
     * @function SET encounters
     * @param {Object} state - vuex module "encounters" state
     * @param {Array} payload
     */
    SET_ENCOUNTERS: (state, payload) => {
        state.encounters = payload;
    },

    /**
     * @function SET encounter details
     * @param {Object} state - vuex module "encounters" state
     * @param {Object} payload
     */
    SET_ENCOUNTER_DETAILS: (state, payload) => {
        state.encounterDetails = payload;
    },

    /**
     * @function SET encounter players
     * @param {Object} state - vuex module "encounters" state
     * @param {Array} payload
     */
    SET_PLAYERS: (state, payload) => {
        state.players = payload;
    },

    /**
     * @function SET encounter stars
     * @param {Object} state - vuex module "encounters" state
     * @param {Array} payload
     */
    SET_STARS: (state, payload) => {
        state.stars = payload;
    },

    /**
     * @function SET player relations
     * @param {Object} state - vuex module "encounters" state
     * @param {Array} payload
     */
    SET_RELATIONS: (state, payload) => {
        state.relations = payload;
    },

    /**
     * @function SET encounter turn
     * @param {Object} state - vuex module "encounters" state
     * @param {Array} payload
     */
    SET_TURN: (state, payload) => {
        state.renderTurn = payload;
    },

    /**
     * @function SET tape playing
     * @param {Object} state - vuex module "encounters" state
     * @param {Boolean} payload
     */
    SET_TAPE_PLAYING: (state, payload) => {
        state.playing = payload;
    },
};
