/******************************************************************************
 * Vuex actions
 *****************************************************************************/

/**
 * @function get gameId from #game
 * @returns {string}
 */
const getGameId = () => document.getElementById("game").dataset.gameId;

export default {
    /*
     * GET game data from api
     * @param {Object} ctx - Vuex context
     */
    GET_GAME_DATA: function ({ commit }) {
        commit("SET_REQUESTING", true);
        window.axios
            .get(`/api/game/${getGameId()}/empire`)
            .then((response) => {
                if (response.status === 200) {
                    commit("SET_GAME_META_DATA", response.data, { root: true });
                    commit("SET_STARS", response.data.stars);
                    commit("SET_PLANETS", response.data.planets);
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
