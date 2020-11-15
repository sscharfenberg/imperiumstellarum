<script>
/******************************************************************************
 * Component: LinearProgress
 *****************************************************************************/
import { computed } from "vue";
export default {
    name: "LinearProgress",
    props: {
        max: Number,
        value: Number,
        showText: {
            type: Boolean,
            default: false,
        },
    },
    setup(props) {
        const donePct = computed(() => (props.value / props.max) * 100);
        const remainingStyle = computed(() => {
            return { width: Math.floor(donePct.value) + "%" };
        });
        const txtLabel = computed(
            () => `${props.value} / ${props.max} - ${donePct.value.toFixed(2)}%`
        );
        return {
            donePct,
            remainingStyle,
            txtLabel,
        };
    },
};
</script>

<template>
    <div
        role="progressbar"
        aria-valuemin="0"
        :aria-valuemax="max"
        :aria-valuenow="value"
        class="progress"
    >
        <div class="bar" :style="remainingStyle" />
        <div class="text" :class="{ hidden: !showText }" :aria-label="txtLabel">
            {{ value }} / {{ max }} - ({{ donePct.toFixed(2) }}%)
        </div>
    </div>
</template>

<style lang="scss" scoped>
.progress {
    display: flex;
    position: relative;
    justify-content: flex-start;

    flex-grow: 1;

    @include themed() {
        border: 2px solid t("g-asher");

        background-color: t("g-raven");
    }
}

.text {
    position: absolute;
    top: 3px;
    right: 3px;
    z-index: 1;

    overflow: hidden;

    line-height: 1;
    text-align: center;
    text-indent: -1000em;
    white-space: nowrap;
    text-overflow: ellipsis;

    @include respond-to("small") {
        text-indent: 0;
    }

    @include themed() {
        color: t("t-light");

        text-shadow: 1px 1px t("t-dark"), -1px 1px t("t-dark"),
            1px -1px t("t-dark"), -1px -1px t("t-dark");
    }

    &.hidden {
        display: none;
    }
}

.bar {
    height: 16px;

    margin: 2px;

    @include themed() {
        border: 1px solid t("s-success");
        /* stylelint-disable */
        background: repeating-linear-gradient(
                -45deg,
                transparent,
                transparent 10px,
                lighten(t("s-success"), 5%) 10px,
                lighten(t("s-success"), 5%) 20px
            ),
            linear-gradient(to bottom, t("s-building"), t("s-active"));
        /* stylelint-enable */
    }
}
</style>
