<script>
/******************************************************************************
 * Component: AreaSection
 *****************************************************************************/
import { useI18n } from "vue-i18n";
import Loading from "Components/Loading/Loading";
export default {
    name: "AreaSection",
    props: {
        headline: String,
        requesting: Boolean,
    },
    components: { Loading },
    setup() {
        return {
            ...useI18n(),
        };
    },
};
</script>

<template>
    <section class="area-section">
        <h2 v-if="headline">
            <loading v-if="requesting" :size="32" />
            {{ headline }}
        </h2>
        <slot></slot>
    </section>
</template>

<style lang="scss" scoped>
.area-section {
    margin: 1.6rem 0;

    @include respond-to("medium") {
        margin: 3.2rem 0;
    }
}

h2 {
    display: flex;
    align-items: center;

    margin: 0 0 0.8rem 0;

    font-weight: 300;
    line-height: 1.4;

    @include themed() {
        color: t("b-viking");
    }

    @include respond-to("medium") {
        margin: 0 0 1.6rem 0;
    }

    > svg {
        margin-right: 0.8rem;
    }
}
</style>
