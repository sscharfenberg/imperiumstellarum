/******************************************************************************
 * Vuex actions
 *****************************************************************************/

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
        // TODO: Requesting?
        // TODO: commit resoures and storage upgrades
        window.axios
            .post(`/api/game/${getGameId()}/storage_upgrade`, payload)
            .then((response) => {
                if (response.status === 200) {
                    console.log(response.data);
                    commit("SET_RESOURCES", response.data.resources);
                    commit(
                        "SET_STORAGE_UPGRADES",
                        response.data.storageUpgrades
                    );
                }
            })
            .catch((e) => {
                console.error(e);
            })
            .finally(() => {
                //commit("SET_REQUESTING", false);
                console.log("done");
            });
    },
};
