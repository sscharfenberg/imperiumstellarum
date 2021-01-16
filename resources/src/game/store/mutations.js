/******************************************************************************
 * Vuex root mutations
 *****************************************************************************/
import { saveState } from "@/game/store/persistState";

export default {
    /**
     * SET GAME NUMBER
     * @param {Object} state - Vuex state
     * @param {Object} payload
     * @param {Number} payload.game.gameNumber
     * @param {Number} payload.game.gameTurn
     * @param {Number} payload.game.dueInSeconds
     * @param {Number} payload.player.empireName
     * @param {Number} payload.player.empireTicker
     * @param {Number} payload.player.researchPriority
     */
    SET_GAME_META_DATA: (state, payload) => {
        // game
        state.gameNumber = payload.game.number;
        state.gameTurn = payload.game.turn;
        state.turnDue = payload.game.turnDue;
        // empire
        state.empireName = payload.player.empireName;
        state.empireTicker = payload.player.empireTicker;
        state.empireId = payload.player.id;
        state.empireResearchPriority = payload.player.researchPriority;
        state.resources = payload.resources;
        state.storageUpgrades = payload.storageUpgrades;
    },

    /**
     * SET PLAYER RESOURCES
     * @param state
     * @param {array} payload
     * @constructor
     */
    SET_RESOURCES: (state, payload) => {
        state.resources = payload;
    },

    /**
     * SET PLAYER RESOURCES
     * @param state
     * @param {array} payload
     * @constructor
     */
    SET_STORAGE_UPGRADES: (state, payload) => {
        state.storageUpgrades = payload;
    },

    /**
     * SET PLAYER RESEARCH PRIORITY
     * @param state
     * @param {Number} payload
     * @constructor
     */
    SET_RESEARCH_PRIORITY: (state, payload) => {
        state.empireResearchPriority = payload;
    },

    /**
     * TOGGLE COLLAPSIBLE ID
     * @param state
     * @param {String} payload
     * @constructor
     */
    TOGGLE_COLLAPSIBLE_ID: (state, payload) => {
        if (state.collapsibleExpandedIds.includes(payload)) {
            state.collapsibleExpandedIds.splice(
                state.collapsibleExpandedIds.indexOf(payload),
                1
            );
        } else {
            state.collapsibleExpandedIds.push(payload);
        }
        saveState(state.collapsibleExpandedIds, "collapsibleExpandedIds");
    },
};
