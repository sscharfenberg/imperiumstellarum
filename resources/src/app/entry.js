import { initTableSort } from "../admin/modules/tablesort";

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
import { initTextToUpperCase } from "./modules/ticker";
import { initColourPicker } from "./modules/colour-picker";
import { initPerPage } from "../admin/modules/perpage";
import { initTrHref } from "../admin/modules/tr-href";

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
    if (document.querySelector("input[type=text].uppercase"))
        initTextToUpperCase();
    if (document.querySelector("[data-colour]")) initColourPicker();
    if (document.querySelector("input[name=sort]")) initTableSort();
    if (document.querySelector("[data-perpage]")) initPerPage();
    if (document.querySelector("[data-tr-href]")) initTrHref();
});
