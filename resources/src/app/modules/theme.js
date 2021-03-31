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
 * @function toggle theme, initiated by user action (changing of radio buttons)
 * @param {String} theme
 */
const onToggle = (theme) => {
    let savedState = getPersistantAppState();
    // get radio buttons with correct values
    const _radios = document.querySelectorAll(
        "[data-theme-toggle][value=" + theme + "]"
    );
    // set radio buttons to checked
    for (let _radio of _radios) {
        _radio.checked = true;
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
        // get radio buttons with correct values
        const _radios = document.querySelectorAll(
            "[data-theme-toggle][value=" + savedState.theme + "]"
        );
        // set radio buttons to checked
        for (let _radio of _radios) {
            _radio.checked = true;
        }
    }
    for (let i = 0; i < _toggles.length; i++) {
        _toggles[i].addEventListener("change", (ev) => {
            onToggle(ev.target.value);
        });
    }
};
