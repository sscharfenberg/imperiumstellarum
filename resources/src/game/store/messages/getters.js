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

    // get player by id
    playerById: (state) => (id) => state.players.find((p) => p.id === id) || {},

    /**
     * multiples
     */

    // get inbox messages
    inbox: (state) => state.inbox || [],

    // get outbox messages
    outbox: (state) => state.outbox || [],
};
