/******************************************************************************
 *
 * "app" entrypoint
 *
 *****************************************************************************/
require("../bootstrap");
require("@/app/styles/main.scss");
import { initSubmenus } from "@/app/modules/submenu";
import { initThemeToggle } from "@/app/modules/theme";
import { initDrawer } from "@/app/modules/drawer";
import { initLocalTime } from "@/app/modules/localtime";
import { initSpectral } from "@/app/modules/spectral";
import { initFlash } from "@/app/modules/flash";
import { initPasswordStrength } from "@/app/modules/password-strength";
import { initSubmitButton } from "@/app/modules/button";
import { initModal } from "@/app/modules/modal";

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
