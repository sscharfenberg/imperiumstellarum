<script>
/******************************************************************************
 * Page: Diplomacy
 *****************************************************************************/
import { useI18n } from "vue-i18n";
export default {
    name: "PageDiplomacy",
    setup() {
        return {
            ...useI18n(),
        };
    },
};
</script>

<template>
    <article class="page">
        <h1>{{ t("diplomacy.title") }}</h1>
    </article>
</template>
