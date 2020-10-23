/******************************************************************************
 *
 * "game" entrypoint
 *
 *****************************************************************************/
require("../bootstrap");
import { createApp } from "vue";
import { i18n } from "./i18n";
import router from "./router";
import store from "./store";
import App from "./App.vue";

console.log("game entry");

/*
 * mount VueJs app
 */
createApp(App).use(store).use(i18n).use(router).mount("#game");
