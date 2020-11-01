/******************************************************************************
 * Vuex getters
 *****************************************************************************/

export default {
    // get star by id
    starById: (state) => (id) =>
        state.stars.find((star) => star.id === id) || {},

    // get planets belonging to a star
    planetsByStarId: (state) => (id) =>
        state.planets
            .filter((planet) => planet.starId === id)
            .sort((a, b) => {
                return a.orbitalIndex - b.orbitalIndex;
            }) || {},

    // is the starId expanded and showing planets?
    isStarExpanded: (state) => (id) => {
        return state.expandedStars.includes(id) || false;
    },

    // get name of star by id
    starNameById: (state) => (id) =>
        state.stars.find((star) => star.id === id).name || "",

    // get planet by id
    planetById: (state) => (id) =>
        state.planets.find((planet) => planet.id === id),
};
