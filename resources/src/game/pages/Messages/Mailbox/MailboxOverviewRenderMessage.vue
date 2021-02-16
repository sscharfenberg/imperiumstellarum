<script>
/******************************************************************************
 * PageComponent: MailboxOverviewRenderMessage
 *****************************************************************************/
import { computed, ref } from "vue";
import { useStore } from "vuex";
import { addSeconds } from "date-fns";
import { formatDateTime } from "@/game/helpers/format";
import MessageDetailsModal from "./MessageDetailsModal";
export default {
    name: "MailboxOverviewRenderMessage",
    props: {
        mailbox: String, // "in" || "out"
        messageId: String,
        senderId: String,
        recipientIds: Array, // of playerIds
        datetime: Number,
        subject: String,
        body: String,
        read: Boolean,
    },
    components: { MessageDetailsModal },
    setup(props, { emit }) {
        const store = useStore();

        const showModal = ref(false);

        // sender for inbox messages
        const sender = computed(() => {
            return store.getters["messages/playerById"](props.senderId);
        });
        const senderTicker = computed(() => {
            if (!sender.value) return undefined;
            return sender.value.ticker;
        });
        const senderName = computed(() => {
            if (!sender.value) return undefined;
            return sender.value.name;
        });

        // formatted timestamp when the message was sent
        const timestampFormatted = computed(() => {
            return formatDateTime(addSeconds(new Date(), props.datetime));
        });

        // formatted recipient tickers
        const recipientTickers = computed(() => {
            const players = store.state.messages.players;
            if (props.recipientIds.length === 0) return "";
            return props.recipientIds
                .map(
                    (player) =>
                        `[${players.find((p) => p.id === player).ticker}]`
                )
                .join(", ");
        });

        // emit event handler
        const onClick = () => emit("clicked", props.messageId);

        // bodyShortened
        const bodyShortened = computed(() => {
            let maxStringLength = window.rules.messages.body.overviewMax;
            let str = props.body.replace(/[\r\n]+/g, " ");
            return str.length > maxStringLength
                ? str.substr(0, maxStringLength - 1) + "..."
                : str;
        });

        return {
            senderTicker,
            senderName,
            timestampFormatted,
            recipientTickers,
            bodyShortened,
            onClick,
            showModal,
        };
    },
};
</script>

<template>
    <button
        class="message"
        @click="showModal = true"
        :class="{ 'message--unread': mailbox === 'in' && !read }"
    >
        <span class="message__sender" v-if="mailbox === 'in'">
            [{{ senderTicker }}] {{ senderName }}
        </span>
        <span
            class="message__sender"
            v-if="mailbox === 'out'"
            :title="recipientTickers"
        >
            {{ recipientTickers }}
        </span>
        <span class="message__timestamp" :title="timestampFormatted">
            {{ timestampFormatted }}
        </span>
        <span class="message__subject">
            {{ subject }}
            <i
                class="unread"
                v-if="mailbox === 'in' && !read"
                :title="$t('messages.mailbox.unread')"
                :aria-label="$t('messages.mailbox.unread')"
            />
            <i
                class="read"
                v-if="mailbox === 'in' && read"
                :title="$t('messages.mailbox.read')"
                :aria-label="$t('messages.mailbox.read')"
            />
        </span>
        <span class="message__body">
            {{ bodyShortened }}
        </span>
    </button>
    <message-details-modal
        v-if="showModal"
        :message-id="messageId"
        :mailbox="mailbox"
        :timestamp-formatted="timestampFormatted"
        :read="read"
        @close="showModal = false"
    />
</template>

<style lang="scss" scoped>
.message {
    display: grid;
    position: relative;
    grid-template-columns: 1fr 1fr;

    width: 100%;
    padding: 0;
    border: 0;
    margin: 1px 0 0 0;
    grid-gap: 1px;

    background-color: transparent;
    outline: 0;

    cursor: pointer;

    font-weight: 300;
    text-align: left;

    @include respond-to("medium") {
        grid-template-columns: 2fr 1fr 2fr;

        margin-top: 2px;
        grid-gap: 2px;
    }

    @include themed() {
        color: t("t-light");
    }

    &::before {
        display: block;
        grid-column: span 3;

        height: 1px;

        content: " ";

        @include themed() {
            background: linear-gradient(
                to right,
                t("b-christine"),
                t("b-gorse")
            );
        }
    }

    &--unread {
        font-weight: 600;
    }

    &__sender,
    &__timestamp,
    &__subject,
    &__body {
        padding: 4px;
        border: 1px solid transparent;

        transition: border-color map-get($animation-speeds, "fast") linear,
            background-color map-get($animation-speeds, "fast") linear;

        @include respond-to("medium") {
            padding: 8px;
        }

        @include themed() {
            background-color: t("g-bunker");
            border-color: t("g-deep");
        }

        &.over {
            cursor: pointer;

            @include themed() {
                background-color: t("b-viking");
            }
        }
    }

    &__sender {
        overflow: hidden;

        white-space: nowrap;
        text-overflow: ellipsis;
    }

    &__subject {
        position: relative;
        grid-column: span 2;

        overflow: hidden;
        padding-right: 24px;

        white-space: nowrap;
        text-overflow: ellipsis;

        @include respond-to("medium") {
            grid-column: 3;
        }

        .unread,
        .read {
            position: absolute;
            top: 50%;
            right: 4px;

            width: 8px;
            height: 8px;
            transform: translate3d(0, -50%, 0);
            flex: 0 0 8px;

            border-radius: 50%;

            @include respond-to("medium") {
                right: 8px;
            }
        }

        .read {
            @include themed() {
                background: linear-gradient(
                    to bottom right,
                    t("s-active"),
                    t("s-success")
                );
            }
        }

        .unread {
            @include themed() {
                background: linear-gradient(
                    to bottom right,
                    t("b-gorse"),
                    t("b-christine")
                );
            }
        }
    }

    &__timestamp {
        overflow: hidden;

        white-space: nowrap;
        text-overflow: ellipsis;
    }

    &__body {
        display: none;

        overflow: hidden;

        white-space: nowrap;
        text-overflow: ellipsis;

        @include respond-to("medium") {
            display: block;
            grid-column: span 3;
        }
    }

    &:hover > span {
        @include themed() {
            background-color: t("g-ebony");
            border-color: t("b-viking");
        }
    }
}
</style>