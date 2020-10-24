/******************************************************************************
 * Vuex mutations
 *****************************************************************************/

export default {
    /*
     * SET/UNSET "requesting"
     * @param {Object} state - Vuex state
     * @param {Boolean} payload
     */
    SET_REQUESTING: (state, payload) => {
        state.requesting = payload;
    },
};
