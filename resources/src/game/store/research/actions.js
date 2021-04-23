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
                    commit("SET_TECH_LEVELS", response.data.techLevels);
                    commit("SET_RESEARCH_JOBS", response.data.researchJobs);
                    commit(
                        "SET_UNREAD_MESSAGES",
                        response.data.unreadMessages,
                        { root: true }
                    );
                    commit(
                        "SET_UNREAD_ENCOUNTERS",
                        response.data.unreadEncounters,
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
     * @function SET research priority
     * @param {Function} commit - Vuex commit
     * @param {Number} payload
     */
    ENQUEUE_TECHLEVEL: function ({ commit }, payload) {
        commit("SET_REQUESTING", true);
        window.axios
            .post(`/api/game/${getGameId()}/research/enqueue`, payload)
            .then((response) => {
                if (response.status === 200) {
                    commit("ADD_RESEARCH_JOB", response.data.researchJob);
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

    /**
     * @function CHANGE research job order
     * @param {Function} commit - Vuex commit
     * @param {Array} payload - Array of research job ids
     */
    CHANGE_RESEARCH_JOB_ORDER: function ({ commit }, payload) {
        commit("SET_REQUESTING", true);
        window.axios
            .post(`/api/game/${getGameId()}/research/order`, payload)
            .then((response) => {
                if (response.status === 200) {
                    commit("SET_RESEARCH_JOBS", response.data.researchJobs);
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
     * @function DELETE research job
     * @param {Function} commit - Vuex commit
     * @param {Object} payload
     * @param {String} payload.id - jobId
     */
    DELETE_RESEARCH_JOB: function ({ commit }, payload) {
        commit("SET_REQUESTING", true);
        window.axios
            .post(`/api/game/${getGameId()}/research/delete`, payload)
            .then((response) => {
                if (response.status === 200 && response.data.researchJobs) {
                    commit("SET_RESEARCH_JOBS", response.data.researchJobs);
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
