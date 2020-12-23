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
     * @function SET maximum number of blueprints
     * @param {Object} state - vuex module "shipyards" state
     * @param {Number} payload
     * @constructor
     */
    SET_BP_MAX: (state, payload) => {
        state.bpMax = payload;
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

    /**************************************************************************
     * DESIGN BLUEPRINTS MUTATIONS
     *************************************************************************/

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

    /**************************************************************************
     * MANAGE BLUEPRINT mutations
     *************************************************************************/

    /**
     * @function SET MANAGE BLUEPRINT preview
     * @param {Object} state - vuex module "shipyards" state
     * @param {Object} payload
     * @param {String} payload.hullType
     * @param {String} payload.className
     * @param {Array} payload.modules
     * @param {Array} payload.techLevels
     * @constructor
     */
    SET_MANAGE_BLUEPRINT_PREVIEW: (state, payload) => {
        state.preview.id = payload.id;
        state.preview.hullType = payload.hullType;
        state.preview.className = payload.className;
        state.preview.modules = payload.modules;
        state.preview.techLevels = payload.techLevels;
    },

    /**
     * @function SET MANAGE BLUEPRINT preview
     * @param {Object} state - vuex module "shipyards" state
     * @constructor
     */
    RESET_MANAGE_BLUEPRINT_PREVIEW: (state) => {
        state.preview.id = "";
        state.preview.hullType = "";
        state.preview.className = "";
        state.preview.modules = [];
        state.preview.techLevels = [];
    },

    /**
     * @function set the blueprint id that is being renamed
     * @param state
     * @param payload
     * @constructor
     */
    SET_BLUEPRINT_RENAMING: (state, payload) => {
        state.changingBpName = payload;
    },
};
