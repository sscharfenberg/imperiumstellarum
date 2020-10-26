/******************************************************************************
 * Vuex store entrypoint
 *****************************************************************************/
import { createStore } from "vuex";
import empire from "./empire";
import state from "./state";
import mutations from "./mutations";

/*
 * create vuex store
 */
const store = createStore({
    state,
    mutations,
    modules: {
        empire,
    },
});

export default store;