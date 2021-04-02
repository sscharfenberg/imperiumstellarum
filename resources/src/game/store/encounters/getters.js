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

    /**
     * multiples
     */
};
