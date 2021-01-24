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
            .get(`/api/game/${getGameId()}/starchart`)
            .then((response) => {
                if (response.status === 200) {
                    commit("SET_GAME_META_DATA", response.data, { root: true });
                    commit("SET_STARS", response.data.stars);
                    commit("SET_STARS_SHOWN", response.data.stars);
                    commit("SET_PLAYERS", response.data.players);
                    commit("SET_FLEETS", response.data.fleets);
                    commit("SET_FLEET_SHIPS", response.data.ships);
                    commit("SET_FLEET_MOVEMENTS", response.data.fleetMovements);
                    commit("SET_DIMENSIONS", response.data.dimensions);
                    commit("SET_PLAYER_STARS", response.data.playerStars);
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
    GET_INITIAL_GAME_DATA: function ({ commit }) {
        commit("SET_REQUESTING", true);
        window.axios
            .get(`/api/game/${getGameId()}/starchart`)
            .then((response) => {
                if (response.status === 200) {
                    commit("SET_GAME_META_DATA", response.data, { root: true });
                    commit("SET_STARS", response.data.stars);
                    commit("SET_PLAYERS", response.data.players);
                    commit("SET_FLEETS", response.data.fleets);
                    commit("SET_FLEET_SHIPS", response.data.ships);
                    commit("SET_FLEET_MOVEMENTS", response.data.fleetMovements);
                    commit("SET_DIMENSIONS", response.data.dimensions);
                    commit("SET_PLAYER_STARS", response.data.playerStars);
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
     * @function POST get own system details for travel time
     * @param {Function} commit - Vuex commit
     * @param {Object} payload
     */
    SEND_FLEET: function ({ commit }, payload) {
        commit("SET_REQUESTING", true);
        window.axios
            .post(`/api/game/${getGameId()}/fleets/destination/send`, payload)
            .then((response) => {
                if (
                    response.status === 200 &&
                    response.data.fleets &&
                    response.data.fleetMovements
                ) {
                    commit("SET_FLEETS", response.data.fleets);
                    commit("SET_FLEET_MOVEMENTS", response.data.fleetMovements);
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
