/******************************************************************************
 * Vuex mutations
 *****************************************************************************/
import { saveState } from "@/game/store/persistState";

export default {
    /**
     * @function SET/UNSET "requesting"
     * @param {Object} state - vuex module "shipyards" state
     * @param {Boolean} payload
     */
    SET_REQUESTING: (state, payload) => {
        state.requesting = payload;
    },

    /**
     * @functon SET shipyards
     * @param {Object} state - vuex module "shipyards" state
     * @param {Array} payload
     * @constructor
     */
    SET_SHIPYARDS: (state, payload) => {
        state.shipyards = payload;
    },

    /**
     * @functon SET techLevels
     * @param {Object} state - vuex module "shipyards" state
     * @param {Array} payload
     * @constructor
     */
    SET_TECHLEVELS: (state, payload) => {
        state.techLevels = payload;
    },

    /**
     * @function SET all blueprints
     * @param {Object} state - vuex module "shipyards" state
     * @param {Array} payload
     * @constructor
     */
    SET_BLUEPRINTS: (state, payload) => {
        state.blueprints = payload;
    },

    /**
     * @function SET shipyard page
     * @param {Object} state - vuex module "shipyards" state
     * @param {Number} payload
     * @constructor
     */
    SET_PAGE: (state, payload) => {
        state.page = payload;
        saveState(state.page, "shipyardPage");
    },

    /**
     * DESIGN BLUEPRINTS MUTATIONS
     */

    /**
     * @function SET DESIGN hulltype
     * @param {Object} state - vuex module "shipyards" state
     * @param {String} payload
     * @constructor
     */
    SET_DESIGN_HULLTYPE: (state, payload) => {
        state.design.hullType = payload;
    },

    /**
     * @function SET DESIGN hulltype
     * @param {Object} state - vuex module "shipyards" state
     * @param {String} payload
     * @constructor
     */
    SET_DESIGN_CLASSNAME: (state, payload) => {
        state.design.className = payload;
    },

    /**
     * @function ADD module during design
     * @param {Object} state - vuex module "shipyards" state
     * @param {String} payload
     * @constructor
     */
    ADD_MODULE: (state, payload) => {
        state.design.modules.push(payload);
    },

    /**
     * @function REMOVE module during design
     * @param {Object} state - vuex module "shipyards" state
     * @param {String} payload
     * @constructor
     */
    REMOVE_MODULE: (state, payload) => {
        state.design.modules.splice(state.design.modules.indexOf(payload), 1);
    },

    /**
     * @function REMOVE module during design
     * @param {Object} state - vuex module "shipyards" state
     * @param {Number} payload
     * @constructor
     */
    TRUNCATE_MODULES: (state, payload) => {
        state.design.modules.length = payload;
    },

    /**
     * @function ADD one blueprint
     * @param {Object} state - vuex module "shipyards" state
     * @param {Object} payload
     * @constructor
     */
    ADD_BLUEPRINT: (state, payload) => {
        state.blueprints.push(payload);
    },

    /**
     * @function ADD one blueprint
     * @param {Object} state - vuex module "shipyards" state
     * @constructor
     */
    RESET_DESIGN: (state) => {
        state.design.hullType = "";
        state.design.className = "";
        state.design.modules = [];
    },
};
