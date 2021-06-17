/******************************************************************************
 * Vuex mutations
 *****************************************************************************/
import { saveState } from "@/game/store/persistState";

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
     * @function SET encounters per page
     * @param {Object} state - vuex module "messages" state
     * @param {Number} payload
     */
    SET_PER_PAGE: (state, payload) => {
        state.perPage = payload;
        saveState(state.perPage, "encountersPerPage");
    },

    /**
     * @function SET raids as raider per page
     * @param {Object} state - vuex module "messages" state
     * @param {Number} payload
     */
    SET_RAIDS_RAIDER_PER_PAGE: (state, payload) => {
        state.raidsAsRaiderPerPage = payload;
        saveState(state.perPage, "raidsAsRaiderPerPage");
    },

    /**
     * @function SET raids as raided per page
     * @param {Object} state - vuex module "messages" state
     * @param {Number} payload
     */
    SET_RAIDS_RAIDED_PER_PAGE: (state, payload) => {
        state.raidsAsRaidedPerPage = payload;
        saveState(state.perPage, "raidsAsRaidedPerPage");
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

    /**
     * @function SET raids
     * @param {Object} state - vuex module "encounters" state
     * @param {Array} payload
     */
    SET_RAIDS: (state, payload) => {
        state.raids = payload;
    },

    /**
     * @function SET encounters page
     * @param {Object} state - vuex module "encounters" state
     * @param {Number} payload
     */
    SET_PAGE: (state, payload) => {
        state.page = payload;
        saveState(state.page, "encountersPage");
    },
};
