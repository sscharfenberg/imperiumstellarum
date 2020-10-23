<script>
/******************************************************************************
 * Page: Shipyards
 *****************************************************************************/
import { useI18n } from "vue-i18n";
export default {
    name: "PageShipyards",
    setup() {
        return {
            ...useI18n(),
        };
    },
};
</script>

<template>
    <article class="page">
        <h1>{{ t("shipyards.title") }}</h1>
    </article>
</template>
