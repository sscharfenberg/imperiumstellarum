/******************************************************************************
 * Vuex module "base" state
 *****************************************************************************/
import { getState } from "@/game/store/persistState";

const savedState = getState();

/*
 * get initial module state
 * @returns {Object}
 */
export default {
    gameNumber: 0,
    gameTurn: 0,
    turnDue: 0,
    empireName: "",
    empireTicker: "",
    empireResearchPriority: 0,
    empireId: "",
    colour: "",
    resources: [],
    storageUpgrades: [],
    collapsibleExpandedIds: savedState.collapsibleExpandedIds || [],
};
