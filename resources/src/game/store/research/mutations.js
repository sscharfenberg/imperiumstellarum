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
        state.researchJobs = payload;
    },
};
