<script>
/******************************************************************************
 * Component: Popover
 *****************************************************************************/
import Icon from "Components/Icon/Icon";
export default {
    name: "Info",
    props: {
        icon: {
            type: String,
            default: "help",
        },
    },
    components: { Icon },
    setup() {
        return {};
    },
};
</script>

<template>
    <div class="popover">
        <div class="popover__modal">
            <slot></slot>
        </div>
        <icon :name="icon" class="popover__trigger" :size="2" />
    </div>
</template>

<style lang="scss" scoped>
.popover {
    position: relative;

    padding: 4px;

    &:hover > .popover__trigger {
        @include themed() {
            color: t("s-viking");
        }
    }

    &__modal {
        position: absolute;
        top: calc(100% + 9px);
        left: 0;
        z-index: z("popover");

        width: 320px;
        padding: 16px;
        border: 3px solid transparent;

        @include themed() {
            background-color: rgba(t("g-black"), 0.85);
            border-color: t("g-raven");
        }

        &::before {
            display: block;
            position: absolute;
            top: -11px;
            left: 2px;

            width: 0;
            height: 0;
            border-width: 0 8px 8px;

            border-style: solid;

            content: " ";

            @include themed() {
                border-color: transparent transparent t("g-raven") transparent;
            }
        }
    }
}
</style>
