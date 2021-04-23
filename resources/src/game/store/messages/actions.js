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
                    commit("SET_REPORTS", response.data.reports);
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
     * @function submit message
     * @param {Function} commit - Vuex commit
     * @param {Object} payload
     * @param {Array} payload.recipients
     * @param {String} payload.subject
     * @param {String} payload.body
     * @param {String} payload.repliesTo
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
                    commit("SET_PAGE", 2);
                }
                notify(response.data.message, "success");
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
     * @function mark message as "read" / "unread"
     * @param {Function} commit - Vuex commit
     * @param {Object} payload
     * @param {String} payload.messageId
     * @param {String} payload.read
     */
    MARK_MESSAGE_READ: function ({ commit }, payload) {
        commit("SET_REQUESTING", true);
        window.axios
            .post(`/api/game/${getGameId()}/messages/read`, payload)
            .then((response) => {
                if (response.status === 200 && response.data.inbox) {
                    commit("SET_INBOX", response.data.inbox);
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
     * @function submit a message report
     * @param commit
     * @param payload
     * @param {Array} payload.messageIds
     * @param {String} payload.mailbox
     * @constructor
     */
    DELETE_MESSAGES: function ({ commit }, payload) {
        commit("SET_REQUESTING", true);
        window.axios
            .post(`/api/game/${getGameId()}/messages/delete`, payload)
            .then((response) => {
                if (
                    response.status === 200 &&
                    response.data.inbox &&
                    response.data.outbox &&
                    response.data.message
                ) {
                    commit("SET_INBOX", response.data.inbox);
                    commit("SET_OUTBOX", response.data.outbox);
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

    REPORT_MESSAGE: function ({ commit }, payload) {
        commit("SET_REQUESTING", true);
        window.axios
            .post(`/api/game/${getGameId()}/messages/report`, payload)
            .then((response) => {
                if (
                    response.status === 200 &&
                    response.data.reports &&
                    response.data.message
                ) {
                    commit("SET_REPORTS", response.data.reports);
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
