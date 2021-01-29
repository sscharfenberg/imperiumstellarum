/******************************************************************************
 *
 * "game" entrypoint
 *
 *****************************************************************************/
import { createApp } from "vue";
import { i18n } from "./i18n";
import router from "./router";
import store from "./store";
import App from "./App.vue";

console.log("bootstrapping game");
/*
 * mount VueJs app
 */
const app = createApp(App).use(store).use(i18n).use(router);
app.mount("#game");
