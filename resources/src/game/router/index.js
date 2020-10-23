/******************************************************************************
 * Router setup
 *****************************************************************************/
import { createRouter, createWebHistory } from "vue-router";
import routes from "./routes";

const gameId = document.getElementById("game").dataset.gameId;

const router = createRouter({
    history: createWebHistory(`/game/${gameId}`),
    routes,
});

/*
 * route navigation guard
 * https://next.router.vuejs.org/guide/advanced/navigation-guards.html
 */
//router.beforeEach((to, from, next) => {
//    if (!to.matched.length) {
//        console.error(`no route found for path ${to.fullPath}`, to);
//        next("Empire");
//    } else {
//        console.log(`routing from '${from.fullPath}' to '${to.fullPath}'`);
//        next();
//    }
//});

export default router;
