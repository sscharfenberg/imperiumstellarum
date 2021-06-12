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
                    commit("SET_RAIDS", response.data.raids);
                    commit("SET_PLAYERS", response.data.players);
                    commit("SET_STARS", response.data.stars);
                    commit("SET_RELATIONS", response.data.relations);
                    commit(
                        "SET_UNREAD_MESSAGES",
                        response.data.unreadMessages,
                        { root: true }
                    );
                }
            })
            .catch((e) => {
                console.error(e);
                if (e.response.data.error)
                    notify(e.response.data.error, "error");
                else if (e.response.data.message)
                    notify(e.response.data.message, "error");
            })
            .finally(() => {
                commit("SET_REQUESTING", false);
            });
    },

    /**
     * @function GET encounter details
     * @param {Function} commit - Vuex commit
     * @param {Function} dispatch - Vuex dispatch
     * @param {String} payload - encounterId
     */
    GET_ENCOUNTER_DETAILS: function ({ commit, dispatch }, payload) {
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
                    commit("SET_STARS", response.data.stars);
                    commit("SET_RELATIONS", response.data.relations);
                    commit("SET_TURN", 0);
                    if (!response.data.encounterDetails.read) {
                        dispatch("SET_ENCOUNTER_READ", {
                            encounterId: payload,
                            status: true,
                        });
                    }
                }
            })
            .catch((e) => {
                console.error(e);
                if (e.response.data.error)
                    notify(e.response.data.error, "error");
                else if (e.response.data.message)
                    notify(e.response.data.message, "error");
            })
            .finally(() => {
                commit("SET_REQUESTING", false);
            });
    },

    /**
     * @function SET READ for an encounter
     * @param {Function} commit - Vuiex commit
     * @param {Object} payload
     * @param {String} payload.encounterId
     * @param {Boolean} payload.status
     * @constructor
     */
    SET_ENCOUNTER_READ: function ({ commit }, payload) {
        commit("SET_REQUESTING", true);
        window.axios
            .post(
                `/api/game/${getGameId()}/encounters/${
                    payload.encounterId
                }/read`,
                {
                    status: payload.status,
                }
            )
            .then((response) => {
                if (response.status === 200 && payload.status) {
                    commit("SET_ENCOUNTERS", response.data.encounters);
                }
                if (response.status === 200 && !payload.status) {
                    commit("SET_ENCOUNTERS", response.data.encounters);
                    notify(response.data.message, "success");
                }
            })
            .catch((e) => {
                console.error(e);
                if (e.response.data.error)
                    notify(e.response.data.error, "error");
                else if (e.response.data.message)
                    notify(e.response.data.message, "error");
            })
            .finally(() => {
                commit("SET_REQUESTING", false);
            });
    },
};
