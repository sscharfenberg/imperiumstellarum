/******************************************************************************
 * Vuex mutations
 *****************************************************************************/
import { saveState } from "@/game/store/persistState";

export default {
    /**
     * @function SET/UNSET "requesting"
     * @param {Object} state - vuex module "messages" state
     * @param {Boolean} payload
     */
    SET_REQUESTING: (state, payload) => {
        state.requesting = payload;
    },

    /**
     * @function SET broadcasts
     * @param {Object} state - vuex module "messages" state
     * @param {Array} payload
     */
    SET_INBOX: (state, payload) => {
        state.inbox = payload;
    },

    /**
     * @function SET sent broadcasts
     * @param {Object} state - vuex module "messages" state
     * @param {Array} payload
     */
    SET_OUTBOX: (state, payload) => {
        state.outbox = payload;
    },

    /**
     * @function SET sent broadcasts
     * @param {Object} state - vuex module "messages" state
     * @param {Number} payload
     */
    SET_PAGE: (state, payload) => {
        state.page = payload;
        saveState(payload, "messagesPage");
    },

    /**
     * @function SET players
     * @param {Object} state - vuex module "messages" state
     * @param {Array} payload
     */
    SET_PLAYERS: (state, payload) => {
        state.players = payload;
    },

    /**
     * @function SET relations of this player
     * @param {Object} state - vuex module "messages" state
     * @param {Array} payload
     */
    SET_RELATIONS: (state, payload) => {
        state.relations = payload;
    },

    /**
     * new message
     */

    /**
     * @function SET ticker search
     * @param {Object} state - vuex module "messages" state
     * @param {String} payload
     */
    SET_SEARCH_TICKER: (state, payload) => {
        state.new.tickerSearch = payload;
    },

    /**
     * @function SET recipient id
     * @param {Object} state - vuex module "messages" state
     * @param {String} payload
     */
    SET_RECIPIENT_ID: (state, payload) => {
        state.new.recipientId = payload;
    },

    /**
     * @function SET message subject
     * @param {Object} state - vuex module "messages" state
     * @param {String} payload
     */
    SET_SUBJECT: (state, payload) => {
        state.new.subject = payload;
    },

    /**
     * @function SET message subject
     * @param {Object} state - vuex module "messages" state
     * @param {String} payload
     */
    SET_BODY: (state, payload) => {
        state.new.body = payload;
    },
};
