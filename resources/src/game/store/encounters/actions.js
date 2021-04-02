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
            .get(`/api/game/${getGameId()}/encounters`)
            .then((response) => {
                if (response.status === 200) {
                    commit("SET_GAME_META_DATA", response.data, { root: true });
                    commit("SET_ENCOUNTERS", response.data.encounters);
                    commit("SET_PLAYERS", response.data.players);
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
     * @function GET encounter details
     * @param {Function} commit - Vuex commit
     * @param {String} payload - encounterId
     */
    GET_ENCOUNTER_DETAILS: function ({ commit }, payload) {
        commit("SET_REQUESTING", true);
        window.axios
            .get(`/api/game/${getGameId()}/encounters/${payload}/details`)
            .then((response) => {
                if (response.status === 200) {
                    commit("SET_GAME_META_DATA", response.data, { root: true });
                    commit(
                        "SET_ENCOUNTER_DETAILS",
                        response.data.encounterDetails
                    );
                    commit("SET_ENCOUNTERS", response.data.encounters);
                    commit("SET_PLAYERS", response.data.players);
                    commit("SET_TURN", 0);
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
