/******************************************************************************
 * formatting helpers
 *****************************************************************************/
import { format } from "date-fns";

/**
 * @function convert a latin number to roman number string
 * @param {Number} latin
 * @returns {String}
 */
export const convertLatinToRoman = (latin) => {
    let result = "";
    const decimal = [1000, 900, 500, 400, 100, 90, 50, 40, 10, 9, 5, 4, 1];
    const roman = [
        "M",
        "CM",
        "D",
        "CD",
        "C",
        "XC",
        "L",
        "XL",
        "X",
        "IX",
        "V",
        "IV",
        "I",
    ];
    latin = parseInt(latin, 10);
    if (latin === 0) return "N";
    for (let i = 0; i <= decimal.length; i++) {
        // looping over every element of our arrays
        while (latin % decimal[i] < latin) {
            // keep trying the same number until it won"t fit anymore
            result += roman[i];
            // add the matching roman number to our result string
            latin -= decimal[i];
            // remove the decimal value of the roman number from our number
        }
    }
    return result;
};

/**
 * @function convert a integer to a local formatted string
 * simple helper function using the browsers toLocaleString(locale)
 * expects the locale as 'de-DE', 'at-DE' etc not supported (would be converted to 'at-AT').
 * @param {Number} number
 * @returns {String}
 */
export const formatNumber = (number) => {
    const language = document.querySelector("html").lang;
    const locale = `${language}-${language.toUpperCase()}`;
    return number.toLocaleString(locale);
};

/**
 * @function formats a js DateTime with the browser locale
 * @param {Date} date
 * @returns {String}
 */
export const formatDateTime = (date) => {
    const language = document.querySelector("html").lang;
    let formatting = (lang) => {
        switch (lang) {
            case "en":
            default:
                return "dd/MM/uuuu hh:mm:ss aaa";
            case "de":
                return "dd.MM.uuuu HH:mm:ss";
        }
    };
    return format(date, formatting(language));
};

/**
 * @function formats a short js DateTime (only time!) with the browser locale
 * @param {Date} date
 * @returns {String}
 */
export const formatTime = (date) => {
    const language = document.querySelector("html").lang;
    let formatting = (lang) => {
        switch (lang) {
            case "en":
            default:
                return "hh:mm:ss aaa";
            case "de":
                return "HH:mm:ss";
        }
    };
    return format(date, formatting(language));
};

/**
 * @function format a minimarkdown message to html
 * @param {String} body
 * @returns {String}
 */
export const formatMessageBody = (body) => {
    return body
        .replace(/(<([^>]+)>)/gi, "") // make sure there really is no html in the string left.
        .replace(/[\r\n]+/g, "<br />"); // linebreaks => <br>
};
