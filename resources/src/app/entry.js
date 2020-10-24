/******************************************************************************
 *
 * "app" entrypoint
 *
 *****************************************************************************/
require("../bootstrap");
require("./styles/main.scss");
import { initSubmenus } from "./modules/submenu";
import { initThemeToggle } from "./modules/theme";
import { initDrawer } from "./modules/drawer";
import { initLocalTime } from "./modules/localtime";
import { initSpectral } from "./modules/spectral";
import { initFlash } from "./modules/flash";
import { initPasswordStrength } from "./modules/password-strength";
import { initSubmitButton } from "./modules/button";
import { initModal } from "./modules/modal";

document.addEventListener("DOMContentLoaded", () => {
    initSubmenus();
    initThemeToggle();
    if (document.querySelector(".drawer")) {
        initDrawer();
        initLocalTime();
    }
    if (document.querySelector("[data-spectral]")) initSpectral();
    if (document.querySelector(".flash")) initFlash();
    if (document.querySelector(".password-strength")) initPasswordStrength();
    if (document.querySelector(".app-btn--submit")) initSubmitButton();
    if (document.querySelector("button[data-modal]")) initModal();
});
