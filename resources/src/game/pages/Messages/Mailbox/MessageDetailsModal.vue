<script>
/******************************************************************************
 * PageComponent: MessageDetailsModal
 *****************************************************************************/
import { computed, onBeforeMount } from "vue";
import { useStore } from "vuex";
import { addSeconds } from "date-fns";
import { formatMessageBody, formatDateTime } from "@/game/helpers/format";
import GameButton from "Components/Button/GameButton";
import MessageDetailsModalRepliesTo from "./MessageDetailsModalRepliesTo";
import Modal from "Components/Modal/Modal";
export default {
    name: "MessageDetailsModal",
    props: {
        messageId: String,
        mailbox: String, // "in" || "out"
        timestampFormatted: String, // pass as props because otherwise it would differ from whats shown on overview, since the data can be old(er)
        read: Boolean,
    },
    components: {
        GameButton,
        MessageDetailsModalRepliesTo,
        Modal,
    },
    emits: ["close"],
    setup(props, { emit }) {
        const store = useStore();
        const requesting = computed(() => store.state.messages.requesting);
        const message = computed(() =>
            props.mailbox === "in" || props.mailbox === "sys"
                ? store.getters["messages/messageById"](props.messageId)
                : store.getters["messages/sentMessageById"](props.messageId)
        );
        const player = (playerId) =>
            store.getters["messages/playerById"](playerId);

        const you = computed(() => `[${store.state.empireTicker}]`);
        const yourId = computed(() => store.state.empireId);

        const repliesToMessage = computed(() => {
            if (!message.value.repliesToId) return undefined;
            let returnedMessage;
            if (props.mailbox === "out") {
                returnedMessage = store.getters["messages/messageById"](
                    message.value.repliesToId
                );
            } else if (props.mailbox === "in" || props.mailbox === "sys") {
                returnedMessage = store.getters["messages/sentMessageById"](
                    message.value.repliesToId
                );
            }
            if (!returnedMessage) return undefined;
            return returnedMessage;
        });

        /**
         * @function click on "mark as unread": action with xhr to server, close modal
         */
        const onMarkUnreadClick = () => {
            store.dispatch("messages/MARK_MESSAGE_READ", {
                messageId: props.messageId,
                read: false,
            });
            //emit("close");
        };

        /**
         * @function click on "reply": prepare state for "new message" page
         */
        const onreplyClick = () => {
            store.commit("messages/SET_SEARCH_TICKER", "");
            store.commit("messages/RESET_RECIPIENTS");
            store.commit("messages/ADD_RECIPIENT", message.value.senderId);
            store.commit("messages/SET_REPLIES_TO_MESSAGE_ID", props.messageId);
            store.commit(
                "messages/SET_SUBJECT",
                `RE: ${message.value.subject}`.substr(
                    0,
                    window.rules.messages.subject.max
                )
            );
            store.commit("messages/SET_BODY", "");
            store.commit("messages/SET_PAGE", 3);
            emit("close");
        };

        /**
         * @function click on "reply all": prepare state for "new message" page
         */
        const onReplyAllClick = () => {
            const newRecipients = [
                ...message.value.recipientIds.filter(
                    (r) => r !== store.state.empireId
                ),
                ...[message.value.senderId],
            ];
            store.commit("messages/SET_SEARCH_TICKER", "");
            store.commit("messages/SET_RECIPIENTS", newRecipients);
            store.commit("messages/SET_REPLIES_TO_MESSAGE_ID", props.messageId);
            store.commit(
                "messages/SET_SUBJECT",
                `RE: ${message.value.subject}`.substr(
                    0,
                    window.rules.messages.subject.max
                )
            );
            store.commit("messages/SET_BODY", "");
            store.commit("messages/SET_PAGE", 3);
            emit("close");
        };

        const onDeleteClick = () => {
            store.dispatch("messages/DELETE_MESSAGE", {
                mailbox: props.mailbox,
                messageIds: [props.messageId],
            });
            //emit("close");
        };

        /**
         * @function before mount, call server and mark as read
         */
        onBeforeMount(() => {
            if (
                !props.read &&
                (props.mailbox === "in" || props.mailbox === "sys")
            ) {
                store.dispatch("messages/MARK_MESSAGE_READ", {
                    messageId: props.messageId,
                    read: true,
                });
            }
        });

        return {
            requesting,
            message,
            player,
            you,
            yourId,
            repliesToMessage,
            formatMessageBody,
            addSeconds,
            formatDateTime,
            onMarkUnreadClick,
            onreplyClick,
            onReplyAllClick,
            onDeleteClick,
        };
    },
};
</script>

