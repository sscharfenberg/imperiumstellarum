<script>
/******************************************************************************
 * PageComponent: RenderModule
 *****************************************************************************/
import Icon from "Components/Icon/Icon";
export default {
    name: "RenderModule",
    props: {
        techType: String,
        tl: Number,
        disabled: Boolean,
        remove: Boolean,
    },
    emits: ["removeModule", "addModule"],
    components: { Icon },
    setup(props, { emit }) {
        const onClick = () => {
            if (props.remove) {
                emit("remove-module", props.stub);
            } else {
                emit("add-module", props.stub);
            }
        };
        return {
            onClick,
        };
    },
};
</script>

<template>
    <button
        v-if="techType"
        class="modules__mod"
        @click="onClick"
        :disabled="disabled"
    >
        <icon :name="`tech-${techType}`" />
        <span>{{ $t("research.tl." + techType) }}</span>
        <span class="tl" v-if="tl >= 0">TL{{ tl }}</span>
    </button>
    <div v-if="!techType" class="modules__mod empty" />
</template>

<style lang="scss" scoped>
.modules__mod {
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;

    min-height: 42px;
    padding: 2px;
    border: 1px solid transparent;
    margin: 0 0 4px 0;

    background-color: transparent;
    outline: 0;
    cursor: pointer;

    font-size: 12px;

    transition: background-color map-get($animation-speeds, "fast") linear;

    @include respond-to("medium") {
        padding: 4px;
        margin-bottom: 8px;

        font-size: 14px;
    }

    @include respond-to("large") {
        padding: 8px;

        font-size: 16px;
    }

    @include themed() {
        color: t("t-light");
        border-color: t("g-raven");
    }

    &:hover:not(.empty) {
        @include themed() {
            background-color: t("g-ebony");
        }
    }

    .icon {
        width: 20px;
        height: 20px;

        @include respond-to("small") {
            width: 24px;
            height: 24px;
        }
    }

    .tl {
        display: none;

        @include respond-to("large") {
            display: block;
        }
    }

    &[disabled] {
        opacity: 0.6;

        cursor: not-allowed;
    }

    &.empty {
        cursor: default;
    }
}
</style>
