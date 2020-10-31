<script>
/******************************************************************************
 * Component: GameButton
 *****************************************************************************/
import { computed } from "vue";
import Icon from "Components/Icon/Icon";
export default {
    name: "GameButton",
    props: {
        textString: String,
        iconName: String,
        size: {
            type: Number, // [0..3]
            default: 2,
        },
    },
    components: { Icon },
    setup(props) {
        const typeModifier = computed(() => {
            return props.iconName && !props.textString
                ? "btn--icon"
                : "btn--text";
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
        return {
            typeModifier,
            sizeClass,
        };
    },
};
</script>

<template>
    <button class="btn" :class="[typeModifier, sizeClass]">
        <icon v-if="iconName" :name="iconName" :size="size" />
        <span v-if="textString">{{ textString }}</span>
    </button>
</template>

<style lang="scss" scoped>
$btnBaseHeight: 3.4rem;

.btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;

    height: $btnBaseHeight;
    padding: 0 1.2rem 0 0.8rem;
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
        height: 2.6rem;

        font-size: 1.4rem;
    }

    &.small {
        height: 3rem;
    }

    &.large {
        height: 4.2rem;

        font-size: 2rem;
    }

    &--text {
        > svg {
            margin-right: 0.5rem;
        }
    }

    > span {
        line-height: 1;
    }

    &--icon {
        width: 3.4rem;
        height: 3.4rem;
        padding: 0 0.8rem;

        border-radius: 50%;

        @include themed() {
            color: t("t-tint");
        }

        &.tiny {
            width: 2.6rem;
            height: 2.6rem;
        }

        &.small {
            width: 3rem;
            height: 3rem;
        }

        &.large {
            width: 4.2rem;
            height: 4.2rem;
        }
    }
}
</style>
