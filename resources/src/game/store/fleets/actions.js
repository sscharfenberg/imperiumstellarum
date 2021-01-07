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
            .get(`/api/game/${getGameId()}/fleets`)
            .then((response) => {
                if (response.status === 200) {
                    commit("SET_GAME_META_DATA", response.data, { root: true });
                    commit("SET_SHIPYARDS", response.data.shipyards);
                    commit("SET_FLEETS", response.data.fleets);
                    commit("SET_STARS", response.data.stars);
                    commit("SET_MAX_FLEETS", response.data.maxFleets);
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
     * @function GET game data from api
     * @param {Function} commit - Vuex commit
     */
    CREATE_FLEET: function ({ commit }, payload) {
        commit("SET_REQUESTING", true);
        window.axios
            .post(`/api/game/${getGameId()}/fleets/create`, payload)
            .then((response) => {
                if (
                    response.status === 200 &&
                    response.data.fleets &&
                    response.data.message
                ) {
                    commit("SET_FLEETS", response.data.fleets);
                    commit("SET_CREATE_FLEET_LOCATION", "");
                    commit("SET_CREATE_FLEET_NAME", "");
                    commit("SET_SHOW_CREATE", false);
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
