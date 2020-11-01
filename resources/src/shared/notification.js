/******************************************************************************
 * Notifications
 *****************************************************************************/
import Noty from "noty";

/**
 * @function wrapper function for noty
 * @param {String} text
 * @param {String} type
 * @param {Number|Boolean} timeout
 * https://ned.im/noty/#/options
 */
export const notify = (text, type = "alert", timeout = 5000) => {
    new Noty({
        type,
        theme: "sunset",
        timeout,
        layout: "topRight",
        text,
    }).show();
};
