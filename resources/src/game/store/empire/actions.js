/******************************************************************************
 * Vuex actions
 *****************************************************************************/

export default {
    /*
     * GET game data from api
     * @param {Object} ctx - Vuex context
     */
    GET_GAME_DATA: function ({ commit }) {
        commit("SET_REQUESTING", true);
        window.axios
            .get("/api/game/empire")
            .then((response) => {
                if (response.status === 200) {
                    const data = response.data;
                    const meta = {
                        gameNumber: data.game.number,
                        gameTurn: data.game.turn,
                        turnDue: data.game.turnDue,
                        empireName: data.player.name,
                        empireTicker: data.player.ticker,
                        empireResearchPrio: data.player.researchPriority,
                    };
                    commit("SET_GAME_META_DATA", meta, { root: true });
                }
            })
            .catch((e) => {
                console.error(e);
            })
            .finally(() => {
                commit("SET_REQUESTING", false);
            });
    },
};
