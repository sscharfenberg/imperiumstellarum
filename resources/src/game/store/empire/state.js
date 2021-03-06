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
    planets: [],
    collapsedStars: savedState.collapsedStars || [],
    editingStarId: "",
    changingStarId: "",
    starsSorted: savedState.starsSorted || [],
    harvesters: [],
    shipyards: [],
};
