<script>
/******************************************************************************
 * Component: GameButton
 *****************************************************************************/
import { computed } from "vue";
import Icon from "Components/Icon/Icon";
import Loading from "Components/Loading/Loading";
export default {
    name: "GameButton",
    props: {
        textString: String,
        iconName: String,
        size: {
            type: Number, // [0..3]
            default: 2,
        },
        primary: {
            type: Boolean,
            default: false,
        },
        loading: {
            type: Boolean,
            default: false,
        },
        hideTextForMobile: {
            type: Boolean,
            default: false,
        },
    },
    components: { Icon, Loading },
    setup(props) {
        const typeModifier = computed(() => {
            let typeClass = props.primary ? "btn--primary " : "";
            typeClass +=
                props.iconName && !props.textString ? "btn--icon" : "btn--text";
            typeClass +=
                props.hideTextForMobile && props.textString && props.iconName
                    ? " mobile-hide"
                    : "";
            return typeClass;
        });
        const sizeClass = computed(() => {
            switch (props.size) {
                case 0:
                    return "tiny";
                case 1:
                    return "small";
                case 2:
                    return "";
                case 3:
                    return "large";
            }
        });
        const loadingSize = computed(() => {
            switch (props.size) {
                case 0:
                    return 20;
                case 1:
                    return 24;
                case 2:
                    return 28;
                case 3:
                    return 36;
            }
        });
        return {
            typeModifier,
            sizeClass,
            loadingSize,
        };
    },
};
</script>

<template>
    <button class="btn" :class="[typeModifier, sizeClass]">
        <icon v-if="iconName && !loading" :name="iconName" :size="size" />
        <loading v-if="loading" :size="loadingSize" />
        <span v-if="textString">{{ textString }}</span>
    </button>
</template>

<style lang="scss" scoped>
$btnBaseHeight: 34px;

.btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;

    height: $btnBaseHeight;
    padding: 0 12px 0 8px;
    border: 1px solid transparent;

    cursor: pointer;

    @include themed() {
        background: t("g-raven");
        color: t("t-light");
        border-color: t("g-asher");
    }

    transition: background-color map-get($animation-speeds, "fast") linear,
        color map-get($animation-speeds, "fast") linear,
        border-color map-get($animation-speeds, "fast") linear;

    &:hover:not([disabled]),
    &:focus {
        outline: 0;

        @include themed() {
            background: t("g-bunker");
            color: t("b-viking");
            border-color: t("g-raven");
        }
    }

    &:active {
        @include themed() {
            background: t("g-ebony");
            color: t("g-white");
        }
    }

    &[disabled] {
        opacity: 0.3;

        cursor: not-allowed;
    }

    &.tiny {
        height: 26px;

        font-size: 14px;
    }

    &.small {
        height: 30px;
    }

    &.large {
        height: 42px;

        font-size: 2px;
    }

    &--text:not(.mobile-hide) {
        > svg {
            margin-right: 5px;
        }
    }

    > span {
        line-height: 1;
    }

    &.mobile-hide {
        padding: 0 8px;

        @include respond-to("medium") {
            padding: 0 12px 0 8px;

            > svg {
                margin-right: 5px;
            }
        }

        > span {
            display: none;

            @include respond-to("medium") {
                display: inline;
            }
        }
    }

    &--icon {
        width: 34px;
        height: 34px;
        padding: 0 8px;

        border-radius: 50%;

        @include themed() {
            color: t("t-tint");
        }

        &.tiny {
            width: 26px;
            height: 26px;
        }

        &.small {
            width: 30px;
            height: 30px;
        }

        &.large {
            width: 42px;
            height: 42px;
        }
    }

    &--primary {
        @include themed() {
            background: t("b-viking");
            color: t("t-dark");
            border-color: t("g-white");
        }

        &:hover:not([disabled]) {
            @include themed() {
                color: t("b-christine");
                border-color: t("b-darkbg");
            }
        }
    }
}
</style>
