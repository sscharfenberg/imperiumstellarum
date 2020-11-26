/******************************************************************************
 * Vuex mutations
 *****************************************************************************/
//import { saveState } from "@/game/store/persistState";

export default {
    /**
     * @function SET/UNSET "requesting"
     * @param {Object} state - vuex module "research" state
     * @param {Boolean} payload
     */
    SET_REQUESTING: (state, payload) => {
        state.requesting = payload;
    },

    /**
     * @function SET ALL stars
     * @param {Object} state - vuex module "research" state
     * @param {Array} payload
     */
    SET_STARS: (state, payload) => {
        state.stars = payload;
    },

    /**
     * @function SET ALL players
     * @param {Object} state - vuex module "research" state
     * @param {Array} payload
     */
    SET_PLAYERS: (state, payload) => {
        state.players = payload;
    },
};
