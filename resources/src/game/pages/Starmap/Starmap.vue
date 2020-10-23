<script>
/******************************************************************************
 * Page: Starmap
 *****************************************************************************/
import { useI18n } from "vue-i18n";
export default {
    name: "PageStarmap",
    setup() {
        return {
            ...useI18n(),
        };
    },
};
</script>

<template>
    <article class="page">
        <h1>{{ t("starmap.title") }}</h1>
    </article>
</template>
