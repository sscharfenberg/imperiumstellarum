/******************************************************************************
 * Vuex getters
 *****************************************************************************/

export default {
    // get blueprint by id
    blueprintById: (state) => (id) =>
        state.blueprints.find((bp) => bp.id === id) || {},
};
