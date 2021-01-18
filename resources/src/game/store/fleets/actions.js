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
                    commit("SET_SHIPS", response.data.ships);
                    commit("SET_STARS", response.data.stars);
                    commit("SET_PLAYERS", response.data.players);
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
     * @function POST create fleet
     * @param {Function} commit - Vuex commit
     * @param {Object} payload
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

    /**
     * @function POST change fleet name
     * @param {Function} commit - Vuex commit
     * @param {Object} payload
     */
    CHANGE_FLEET_NAME: function ({ commit }, payload) {
        commit("SET_REQUESTING", true);
        window.axios
            .post(`/api/game/${getGameId()}/fleets/name`, payload)
            .then((response) => {
                if (
                    response.status === 200 &&
                    response.data.fleets &&
                    response.data.message
                ) {
                    commit("SET_FLEETS", response.data.fleets);
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

    /**
     * @function POST delete fleet
     * @param {Function} commit - Vuex commit
     * @param {Object} payload
     */
    DELETE_FLEET: function ({ commit }, payload) {
        commit("SET_REQUESTING", true);
        window.axios
            .post(`/api/game/${getGameId()}/fleets/delete`, payload)
            .then((response) => {
                if (
                    response.status === 200 &&
                    response.data.fleets &&
                    response.data.message
                ) {
                    commit("SET_FLEETS", response.data.fleets);
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

    /**
     * @function POST change ship name
     * @param {Function} commit - Vuex commit
     * @param {Object} payload
     */
    CHANGE_SHIP_NAME: function ({ commit }, payload) {
        commit("SET_REQUESTING", true);
        window.axios
            .post(`/api/game/${getGameId()}/fleets/shipName`, payload)
            .then((response) => {
                if (
                    response.status === 200 &&
                    response.data.ships &&
                    response.data.message
                ) {
                    commit("SET_SHIPS", response.data.ships);
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

    /**
     * @function POST submit ship transfer
     * @param {Function} commit - Vuex commit
     * @param {Object} payload
     */
    SUBMIT_SHIP_TRANSFER: function ({ commit }, payload) {
        commit("SET_REQUESTING", true);
        window.axios
            .post(`/api/game/${getGameId()}/fleets/transfer`, payload)
            .then((response) => {
                if (
                    response.status === 200 &&
                    response.data.ships &&
                    response.data.fleets &&
                    response.data.message
                ) {
                    commit("SET_SHIPS", response.data.ships);
                    commit("SET_FLEETS", response.data.fleets);
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
