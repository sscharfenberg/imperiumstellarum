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

    // get sorted research jobs in queue order
    researchJobsOrdered: (state) =>
        state.researchJobs.sort((a, b) => a.order - b.order),

    // get research job by type
    researchJobsByType: (state) => (type) =>
        state.researchJobs.filter((job) => job.type === type) || [],

    // get research job by id
    researchJobById: (state) => (id) =>
        state.researchJobs.find((job) => job.id === id) || {},
};
