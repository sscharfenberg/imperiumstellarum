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
            .get(`/api/game/${getGameId()}/messages`)
            .then((response) => {
                if (response.status === 200) {
                    commit("SET_GAME_META_DATA", response.data, { root: true });
                    commit("SET_INBOX", response.data.inbox);
                    commit("SET_OUTBOX", response.data.outbox);
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
     * @function submit message
     * @param {Function} commit - Vuex commit
     * @param {Object} payload
     */
    SEND_MESSAGE: function ({ commit }, payload) {
        commit("SET_REQUESTING", true);
        window.axios
            .post(`/api/game/${getGameId()}/messages/send`, payload)
            .then((response) => {
                if (
                    response.status === 200 &&
                    response.data.outbox &&
                    response.data.message
                ) {
                    commit("SET_OUTBOX", response.data.outbox);
                    commit("SET_SEARCH_TICKER", "");
                    commit("RESET_RECIPIENTS");
                    commit("SET_SUBJECT", "");
                    commit("SET_BODY", "");
                    commit("SET_PAGE", 1);
                }
                notify(response.data.message, "success");
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
     * @function mark message as "read"
     * @param {Function} commit - Vuex commit
     * @param {Object} payload
     */
    MARK_MESSAGE_READ: function ({ commit }, payload) {
        commit("SET_REQUESTING", true);
        window.axios
            .post(`/api/game/${getGameId()}/messages/read`, payload)
            .then((response) => {
                if (response.status === 200 && response.data.inbox) {
                    commit("SET_INBOX", response.data.inbox);
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
