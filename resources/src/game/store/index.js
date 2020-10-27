/******************************************************************************
 * Vuex store entrypoint
 *****************************************************************************/
import { createStore } from "vuex";
import empire from "./empire";
import state from "./state";
import mutations from "./mutations";
import actions from "./actions";

/*
 * create vuex store
 */
const store = createStore({
    state,
    mutations,
    actions,
    modules: {
        empire,
    },
});

export default store;
