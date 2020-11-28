/******************************************************************************
 * Vuex module "empire" state
 *****************************************************************************/
import { getState } from "@/game/store/persistState";

const savedState = getState();

/*
 * get initial module state
 * @returns {Object}
 */
export default {
    requesting: false,
    stars: [],
    players: [],
    cameraX: savedState.cameraX || 0,
    cameraY: savedState.cameraY || 0,
    zoomLevel: savedState.zoomLevel || 2,
};
