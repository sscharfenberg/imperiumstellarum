/******************************************************************************
 * Vuex module "messages" state
 *****************************************************************************/
import { getState } from "@/game/store/persistState";

const savedState = getState();

/*
 * get initial module state
 * @returns {Object}
 */
export default {
    requesting: false,
    // area meta data
    inbox: [],
    outbox: [],
    page: savedState.messagesPage || 0,
    players: [],
    relations: [],
    reports: [],
    // new message
    new: {
        tickerSearch: "",
        recipients: [],
        repliesToMessageId: "",
        subject: "",
        body: "",
    },
    // per page settings for inboxes
    perPage: savedState.messagesPerPage || {},
    massDeleteIds: [],
    massDeleteMailbox: "",
    reportMessageId: "",
    reportMessageComment: "",
};
