/******************************************************************************
 * Vuex module "base" state
 *****************************************************************************/

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
    empireResearchPriority: 1,
    resources: [],
    storageUpgrades: [],
};
