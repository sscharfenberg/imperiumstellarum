/******************************************************************************
 * Vuex actions
 *****************************************************************************/

export default {
    /*
     * GET game data from api
     * @param {Object} ctx - Vuex context
     */
    GET_GAME_DATA: function ({ commit }) {
        commit("SET_REQUESTING", true);
        window.axios
            .get("/api/game/empire")
            .then((response) => {
                if (response.status === 200) {
                    commit("SET_GAME_META_DATA", response.data, { root: true });
                }
            })
            .catch((e) => {
                console.error(e);
            })
            .finally(() => {
                commit("SET_REQUESTING", false);
            });
    },
};
