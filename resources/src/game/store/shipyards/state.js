/******************************************************************************
 * Vuex module "starchart" state
 *****************************************************************************/
import { getState } from "@/game/store/persistState";

const savedState = getState();

/*
 * get initial module state
 * @returns {Object}
 */
export default {
    requesting: false,
    shipyards: [],
    page: savedState.shipyardPage || 0,
    techLevels: [],
    blueprints: [],
    bpMax: 0,
    design: {
        hullType: "",
        className: "",
        modules: [],
    },
    preview: {
        id: "",
        hullType: "",
        className: "",
        modules: [],
        techLevels: [],
    },
    changingBpName: "",
    newContract: {
        shipyard: "",
        blueprint: "",
    },
};
