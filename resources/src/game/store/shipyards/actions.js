/******************************************************************************
 * Vuex actions
 *****************************************************************************/
import { notify } from "@/shared/notification";

/**
 * @function get gameId from #game
 * @returns {string}
 */
const getGameId = () => document.getElementById("game").dataset.gameId;

export default {
    /**
     * @function GET game data from api
     * @param {Function} commit - Vuex commit
     */
    GET_GAME_DATA: function ({ commit }) {
        commit("SET_REQUESTING", true);
        window.axios
            .get(`/api/game/${getGameId()}/shipyards`)
            .then((response) => {
                if (response.status === 200) {
                    commit("SET_GAME_META_DATA", response.data, { root: true });
                    commit("SET_SHIPYARDS", response.data.shipyards);
                    commit("SET_TECHLEVELS", response.data.techLevels);
                    commit("SET_BLUEPRINTS", response.data.blueprints);
                    commit("SET_BP_MAX", response.data.numMaxBlueprints);
                }
            })
            .catch((e) => {
                console.error(e);
                notify(e.response.data.error, "error");
            })
            .finally(() => {
                commit("SET_REQUESTING", false);
            });
    },

    /**
     * @function save blueprint
     * @param {Function} commit - Vuex commit fn
     * @param {Object} payload
     * @param {String} payload.hullType
     * @param {Array} payload.modules
     * @param {String} payload.name
     */
    SAVE_BLUEPRINT: function ({ commit }, payload) {
        commit("SET_REQUESTING", true);
        window.axios
            .post(`/api/game/${getGameId()}/shipyards/blueprint`, payload)
            .then((response) => {
                if (response.status === 200 && response.data.blueprint) {
                    commit("ADD_BLUEPRINT", response.data.blueprint);
                    commit("SET_RESOURCES", response.data.resources, {
                        root: true,
                    });
                    commit("RESET_DESIGN");
                    notify(response.data.message, "success");
                }
            })
            .catch((e) => {
                console.error(e);
                notify(e.response.data.error, "error");
            })
            .finally(() => {
                commit("SET_REQUESTING", false);
            });
    },

    /**
     * @function delete blueprint
     * @param {Function} commit - Vuex commit fn
     * @param {Proxy} state - Vuex state
     * @param {Object} payload
     * @param {String} payload.id - blueprint id
     */
    DELETE_BLUEPRINT: function ({ commit, state }, payload) {
        console.log("delete blueprint", payload);
        commit("SET_REQUESTING", true);
        window.axios
            .post(
                `/api/game/${getGameId()}/shipyards/blueprint/delete`,
                payload
            )
            .then((response) => {
                if (
                    response.status === 200 &&
                    response.data.blueprints &&
                    response.data.message
                ) {
                    commit("SET_BLUEPRINTS", response.data.blueprints);
                    if (state.preview.id === payload.id) {
                        commit("RESET_MANAGE_BLUEPRINT_PREVIEW");
                    }
                    notify(response.data.message, "success");
                }
            })
            .catch((e) => {
                console.error(e);
                notify(e.response.data.error, "error");
            })
            .finally(() => {
                commit("SET_REQUESTING", false);
            });
    },

    /**
     * @function change blueprint name
     * @param commit
     * @param {Function} commit - Vuex commit fn
     * @param {Object} payload
     * @param {String} payload.id
     * @param {String} payload.name
     * @constructor
     */
    CHANGE_BLUEPRINT_NAME: function ({ commit }, payload) {
        commit("SET_REQUESTING", true);
        commit("SET_BLUEPRINT_RENAMING", payload.id);
        window.axios
            .post(
                `/api/game/${getGameId()}/shipyards/blueprint/rename`,
                payload
            )
            .then((response) => {
                if (
                    response.status === 200 &&
                    response.data.blueprints &&
                    response.data.message
                ) {
                    commit("SET_BLUEPRINTS", response.data.blueprints);
                    notify(response.data.message, "success");
                }
            })
            .catch((e) => {
                console.error(e);
                notify(e.response.data.error, "error");
            })
            .finally(() => {
                commit("SET_REQUESTING", false);
                commit("SET_BLUEPRINT_RENAMING", "");
            });
    },
};
