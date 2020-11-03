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
            .get(`/api/game/${getGameId()}/empire`)
            .then((response) => {
                if (response.status === 200) {
                    commit("SET_GAME_META_DATA", response.data, { root: true });
                    commit("SET_STARS", response.data.stars);
                    commit("SET_PLANETS", response.data.planets);
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
     * @function POST changed star name to api
     * @param {Function} commit - Vuex commit
     * @param {Object} payload
     * @param {String} payload.id
     * @param {String} payload.name
     * @constructor
     */
    CHANGE_STAR_NAME: function ({ commit }, payload) {
        commit("SET_CHANGING_STAR", payload.id);
        window.axios
            .post(`/api/game/${getGameId()}/empire/star_name`, payload)
            .then((response) => {
                if (response.status === 200) {
                    commit("CHANGE_STAR", response.data.star);
                }
            })
            .catch((e) => {
                console.error(e);
                notify(e.response.data.errors.name[0], "error");
            })
            .finally(() => {
                commit("SET_CHANGING_STAR", "");
            });
    },

    SET_FOOD_CONSUMPTION: function ({ commit }, payload) {
        console.log("change consumption", payload);
        window.axios
            .post(`/api/game/${getGameId()}/empire/food_consumption`, payload)
            .then((response) => {
                if (response.status === 200) {
                    console.log(response.data);
                    commit("SET_FOOD_CONSUMPTION", {
                        planetId: payload.planetId,
                        foodConsumption: payload.foodConsumption,
                    });
                }
            })
            .catch((e) => {
                console.error(e.response.data.error);
                notify(e.response.data.error, "error");
            });
    },
};
