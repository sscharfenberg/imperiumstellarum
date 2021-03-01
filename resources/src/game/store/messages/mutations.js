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
     * @function SET relations of this player
     * @param {Object} state - vuex module "messages" state
     * @param {Object} payload
     */
    SET_PER_PAGE: (state, payload) => {
        state.perPage[payload.mailbox] = payload.perPage;
        saveState(
            {
                in: state.perPage.in,
                out: state.perPage.out,
                sys: state.perPage.sys,
            },
            "messagesPerPage"
        );
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
     * @function ADD a playerId to recipients
     * @param {Object} state - vuex module "messages" state
     * @param {String} payload
     */
    ADD_RECIPIENT: (state, payload) => {
        state.new.recipients.push(payload);
    },

    /**
     * @function REMOVE a playerId from recipients
     * @param {Object} state - vuex module "messages" state
     * @param {String} payload
     */
    REMOVE_RECIPIENT: (state, payload) => {
        state.new.recipients.splice(state.new.recipients.indexOf(payload), 1);
    },

    /**
     * @function SET recipients array
     * @param {Object} state - vuex module "messages" state
     * @param {Array} payload
     */
    SET_RECIPIENTS: (state, payload) => {
        state.new.recipients = payload;
    },

    /**
     * @function RESET recipients array
     * @param {Object} state - vuex module "messages" state
     */
    RESET_RECIPIENTS: (state) => {
        state.new.recipients = [];
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

    /**
     * @function SET message id to which this message replies to
     * @param {Object} state - vuex module "messages" state
     * @param {String} payload
     */
    SET_REPLIES_TO_MESSAGE_ID: (state, payload) => {
        state.new.repliesToMessageId = payload;
    },

    /**
     * @function SET IDs of messages which should be deleted
     * @param {Object} state - vuex module "messages" state
     * @param {Array} payload
     */
    SET_MASS_DELETE_IDS: (state, payload) => {
        state.massDeleteIds = payload;
    },

    /**
     * @function add a messageId to massDeleteIds
     * @param state
     * @param payload
     * @constructor
     */
    ADD_MASS_DELETE_ID: (state, payload) => {
        if (state.massDeleteIds.indexOf(payload) === -1) {
            state.massDeleteIds.push(payload);
        }
    },

    /**
     * @function add a messageId to massDeleteIds
     * @param state
     * @param payload
     * @constructor
     */
    REMOVE_MASS_DELETE_ID: (state, payload) => {
        if (state.massDeleteIds.indexOf(payload) > -1) {
            state.massDeleteIds.splice(state.massDeleteIds.indexOf(payload), 1);
        }
    },
};
