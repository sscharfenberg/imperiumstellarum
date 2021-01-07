/******************************************************************************
 * Vuex module "fleets" state
 *****************************************************************************/
//import { getState } from "@/game/store/persistState";

//const savedState = getState();

/*
 * get initial module state
 * @returns {Object}
 */
export default {
    requesting: false,
    fleets: [],
    shipyards: [],
    stars: [],
    maxFleets: 0,
    create: {
        show: false,
        location: "",
        name: "",
    },
};
