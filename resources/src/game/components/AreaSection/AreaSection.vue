<script>
/******************************************************************************
 * Component: AreaSection
 *****************************************************************************/
import { useI18n } from "vue-i18n";
export default {
    name: "AreaSection",
    props: {
        headline: String,
    },
    setup() {
        return {
            ...useI18n(),
        };
    },
};
</script>

<template>
    <section class="area-section">
        <h2 v-if="headline">{{ headline }}</h2>
        <slot></slot>
    </section>
</template>

<style lang="scss" scoped>
.screen-section {
    margin: 1.6rem 0;

    @include respond-to("medium") {
        margin: 3.2rem 0;
    }
}

h2 {
    margin: 0 0 0.8rem 0;

    font-weight: 300;

    @include themed() {
        color: t("b-viking");
    }

    @include respond-to("medium") {
        margin: 0 0 1.6rem 0;
    }
}
</style>
