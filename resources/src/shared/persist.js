/******************************************************************************
 *
 * persist settings
 *
 *****************************************************************************/
import pkg from "../../../package.json";
const storageKey = pkg.name.toUpperCase();

/*
 * the default state
 */
export const defaultState = {
    theme: "dark", // ["light", "dark"]
};

/*
 * find out if localStorage is available and works
 * (without throwing exceptions)
 * @returns {Boolean}
 */
export const storageAvailable = () => {
    let storage = window.localStorage;
    let x = "__storage_test__";
    try {
        storage.setItem(x, x);
        storage.removeItem(x);
        return true;
    } catch (e) {
        return (
            e instanceof DOMException &&
            // everything except Firefox
            (e.code === 22 ||
                // Firefox
                e.code === 1014 ||
                // test name field too, because code might not be present
                // everything except Firefox
                e.name === "QuotaExceededError" ||
                // Firefox
                e.name === "NS_ERROR_DOM_QUOTA_REACHED") &&
            // acknowledge QuotaExceededError only if there's something already stored
            storage.length !== 0
        );
    }
};

/*
 * get app state from localStorage
 * @returns {Object|Boolean}
 */
export const getPersistantAppState = () => {
    console.log("getting persistant state");
    const moduleState = JSON.parse(localStorage.getItem(storageKey));
    if (moduleState) return moduleState;
    return false;
};

/*
 * save state to local storage
 * @param {Object} state
 */
export const savePersistantAppState = (state) => {
    if (!storageAvailable()) return false;
    // construct state to be saved
    let stateToSave = {};
    Object.keys(defaultState).forEach((key) => {
        stateToSave[key] = state[key];
    });
    console.log("saving app state to storage:", stateToSave);
    return localStorage.setItem(storageKey, JSON.stringify(stateToSave));
};
