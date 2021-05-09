/******************************************************************************
 * Vuex getters
 *****************************************************************************/

export default {
    /**
     * singletons
     */
    // get turn data by turn number
    getTurnByNumber: (state) => (turn) =>
        state.encounterDetails.turns.find((t) => t.turn === turn) || {},

    // get star by id
    starById: (state) => (id) =>
        state.stars.find((star) => star.id === id) || {},

    // get player by id
    playerById: (state) => (playerId) =>
        state.players.find((player) => player.id === playerId) || {},

    /**
     * multiples
     */

    // get encounters
    inbox: (state) => state.encounters || [],
};
