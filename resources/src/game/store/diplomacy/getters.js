/******************************************************************************
 * Vuex getters
 *****************************************************************************/

export default {
    /**
     * singletons
     */

    // playerRelationChange by recipient ID
    relationChangeByRecipientId: (state) => (id) =>
        state.relationChanges.find((rc) => rc.playerId === id),

    /**
     * multiples
     */
};
