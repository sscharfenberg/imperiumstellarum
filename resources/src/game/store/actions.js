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
                    //commit("SET_STORAGE_UPGRADE_INSTALLING", response.data);
                    console.log(response.data);
                    console.log(commit);
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
