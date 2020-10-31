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
     * @param {Object} payload - id of star
     * @constructor
     */
    SET_EDITING_STAR: (state, payload) => {
        state.editingStarId = payload;
    },

    /**
     * @function SET STAR CHANGING
     * @param {Object} state - vuex module "empire" state
     * @param {Object} payload - id of star
     * @constructor
     */
    SET_CHANGING_STAR: (state, payload) => {
        state.changingStarId = payload;
    },
};
