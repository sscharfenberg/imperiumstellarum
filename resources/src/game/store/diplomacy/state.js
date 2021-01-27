/******************************************************************************
 * Vuex module "diplomacy" state
 *****************************************************************************/
import { getState } from "@/game/store/persistState";

const savedState = getState();

console.log("saved state", savedState);

/*
 * get initial module state
 * @returns {Object}
 */
export default {
    requesting: false,
    // area meta data
    players: [],
    relations: [],
    showAllies: savedState.diplomacyShowAllies,
    showNeutrals: savedState.diplomacyShowNeutrals,
    showHostiles: savedState.diplomacyShowHostiles,
    filterByTicker: "",
};
