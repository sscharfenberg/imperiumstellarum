/******************************************************************************
 *
 * submenu
 *
 *****************************************************************************/
// imports
import {
    defaultState,
    getPersistantAppState,
    savePersistantAppState,
} from "@/shared/persist";
// vars
const _toggles = document.querySelectorAll("[data-theme-toggle]");

/**
 * set current theme
 * @param {string} theme
 */
const setTheme = (theme) => {
    document.querySelector("html").dataset.theme = theme;
};

/**
 * get current theme
 * @returns {string} theme
 */
const getTheme = () => {
    return document.querySelector("html").dataset.theme;
};

const onToggle = (theme) => {
    console.log("setting theme to ", theme);
    let savedState = getPersistantAppState();
    for (let i = 0; i < _toggles.length; i++) {
        _toggles[i].checked = theme === "dark";
    }
    savedState.theme = theme;
    setTheme(theme);
    savePersistantAppState(savedState);
};

/**
 * @function initialize theme toggle
 */
export const initThemeToggle = () => {
    console.log("initializing theme");
    let savedState = getPersistantAppState();
    if (!savedState) {
        savePersistantAppState(defaultState);
    } else {
        setTheme(savedState.theme);
        // update checkboxes
        for (let i = 0; i < _toggles.length; i++) {
            _toggles[i].checked = savedState.theme === "dark";
        }
    }

    for (let i = 0; i < _toggles.length; i++) {
        _toggles[i].addEventListener("click", () => {
            onToggle(getTheme() === "dark" ? "light" : "dark");
        });
    }
};
