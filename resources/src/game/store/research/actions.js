/******************************************************************************
 * Vuex actions
 *****************************************************************************/
import { notify } from "@/shared/notification";

/**
 * @function get gameId from #game
 * @returns {string}
 */
const getGameId = () => document.getElementById("game").dataset.gameId;

export default {
    /**
     * @function GET game data from api
     * @param {Function} commit - Vuex commit
     */
    GET_GAME_DATA: function ({ commit }) {
        commit("SET_REQUESTING", true);
        window.axios
            .get(`/api/game/${getGameId()}/research`)
            .then((response) => {
                if (response.status === 200) {
                    commit("SET_GAME_META_DATA", response.data, { root: true });
                    commit("SET_POPULATION", response.data.totalPopulation);
                }
            })
            .catch((e) => {
                console.error(e);
                notify(e.response.data.error, "error");
            })
            .finally(() => {
                commit("SET_REQUESTING", false);
            });
    },

    /**
     * @function SET research priority
     * @param {Function} commit - Vuex commit
     * @param {Number} payload
     */
    SET_RESEARCH_PRIORITY: function ({ commit }, payload) {
        commit("SET_REQUESTING", true);
        window.axios
            .post(`/api/game/${getGameId()}/setResearchPriority`, payload)
            .then((response) => {
                if (response.status === 200) {
                    commit("SET_RESEARCH_PRIORITY", payload.researchPriority, {
                        root: true,
                    });
                }
            })
            .catch((e) => {
                console.error(e);
                notify(e.response.data.error, "error");
            })
            .finally(() => {
                commit("SET_REQUESTING", false);
            });
    },
};
