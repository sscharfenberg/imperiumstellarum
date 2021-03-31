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
     * @param {Boolean} payload
     */
    SET_ENCOUNTERS: (state, payload) => {
        state.encounters = payload;
    },
};
