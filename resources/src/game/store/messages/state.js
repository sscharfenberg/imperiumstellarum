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
    // new message
    new: {
        tickerSearch: "",
        recipients: [],
        subject: "",
        body: "",
    },
};
