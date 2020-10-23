<script>
/******************************************************************************
 * Page: Fleets
 *****************************************************************************/
import { useI18n } from "vue-i18n";
export default {
    name: "PageFleets",
    setup() {
        return {
            ...useI18n(),
        };
    },
};
</script>

<template>
    <article class="page">
        <h1>{{ t("fleets.title") }}</h1>
    </article>
</template>
