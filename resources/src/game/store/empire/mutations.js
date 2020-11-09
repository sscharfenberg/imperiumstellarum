/******************************************************************************
 * Vuex mutations
 *****************************************************************************/
import { saveState } from "@/game/store/persistState";

export default {
    /**
     * @function SET/UNSET "requesting"
     * @param {Object} state - vuex module "empire" state
     * @param {Boolean} payload
     */
    SET_REQUESTING: (state, payload) => {
        state.requesting = payload;
    },

    /**
     * @function SET PLAYER STARS
     * @param {Object} state - vuex module "empire" state
     * @param {Array} payload
     */
    SET_STARS: (state, payload) => {
        state.stars = payload;
    },

    /**
     * @function SET PLAYER PLANETS
     * @param {Object} state - vuex module "empire" state
     * @param {Array} payload
     */
    SET_PLANETS: (state, payload) => {
        state.planets = payload;
    },

    /**
     * @function TOGGLE STAR EXPANSION
     * @param {Object} state - vuex module "empire" state
     * @param {Object} payload
     * @param {String} payload.id - id of star
     * @param {Boolean} payload.expand
     * @constructor
     */
    TOGGLE_STAR_EXPANDED: (state, payload) => {
        if (payload.expand) {
            state.expandedStars.push(payload.id);
        } else {
            state.expandedStars.splice(
                state.expandedStars.indexOf(payload.id),
                1
            );
        }
        saveState(state.expandedStars, "expandedStars");
    },

    /**
     * @function SET STAR EDITING
     * @param {Object} state - vuex module "empire" state
     * @param {String} payload - id of star
     * @constructor
     */
    SET_EDITING_STAR: (state, payload) => {
        state.editingStarId = payload;
    },

    /**
     * @function SET STAR CHANGING
     * @param {Object} state - vuex module "empire" state
     * @param {String} payload - id of star
     * @constructor
     */
    SET_CHANGING_STAR: (state, payload) => {
        state.changingStarId = payload;
    },

    /**
     * @function UPDATE STAR
     * @param {Object} state - vuex module "empire" state
     * @param {Object} payload - star
     * @constructor
     */
    CHANGE_STAR: (state, payload) => {
        Object.assign(
            state.stars.find((star) => star.id === payload.id),
            payload
        );
    },

    /**
     * @function SET STARS ORDER
     * @param {Object} state - vuex module "empire" state
     * @param {Array} payload - array of starIds
     * @constructor
     */
    SET_STARS_ORDER: (state, payload) => {
        state.starsSorted = payload;
        saveState(state.starsSorted, "starsSorted");
    },

    /**
     * @function SET FOOD CONSUMPTION OF PLANET
     * @param {Object} state - vuex module "empire" state
     * @param {Object} payload
     * @param {String} payload.planetId
     * @param {Number} payload.foodConsumption
     * @constructor
     */
    SET_FOOD_CONSUMPTION: (state, payload) => {
        state.planets.find(
            (planet) => planet.id === payload.planetId
        ).foodConsumption = payload.foodConsumption;
    },

    /**
     * @function SET HARVESTERS
     * @param {Object} state - vuex module "empire" state
     * @param {Array} payload
     */
    SET_HARVESTERS: (state, payload) => {
        state.harvesters = payload;
    },

    /**
     * @function ADD HARVESTER
     * @param {Object} state - vuex module "empire" state
     * @param {Object} payload - the new harvester from server
     * @constructor
     */
    ADD_HARVESTER: (state, payload) => {
        state.harvesters.push(payload);
    },

    /**
     * @function SET SHIPYARDS
     * @param {Object} state - vuex module "empire" state
     * @param {Array} payload
     */
    SET_SHIPYARDS: (state, payload) => {
        state.shipyards = payload;
    },

    /**
     * @function ADD SHIPYARD
     * @param {Object} state - vuex module "empire" state
     * @param {Object} payload - the new harvester from server
     * @constructor
     */
    ADD_SHIPYARD: (state, payload) => {
        state.shipyards.push(payload);
    },

    /**
     * @function MODIFY SHIPYARD
     * @param {Object} state - vuex module "empire" state
     * @param {Object} payload - the new harvester from server
     * @constructor
     */
    MODIFY_SHIPYARD: (state, payload) => {
        Object.assign(
            state.shipyards.find((shipyard) => shipyard.id === payload.id),
            payload
        );
    },
};
