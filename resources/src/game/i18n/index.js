/******************************************************************************
 *
 * index handling
 *
 *****************************************************************************/
import { createI18n } from "vue-i18n";
import deTexts from "./de";
import enTexts from "./en";

export const i18n = createI18n({
    locale: document.documentElement.lang,
    messages: {
        de: deTexts,
        en: enTexts,
    },
});
