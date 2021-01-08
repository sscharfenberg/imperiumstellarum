/******************************************************************************
 * Vuex getters
 *****************************************************************************/

export default {
    // get blueprint by id
    fleetById: (state) => (id) => state.fleets.find((f) => f.id === id) || {},

    // get star by id
    starById: (state) => (id) =>
        state.stars.find((star) => star.id === id) || {},
};
