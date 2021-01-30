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
    stars: [],
    starsShown: [],
    playerStars: [],
    relations: [],
    players: [],
    fleets: [],
    fleetMovements: [],
    ships: [],
    cameraX: savedState.cameraX || 0,
    cameraY: savedState.cameraY || 0,
    zoomLevel: savedState.zoomLevel,
    flashCoordX: null,
    flashCoordY: null,
    jumpCoordX: null,
    jumpCoordY: null,
    starMoveFleetHereId: "",
};
