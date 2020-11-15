/******************************************************************************
 * Vuex mutations
 *****************************************************************************/
//import { saveState } from "@/game/store/persistState";

export default {
    /**
     * @function SET/UNSET "requesting"
     * @param {Object} state - vuex module "research" state
     * @param {Boolean} payload
     */
    SET_REQUESTING: (state, payload) => {
        state.requesting = payload;
    },

    /**
     * @function SET PLAYER TOTAL POPULATION
     * @param {Object} state - vuex module "research" state
     * @param {Number} payload
     */
    SET_POPULATION: (state, payload) => {
        state.totalPopulation = payload;
    },

    /**
     * @function SET PLAYER TECH LEVELS
     * @param {Object} state - vuex module "research" state
     * @param {Number} payload
     */
    SET_TECH_LEVELS: (state, payload) => {
        state.techLevels = payload;
    },

    /**
     * @function SET PLAYER RESEARCH JOBS
     * @param {Object} state - vuex module "research" state
     * @param {Number} payload
     */
    SET_RESEARCH_JOBS: (state, payload) => {
        console.log("setting research jobs to", payload);
        state.researchJobs = payload;
    },

    /**
     * @function ADD PLAYER RESEARCH JOB
     * @param {Object} state - vuex module "research" state
     * @param {Number} payload
     */
    ADD_RESEARCH_JOB: (state, payload) => {
        state.researchJobs.push(payload);
    },
};