<template>
    <modal :title="message.subject" @close="$emit('close')" :full-size="false">
        <div class="actions">
            <game-button
                v-if="mailbox === 'in' && message.senderId"
                :text-string="$t('messages.details.reply')"
                icon-name="reply"
                :loading="requesting"
                :disabled="requesting"
                @click="onreplyClick"
            />
            <game-button
                v-if="
                    mailbox === 'in' &&
                    message.recipientIds.length > 1 &&
                    message.senderId
                "
                :text-string="$t('messages.details.replyAll')"
                icon-name="reply_all"
                :loading="requesting"
                :disabled="requesting"
                @click="onReplyAllClick"
            />
            <game-button
                v-if="(mailbox === 'in' || mailbox === 'sys') && message.read"
                :text-string="$t('messages.details.markUnread')"
                icon-name="markunread"
                :loading="requesting"
                :disabled="requesting"
                @click="onMarkUnreadClick"
            />
            <game-button
                :text-string="$t('messages.details.delete')"
                icon-name="delete"
                :loading="requesting"
                :disabled="requesting"
                @click="onDeleteClick"
            />
        </div>

        <ul class="stats base-message">
            <li class="text-left">{{ $t("messages.details.sender") }}</li>
            <li class="text-left" v-if="mailbox === 'out'">
                {{ $t("messages.details.you", { ticker: you }) }}
            </li>
            <li
                class="text-left"
                v-else-if="mailbox === 'in' && message.senderId"
            >
                [{{ player(message.senderId).ticker }}]
                {{ player(message.senderId).name }}
            </li>
            <li
                class="text-left"
                v-else-if="mailbox === 'in' && !message.senderId"
            >
                {{ $t("messages.systemSender") }}
            </li>

            <li class="text-left" v-if="mailbox === 'out'">
                {{ $t("messages.details.sentAt") }}
            </li>
            <li class="text-left" v-if="mailbox === 'in'">
                {{ $t("messages.details.recievedAt") }}
            </li>
            <li class="text-left">{{ timestampFormatted }}</li>

            <li class="text-left">
                {{
                    $tc(
                        "messages.details.recipients",
                        message.recipientIds.length
                    )
                }}
            </li>
            <li class="text-left">
                <span
                    v-for="(recipient, index) in message.recipientIds"
                    :key="recipient"
                >
                    <span v-if="recipient === yourId">{{
                        $t("messages.details.you", { ticker: you })
                    }}</span
                    ><span v-else>[{{ player(recipient).ticker }}]</span
                    ><span v-if="index !== message.recipientIds.length - 1"
                        >,
                    </span>
                </span>
            </li>

            <li class="stats--two-col text-left featured">
                {{ $t("messages.details.subject") }}:
            </li>
            <li class="stats--two-col text-left">
                {{ message.subject }}
            </li>

            <li class="stats--two-col text-left featured">
                {{ $t("messages.details.body") }}:
            </li>
            <li
                class="stats--two-col text-left"
                v-html="formatMessageBody(message.body)"
            />
        </ul>

        <message-details-modal-replies-to
            v-if="repliesToMessage && repliesToMessage.id"
            :message-id="repliesToMessage.id"
            :mailbox="mailbox"
            :sender="`[${player(repliesToMessage.senderId).ticker}] ${
                player(repliesToMessage.senderId).name
            }`"
            :sent-at="
                formatDateTime(
                    addSeconds(new Date(), repliesToMessage.timestamp)
                )
            "
            :recipient-ids="repliesToMessage.recipientIds"
            :subject="repliesToMessage.subject"
            :body="formatMessageBody(repliesToMessage.body)"
        />
    </modal>
</template>

<style lang="scss" scoped>
.stats {
    margin-bottom: 0;

    &.base-message {
        margin-bottom: 16px;
    }
}

.you {
    @include themed() {
        color: t("b-christine");
    }
}

.actions {
    display: flex;
    flex-wrap: wrap;

    margin-bottom: 16px;
    gap: 4px;
}
</style>
