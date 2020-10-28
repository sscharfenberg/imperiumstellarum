/******************************************************************************
 * Vuex store entrypoint
 *****************************************************************************/
import { createStore } from "vuex";
import empire from "./empire";
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
    },
});

export default store;
