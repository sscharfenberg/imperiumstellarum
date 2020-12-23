<script>
/******************************************************************************
 * Component: SubHeadline
 *****************************************************************************/
import { computed } from "vue";
export default {
    name: "SubHeadline",
    props: {
        headline: String,
    },
    setup(props, { slots }) {
        const showSlot = computed(() => slots.default);
        return {
            showSlot,
        };
    },
};
</script>

<template>
    <header>
        <h1>
            {{ headline }}
            <span v-if="showSlot" class="aside">
                <slot></slot>
            </span>
        </h1>
    </header>
</template>

<style lang="scss" scoped>
header h1 {
    display: flex;
    align-items: center;
    justify-content: center;

    margin: 0 0 8px 0;

    font-size: 16px;
    font-weight: 300;

    @include respond-to("medium") {
        margin-bottom: 16px;

        font-size: 18px;
    }

    &::after {
        display: block;

        height: 2px;
        margin-left: 8px;
        flex-grow: 1;

        content: " ";

        @include themed() {
            background: linear-gradient(
                to left,
                t("b-gorse"),
                t("b-christine")
            );
        }

        @include respond-to("medium") {
            margin-left: 16px;
        }
    }

    .aside {
        order: 3;

        margin-left: 8px;

        @include respond-to("medium") {
            margin-left: 16px;
        }
    }
}
</style>
