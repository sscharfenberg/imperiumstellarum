/******************************************************************************
 * Vuex module "fleets"
 * https://vuex.vuejs.org/guide/modules.html
 *****************************************************************************/
import state from "./state";
import mutations from "./mutations";
import actions from "./actions";
import getters from "./getters";

export default {
    namespaced: true,
    // module assets
    state,
    mutations,
    actions,
    getters,
};
