<script>
/******************************************************************************
 * Page: Research
 *****************************************************************************/
import { useI18n } from "vue-i18n";
export default {
    name: "PageResearch",
    setup() {
        return {
            ...useI18n(),
        };
    },
};
</script>

<template>
    <article class="page">
        <h1>{{ t("research.title") }}</h1>
    </article>
</template>
