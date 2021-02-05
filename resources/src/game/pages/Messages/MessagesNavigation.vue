<script>
/******************************************************************************
 * PageComponent: MessagesNavigation
 *****************************************************************************/
import { computed } from "vue";
import { useStore } from "vuex";
import Icon from "Components/Icon/Icon";
export default {
    name: "MessagesNavigation",
    components: { Icon },
    setup() {
        const store = useStore();
        const pageIndex = computed({
            get: () => store.state.messages.page,
            set: (value) => {
                store.commit("messages/SET_PAGE", value);
            },
        });
        return {
            pageIndex,
        };
    },
};
</script>

<template>
    <nav class="messages-nav">
        <button
            class="messages-nav__link"
            @click="pageIndex = 0"
            :class="{ active: pageIndex === 0 }"
        >
            <icon name="messages" />
            {{ $t("messages.inbox.navTitle") }}
        </button>
        <button
            class="messages-nav__link"
            @click="pageIndex = 1"
            :class="{ active: pageIndex === 1 }"
        >
            <icon name="messages" />
            {{ $t("messages.outbox.navTitle") }}
        </button>
        <button
            class="messages-nav__link"
            @click="pageIndex = 2"
            :class="{ active: pageIndex === 2 }"
        >
            <icon name="edit" />
            {{ $t("messages.new.navTitle") }}
        </button>
    </nav>
</template>

<style lang="scss" scoped>
.messages-nav {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(170px, 1fr));

    padding: 0;
    margin: 0 0 8px 0;
    grid-gap: 8px;

    list-style: none;

    @include respond-to("medium") {
        margin-bottom: 16px;
        grid-gap: 16px;
    }

    &__link {
        display: flex;
        align-items: center;
        justify-content: center;

        padding: 8px;
        border: 2px solid transparent;

        outline: 0;
        cursor: pointer;

        font-size: 18px;
        font-weight: 300;

        transition: background-color map-get($animation-speeds, "fast") linear,
            color map-get($animation-speeds, "fast") linear,
            border-color map-get($animation-speeds, "fast") linear;

        @include themed() {
            background-color: t("g-sunken");
            color: t("t-light");
            border-color: t("g-raven");
        }

        @include respond-to("medium") {
            padding: 16px;

            font-size: 24px;
        }

        &:hover {
            @include themed() {
                background-color: t("g-ebony");
                color: t("t-light");
                border-color: t("g-asher");
            }
        }

        &.active {
            @include themed() {
                color: t("b-christine");
                border-color: t("b-gorse");
            }
        }

        .icon {
            width: 32px;
            height: 32px;
            margin-right: 8px;

            @include respond-to("medium") {
                width: 48px;
                height: 48px;
                margin-right: 16px;
            }
        }
    }
}
</style>
