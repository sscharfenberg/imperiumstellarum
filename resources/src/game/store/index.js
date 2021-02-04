/******************************************************************************
 * Vuex store entrypoint
 *****************************************************************************/
import { createStore } from "vuex";
import empire from "./empire";
import research from "./research";
import starchart from "./starchart";
import shipyards from "./shipyards";
import fleets from "./fleets";
import diplomacy from "./diplomacy";
import messages from "./messages";
import state from "./state";
import mutations from "./mutations";
import actions from "./actions";
import getters from "./getters";

/*
 * create vuex store
 */
const store = createStore({
    state,
    mutations,
    actions,
    getters,
    modules: {
        empire,
        research,
        starchart,
        shipyards,
        fleets,
        diplomacy,
        messages,
    },
});

export default store;
