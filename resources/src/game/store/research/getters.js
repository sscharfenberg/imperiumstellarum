/******************************************************************************
 * Vuex getters
 *****************************************************************************/

export default {
    // get and sort tech levels
    techLevelsSorted: (state) => {
        const preferedSortOrder = [];
        for (let area in window.rules.tech.areas) {
            preferedSortOrder.push(area);
        }
        return state.techLevels.sort((a, b) => {
            return (
                preferedSortOrder.indexOf(a.type) -
                preferedSortOrder.indexOf(b.type)
            );
        });
    },

    // get TechLevel by id
    techLevelById: (state) => (id) =>
        state.techLevels.find((tl) => tl.id === id) || {},

    // get research job by type
    researchJobByType: (state) => (type) =>
        state.researchJobs.filter((job) => job.type === type) || {},
};
