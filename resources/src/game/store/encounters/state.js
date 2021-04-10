/******************************************************************************
 * Vuex module "messages" state
 *****************************************************************************/
//import { getState } from "@/game/store/persistState";
//const savedState = getState();

/*
 * get initial module state
 * @returns {Object}
 */
export default {
    requesting: false,
    // area meta data
    encounters: [],
    encounterDetails: {},
    players: [],
    renderTurn: 0,
    playing: false,
};