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
            .get(`/api/game/${getGameId()}/diplomacy`)
            .then((response) => {
                if (response.status === 200) {
                    commit("SET_GAME_META_DATA", response.data, { root: true });
                    commit("SET_PLAYERS", response.data.players);
                    commit("SET_RELATIONS", response.data.relations);
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
    CHANGE_RELATION: function ({ commit }, payload) {
        commit("SET_REQUESTING", true);
        window.axios
            .post(`/api/game/${getGameId()}/diplomacy/change`, payload)
            .then((response) => {
                if (response.status === 200) {
                    commit("SET_RELATIONS", response.data.relations);
                    commit(
                        "SET_RELATION_CHANGES",
                        response.data.relationChanges
                    );
                    notify(response.data.message, "success");
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
