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
        const numInbox = computed(
            () => store.state.messages.inbox.filter((m) => m.senderId).length
        );
        const unreadInboxMessages = computed(
            () =>
                store.state.messages.inbox.filter((m) => m.senderId && !m.read)
                    .length
        );
        const numSysbox = computed(
            () => store.state.messages.inbox.filter((m) => !m.senderId).length
        );
        const unreadSysboxMessages = computed(
            () =>
                store.state.messages.inbox.filter((m) => !m.senderId && !m.read)
                    .length
        );
        const dead = computed(() => store.state.dead);
        const gameOver = computed(() => store.state.gameEnded);
        const onPageChange = (page) => {
            store.commit("messages/SET_MASS_DELETE_IDS", []);
            pageIndex.value = page;
        };
        return {
            pageIndex,
            numInbox,
            unreadInboxMessages,
            numSysbox,
            unreadSysboxMessages,
            onPageChange,
            dead,
            gameOver,
        };
    },
};
</script>

<template>
    <nav class="messages-nav">
        <button
            class="messages-nav__link"
            @click="onPageChange(0)"
            :class="{ active: pageIndex === 0 }"
        >
            <icon name="info" />
            {{ $t("messages.sysbox.navTitle") }}
            <span
                v-if="unreadSysboxMessages > 0"
                class="pill"
                :title="
                    $t('messages.sysbox.navLabel', {
                        unread: unreadSysboxMessages,
                        num: numSysbox,
                    })
                "
                :aria-label="
                    $t('messages.sysbox.navLabel', {
                        unread: unreadSysboxMessages,
                        num: numSysbox,
                    })
                "
            >
                {{ unreadSysboxMessages }}
            </span>
        </button>
        <button
            class="messages-nav__link"
            @click="onPageChange(1)"
            :class="{ active: pageIndex === 1 }"
        >
            <icon name="messages" />
            {{ $t("messages.inbox.navTitle") }}
            <span
                v-if="unreadInboxMessages > 0"
                class="pill"
                :title="
                    $t('messages.inbox.navLabel', {
                        unread: unreadInboxMessages,
                        num: numInbox,
                    })
                "
                :aria-label="
                    $t('messages.inbox.navLabel', {
                        unread: unreadInboxMessages,
                        num: numInbox,
                    })
                "
            >
                {{ unreadInboxMessages }}
            </span>
        </button>
        <button
            class="messages-nav__link"
            @click="onPageChange(2)"
            :class="{ active: pageIndex === 2 }"
        >
            <icon name="messages" />
            {{ $t("messages.outbox.navTitle") }}
        </button>
        <button
            v-if="!dead && !gameOver"
            class="messages-nav__link messages-nav__link--new"
            @click="onPageChange(3)"
            :class="{ active: pageIndex === 3 }"
        >
            <icon name="edit" />
            {{ $t("messages.new.navTitle") }}
        </button>
    </nav>
</template>

<style lang="scss" scoped>
.messages-nav {
    display: grid;
    grid-template-columns: 1fr;

    padding: 0;
    margin: 0 0 8px 0;
    grid-gap: 8px;

    list-style: none;

    @include respond-to("medium") {
        grid-template-columns: 1fr 1fr 1fr;

        margin-bottom: 16px;
        grid-gap: 16px;
    }

    &__link {
        display: flex;
        position: relative;
        align-items: center;
        justify-content: center;

        overflow: hidden;
        padding: 8px;
        border: 2px solid transparent;

        outline: 0;
        cursor: pointer;

        font-size: 16px;
        font-weight: 300;
        white-space: nowrap;
        text-overflow: ellipsis;

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

            font-size: 20px;
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

        &--new {
            grid-column: 1/-1;

            padding: 2px;

            @include respond-to("medium") {
                padding: 4px;
            }
        }

        .pill {
            position: absolute;
            top: 0;
            right: 0;

            padding: 2px 4px;

            font-size: 16px;

            @include respond-to("medium") {
                padding: 4px 8px;
            }

            @include themed() {
                background-color: t("g-raven");
                color: t("t-light");
            }
        }
    }
}

.unread {
    margin-left: 4px;

    @include respond-to("medium") {
        margin-left: 8px;
    }
}
</style>
