/******************************************************************************
 * Vuex getters
 *****************************************************************************/

export default {
    /**
     * singletons
     */

    // get broadcast by id
    messageById: (state) => (id) => state.inbox.find((f) => f.id === id) || {},

    // get sent broadcast by id
    sentMessageById: (state) => (id) =>
        state.outbox.find((f) => f.id === id) || {},

    // get any message (inbox and outbox) by id
    anyMessageById: (state) => (id) =>
        [...state.inbox, ...state.outbox].find((m) => m.id === id),

    // get player by id
    playerById: (state) => (id) => state.players.find((p) => p.id === id) || {},

    // report for message
    messageReport: (state) => (id) =>
        state.reports.find((m) => m.messageId === id) || {},

    /**
     * multiples
     */

    // get inbox messages
    inbox: (state) => state.inbox || [],

    // get outbox messages
    outbox: (state) => state.outbox || [],
};
