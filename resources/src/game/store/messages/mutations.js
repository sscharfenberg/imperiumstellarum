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
     * @function SET broadcasts
     * @param {Object} state - vuex module "fleets" state
     * @param {Array} payload
     */
    SET_INBOX: (state, payload) => {
        state.inbox = payload;
    },

    /**
     * @function SET sent broadcasts
     * @param {Object} state - vuex module "fleets" state
     * @param {Array} payload
     */
    SET_OUTBOX: (state, payload) => {
        state.outbox = payload;
    },
};
