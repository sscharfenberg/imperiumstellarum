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
                    commit("SET_HARVESTERS", response.data.harvesters);
                    commit("SET_SHIPYARDS", response.data.shipyards);
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

    /**
     * @function POST set planet food consumption
     * @param {Function} commit - Vuex commit
     * @param {Object} payload
     * @param {String} payload.planetId
     * @param {Number} payload.foodConsumption
     * @constructor
     */
    SET_FOOD_CONSUMPTION: function ({ commit }, payload) {
        window.axios
            .post(`/api/game/${getGameId()}/empire/food_consumption`, payload)
            .then((response) => {
                if (response.status === 200) {
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

    /**
     * @function POST install harvester request
     * @param {Function} commit - Vuex commit
     * @param {Object} payload
     * @param {String} payload.planetId
     * @param {Number} payload.resourceType
     * @constructor
     */
    INSTALL_HARVESTER: function ({ commit }, payload) {
        window.axios
            .post(`/api/game/${getGameId()}/empire/harvester`, payload)
            .then((response) => {
                if (response.status === 200) {
                    commit("ADD_HARVESTER", response.data.harvester);
                    commit("SET_RESOURCES", response.data.resources, {
                        root: true,
                    });
                    notify(response.data.message, "success");
                }
            })
            .catch((e) => {
                console.error(e.response.data.error);
                notify(e.response.data.error, "error");
            });
    },

    /**
     * @function POST build shipyard request
     * @param {Function} commit - Vuex commit
     * @param {Object} payload
     * @param {String} payload.planetId
     * @param {Number} payload.type
     * @constructor
     */
    INSTALL_SHIPYARD: function ({ commit }, payload) {
        window.axios
            .post(`/api/game/${getGameId()}/empire/shipyard`, payload)
            .then((response) => {
                if (response.status === 200) {
                    commit("ADD_SHIPYARD", response.data.shipyard);
                    commit("SET_RESOURCES", response.data.resources, {
                        root: true,
                    });
                    if (response.data.message)
                        notify(response.data.message, "success");
                }
            })
            .catch((e) => {
                console.error(e.response.data.error);
                notify(e.response.data.error, "error");
            });
    },

    /**
     * @function POST build shipyard request
     * @param {Function} commit - Vuex commit
     * @param {Object} payload
     * @param {String} payload.planetId
     * @param {Number} payload.type
     * @constructor
     */
    UPGRADE_SHIPYARD: function ({ commit }, payload) {
        window.axios
            .post(`/api/game/${getGameId()}/empire/shipyard/upgrade`, payload)
            .then((response) => {
                if (response.status === 200) {
                    commit("MODIFY_SHIPYARD", response.data.shipyard);
                    commit("SET_RESOURCES", response.data.resources, {
                        root: true,
                    });
                    if (response.data.message)
                        notify(response.data.message, "success");
                }
            })
            .catch((e) => {
                console.error(e.response.data.error);
                notify(e.response.data.error, "error");
            });
    },
};
