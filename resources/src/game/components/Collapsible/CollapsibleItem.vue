<script>
/******************************************************************************
 * Component: Collapsible
 *****************************************************************************/
import { ref } from "vue";
import Icon from "Components/Icon/Icon";
export default {
    name: "Collapsible",
    props: {
        topic: String,
        iconName: String,
        expanded: Boolean,
    },
    components: { Icon },
    setup(props) {
        const show = ref(props.expanded);
        return {
            show,
        };
    },
};
</script>

<template>
    <div class="collapsible__item">
        <button class="collapsible__topic" @click="show = !show">
            <icon
                name="expand_more"
                class="expand"
                :class="{ rotate: show }"
                :size="3"
            />
            <icon
                v-if="iconName"
                :name="iconName"
                class="collapsible__topic-icon"
            />
            {{ topic }}
        </button>
        <transition name="list">
            <div
                v-if="show"
                class="collapsible__definition"
                aria-expanded="true"
            >
                <slot></slot>
            </div>
        </transition>
    </div>
</template>

<style lang="scss" scoped>
.collapsible {
    &__item {
        margin-bottom: 4px;

        @include respond-to("medium") {
            margin-bottom: 8px;
        }

        &:last-of-type {
            margin-bottom: 0;
        }

        @include themed() {
            background: radial-gradient(
                ellipse 27px 27px at 15px 18px,
                transparent 0%,
                transparent 99%,
                t("g-sunken") 100%
            );
        }
    }

    &__topic {
        display: flex;
        align-items: center;

        width: 100%;
        padding: 4px 8px 4px 0;
        border: 0;
        margin: 0;

        background: transparent;
        outline: 0;
        cursor: pointer;

        text-align: left;

        @include themed() {
            color: t("t-light");
        }

        .expand {
            margin-right: 16px;

            transition: transform map-get($animation-speeds, "fast") linear;

            @include themed() {
                color: t("b-gorse");
            }

            &.rotate {
                transform: rotate(180deg);
            }
        }
    }

    &__topic-icon {
        margin: 0 16px 0 8px;
    }

    &__definition {
        padding: 8px;

        @include respond-to("medium") {
            padding: 16px;
        }

        @include themed() {
            color: t("t-light");
        }
    }
}

.list-enter-active,
.list-leave-active {
    transition: all 150ms ease;
}
.list-enter-from,
.list-leave-to {
    opacity: 0;

    transform: translateX(-200px);
}
</style>
