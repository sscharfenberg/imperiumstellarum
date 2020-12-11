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
};
