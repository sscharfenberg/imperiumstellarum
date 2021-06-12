/******************************************************************************
 * Vuex root mutations
 *****************************************************************************/
import { saveState } from "@/game/store/persistState";

export default {
    /**
     * SET GAME NUMBER
     * @param {Object} state - Vuex state
     * @param {Object} payload
     * @param {Number} payload.game.number
     * @param {Number} payload.game.turn
     * @param {Number} payload.game.turnDue
     * @param {Boolean} payload.game.finished
     * @param {String} payload.player.empireName
     * @param {String} payload.player.empireTicker
     * @param {String} payload.player.id
     * @param {Number} payload.player.researchPriority
     * @param {String} payload.player.colour
     * @param {Boolean} payload.player.dead
     * @param {Array} payload.resources
     * @param {Array} payload.storageUpgrades
     * @param {Number} payload.unreadMessages
     * @param {Number} payload.unreadEncounters
     * @param {Number} payload.unreadRaids
     * @param {String} payload.winner
     */
    SET_GAME_META_DATA: (state, payload) => {
        // game
        state.gameNumber = payload.game.number;
        state.gameTurn = payload.game.turn;
        state.turnDue = payload.game.turnDue;
        state.gameEnded = payload.game.finished;
        if (payload.winner) state.winner = payload.winner;
        // empire
        state.empireName = payload.player.empireName;
        state.empireTicker = payload.player.empireTicker;
        state.empireId = payload.player.id;
        state.empireResearchPriority = payload.player.researchPriority;
        state.colour = payload.player.colour;
        state.dead = payload.player.dead;
        // resources, storageUpgrades
        state.resources = payload.resources;
        state.storageUpgrades = payload.storageUpgrades;
        // unread stuff
        state.unreadMessages = payload.unreadMessages;
        state.unreadEncounters = payload.unreadEncounters;
        state.unreadRaids = payload.unreadRaids;
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
     * SET NUMBER OF UNREAD MESSAGES
     * @param state
     * @param {Number} payload
     * @constructor
     */
    SET_UNREAD_MESSAGES: (state, payload) => {
        state.unreadMessages = payload;
    },

    /**
     * SET NUMBER OF UNREAD ENCOUNTERS
     * @param state
     * @param {Number} payload
     * @constructor
     */
    SET_UNREAD_ENCOUNTERS: (state, payload) => {
        state.unreadEncounters = payload;
    },

    /**
     * TOGGLE COLLAPSIBLE ID
     * @param state
     * @param {String} payload
     * @constructor
     */
    TOGGLE_COLLAPSIBLE: (state, payload) => {
        console.log("toggling ", payload);
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
