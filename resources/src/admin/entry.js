/******************************************************************************
 *
 * "admin" entrypoint
 *
 *****************************************************************************/
require("./styles/main.scss");
import { initDatePicker } from "./modules/datepicker";
import { initSliders } from "./modules/slider";
import { initSeed } from "./modules/seed";

document.addEventListener("DOMContentLoaded", () => {
    if (document.querySelector("[data-datepicker]")) initDatePicker();
    if (document.querySelector("[data-slider]")) initSliders();
    if (document.querySelector("[data-seed-map]")) initSeed();
});
