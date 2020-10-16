/******************************************************************************
 *
 * "admin" entrypoint
 *
 *****************************************************************************/
require("../bootstrap");
require("@/admin/styles/main.scss");
import { initTableSort } from "@/admin/modules/tablesort";
import { initPerPage } from "@/admin/modules/perpage";
import { initTrHref } from "@/admin/modules/tr-href";
import { initDatePicker } from "@/admin/modules/datepicker";
import { initSliders } from "@/admin/modules/slider";
import { initSeed } from "@/admin/modules/seed";

document.addEventListener("DOMContentLoaded", () => {
    if (document.querySelector("input[name=sort]")) initTableSort();
    if (document.querySelector("[data-perpage]")) initPerPage();
    if (document.querySelector("[data-tr-href]")) initTrHref();
    if (document.querySelector("[data-datepicker]")) initDatePicker();
    if (document.querySelector("[data-slider]")) initSliders();
    if (document.querySelector("[data-seed-map]")) initSeed();
});
