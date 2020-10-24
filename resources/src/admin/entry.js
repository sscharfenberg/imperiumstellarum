/******************************************************************************
 *
 * "admin" entrypoint
 *
 *****************************************************************************/
require("./styles/main.scss");
import { initTableSort } from "./modules/tablesort";
import { initPerPage } from "./modules/perpage";
import { initTrHref } from "./modules/tr-href";
import { initDatePicker } from "./modules/datepicker";
import { initSliders } from "./modules/slider";
import { initSeed } from "./modules/seed";

document.addEventListener("DOMContentLoaded", () => {
    if (document.querySelector("input[name=sort]")) initTableSort();
    if (document.querySelector("[data-perpage]")) initPerPage();
    if (document.querySelector("[data-tr-href]")) initTrHref();
    if (document.querySelector("[data-datepicker]")) initDatePicker();
    if (document.querySelector("[data-slider]")) initSliders();
    if (document.querySelector("[data-seed-map]")) initSeed();
});
