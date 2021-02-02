<script>
/******************************************************************************
 * Component: AreaSection
 *****************************************************************************/
import { computed } from "vue";
import Loading from "Components/Loading/Loading";
export default {
    name: "AreaSection",
    props: {
        headline: String,
        requesting: Boolean,
    },
    components: { Loading },
    setup(props, { slots }) {
        const renderAside = computed(() => slots.aside);
        return {
            renderAside,
        };
    },
};
</script>

<template>
    <section class="area-section">
        <h2 v-if="headline">
            <loading v-if="requesting" :size="32" />
            {{ headline }}
            <span class="aside" v-if="renderAside">
                <slot name="aside"></slot>
            </span>
        </h2>
        <slot></slot>
    </section>
</template>

<style lang="scss" scoped>
.area-section {
    margin: 16px 0;

    @include respond-to("medium") {
        margin: 32px 0;
    }
}

h2 {
    display: flex;
    align-items: center;
    justify-content: flex-start;
    flex-wrap: wrap;

    margin: 0 0 8px 0;

    font-weight: 300;
    line-height: 1.4;

    @include themed() {
        color: t("b-viking");
    }

    @include respond-to("medium") {
        margin: 0 0 16px 0;
    }

    > svg {
        margin-right: 8px;
    }

    .aside {
        display: flex;
        align-items: flex-start;

        margin-top: 8px;
        flex: 0 0 100%;

        font-size: 16px;

        @include themed() {
            color: t("t-light");
        }

        @include respond-to("medium") {
            margin: 0 0 0 auto;
            flex-basis: auto;
        }
    }
}
</style>
