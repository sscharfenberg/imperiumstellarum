/******************************************************************************
 *
 * persist settings
 *
 *****************************************************************************/
import pkg from "../../../../package.json";
const storageKey = pkg.name.toUpperCase() + "_GAME";
import { storageAvailable } from "@/shared/persist";

const defaultState = {
    expandedStars: [],
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
