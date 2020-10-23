<script>
/******************************************************************************
 * Page: Empire
 *****************************************************************************/
import { useI18n } from "vue-i18n";
export default {
    name: "PageEmpire",
    setup() {
        return {
            ...useI18n(),
        };
    },
};
</script>

<template>
    <article class="page">
        <h1>{{ t("empire.title") }}</h1>
    </article>
</template>
