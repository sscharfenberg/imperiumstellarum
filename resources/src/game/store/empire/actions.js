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
        const gameId = document.getElementById("game").dataset.gameId;
        window.axios
            .get(`/api/game/${gameId}/empire`)
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
