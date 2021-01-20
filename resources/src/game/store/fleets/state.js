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
    // area meta data
    fleets: [],
    shipyards: [],
    ships: [],
    stars: [],
    players: [],
    maxFleets: 0,
    // create new fleet
    createLocation: "",
    createName: "",
    // transfer ships between fleets/shipyards
    transferSourceId: "",
    transferSourceShipIds: [],
    transferTargetId: "",
    transferTargetShipIds: [],
    transferSubmitActive: false,
    // move ships
    moveDestinationStarId: "",
    moveCoordX: "",
    moveCoordY: "",
    destinationStar: {},
    destinationOwner: {},
    availableDestinations: [],
    availableOwner: {},
};
