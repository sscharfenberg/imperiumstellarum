/******************************************************************************
 * Vuex getters
 *****************************************************************************/

export default {
    // get blueprint by id
    blueprintById: (state) => (id) =>
        state.blueprints.find((bp) => bp.id === id) || {},

    // get construction contract by id
    contractById: (state) => (id) =>
        state.constructionContracts.find((c) => c.id === id) || {},
};
