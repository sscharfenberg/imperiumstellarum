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
     * @function order a storage upgrade
     * @param commit
     * @param {Object} payload
     * @param {String} payload.type
     * @param {Number} payload.level
     * @constructor
     */
    INSTALL_STORAGE_UPGRADE: function ({ commit }, payload) {
        commit("empire/SET_REQUESTING", true);
        window.axios
            .post(`/api/game/${getGameId()}/storage_upgrade`, payload)
            .then((response) => {
                if (response.status === 200) {
                    commit("SET_RESOURCES", response.data.resources);
                    commit(
                        "SET_STORAGE_UPGRADES",
                        response.data.storageUpgrades
                    );
                    notify(response.data.message, "success");
                }
            })
            .catch((e) => {
                console.error(e);
                notify(e.response.data.error, "error");
            })
            .finally(() => {
                commit("empire/SET_REQUESTING", false);
                console.log("done");
            });
    },
};
