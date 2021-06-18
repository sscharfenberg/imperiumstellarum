<script>
/******************************************************************************
 * Component: Collapsible
 *****************************************************************************/
import { computed } from "vue";
import { useStore } from "vuex";
import Icon from "Components/Icon/Icon";
export default {
    name: "Collapsible",
    props: {
        collapsibleId: {
            type: String,
            required: true,
        },
        expanded: Boolean,
        altBg: Boolean,
    },
    components: { Icon },
    emits: ["open", "close"],
    setup(props, { slots }) {
        const store = useStore();
        const show = computed(
            () =>
                store.state.collapsibleExpandedIds.includes(
                    props.collapsibleId
                ) || props.expanded
        );
        const renderAside = computed(() => slots.aside);
        const renderTopic = computed(() => slots.topic);
        const onToggle = () => {
            store.commit("TOGGLE_COLLAPSIBLE", props.collapsibleId);
        };
        return {
            show,
            renderAside,
            renderTopic,
            onToggle,
        };
    },
};
</script>

<template>
    <div class="collapsible__item" :class="{ 'collapsible__item--alt': altBg }">
        <button
            class="collapsible__topic"
            @click="onToggle"
            :aria-expanded="show"
            aria-haspopup="true"
        >
            <icon
                name="expand_more"
                class="expand"
                :class="{ rotate: show }"
                :size="3"
            />
            <div class="collapsible__topic-text" v-if="renderTopic">
                <slot name="topic"></slot>
            </div>
            <span class="right" v-if="renderAside">
                <slot name="aside"></slot>
            </span>
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

        &--alt {
            @include themed() {
                background: radial-gradient(
                    ellipse 27px 27px at 15px 18px,
                    transparent 0%,
                    transparent 99%,
                    darken(t("g-bunker"), 2%) 100%
                );
            }
        }
    }

    &__topic {
        display: flex;
        align-items: center;
        //flex-wrap: wrap;

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
            height: 32px;
            margin-right: 16px;
            flex: 0 0 32px;

            transition: transform map-get($animation-speeds, "fast") linear;

            @include themed() {
                color: t("b-gorse");
            }

            &.rotate {
                transform: rotate(180deg);
            }
        }

        .right {
            display: flex;
            align-items: center;

            margin-left: auto;
        }
    }

    &__topic-text {
        display: flex;
        align-items: center;

        overflow: hidden;

        white-space: nowrap;
        text-overflow: ellipsis;
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
