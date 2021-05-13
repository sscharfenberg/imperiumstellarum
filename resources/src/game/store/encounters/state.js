/******************************************************************************
 * Vuex module "messages" state
 *****************************************************************************/
import { getState } from "@/game/store/persistState";
const savedState = getState();

/*
 * get initial module state
 * @returns {Object}
 */
export default {
    requesting: false,
    encounters: [],
    encounterDetails: {},
    players: [],
    stars: [],
    relations: [],
    // encounters list
    perPage: savedState.encountersPerPage || 10,
    // encounter details
    renderTurn: 0,
    playing: false,
};
