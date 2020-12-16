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
        console.log("save blueprint", payload);
        commit("SET_REQUESTING", true);
        //TODO: implement axios.post / mutations
    },
};
