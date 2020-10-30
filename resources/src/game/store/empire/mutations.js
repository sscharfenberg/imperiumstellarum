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

    /*
     * SET PLAYER STARS
     * @param {Object} state - Vuex state
     * @param {Array} payload
     */
    SET_STARS: (state, payload) => {
        state.stars = payload;
    },

    /*
     * SET PLAYER PLANETS
     * @param {Object} state - Vuex state
     * @param {Array} payload
     */
    SET_PLANETS: (state, payload) => {
        state.planets = payload;
    },
};
