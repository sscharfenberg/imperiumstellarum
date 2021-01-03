/******************************************************************************
 * Vuex getters
 *****************************************************************************/

export default {
    // get blueprint by id
    fleetById: (state) => (id) =>
        state.blueprints.find((bp) => bp.id === id) || {},
};
