/******************************************************************************
 * Vuex store entrypoint
 *****************************************************************************/
import { createStore } from "vuex";
import mutations from "./mutations";
import actions from "./actions";
import getters from "./getters";
import { state } from "./state";

/*
 * create vuex store
 */
const store = createStore({
    mutations,
    actions,
    getters,
    state,
});

export default store;
