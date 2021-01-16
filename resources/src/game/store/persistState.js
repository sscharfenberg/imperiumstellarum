/******************************************************************************
 *
 * persist settings
 *
 *****************************************************************************/
import pkg from "../../../../package.json";
const gameNumber = document.getElementById("game").dataset.gameNumber;
const storageKey = pkg.name.toUpperCase() + "_GAME" + gameNumber;
import { storageAvailable } from "@/shared/persist";

const defaultState = {
    collapsedStars: [],
    starsSorted: [],
    cameraX: 0,
    cameraY: 0,
    zoomLevel: 3,
    shipyardPage: 0,
    collapsibleExpandedIds: [],
};

/**
 * @function get state from localStorage
 * @returns {Object|boolean}
 */
export const getState = () => {
    if (!storageAvailable()) return false;
    const state = JSON.parse(localStorage.getItem(storageKey));
    return state ? state : defaultState;
};

export const saveState = (state, property) => {
    if (!storageAvailable()) return false;
    let stateToSave = getState();
    stateToSave[property] = state;
    console.log("saving app state to storage:", stateToSave);
    return localStorage.setItem(storageKey, JSON.stringify(stateToSave));
};
