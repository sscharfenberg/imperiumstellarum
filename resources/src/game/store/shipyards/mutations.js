/******************************************************************************
 * Vuex mutations
 *****************************************************************************/

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
     * @functon SET shipyards
     * @param {Object} state
     * @param {Array} payload
     * @constructor
     */
    SET_SHIPYARDS: (state, payload) => {
        state.shipyards = payload;
    },
};
