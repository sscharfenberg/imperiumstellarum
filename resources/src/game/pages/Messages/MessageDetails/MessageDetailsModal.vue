<script>
/******************************************************************************
 * PageComponent: MessageDetailsModal
 *****************************************************************************/
import { computed, onBeforeMount } from "vue";
import { useStore } from "vuex";
import { addSeconds } from "date-fns";
import { formatMessageBody, formatDateTime } from "@/game/helpers/format";
import MessageDetailsModalActions from "./MessageDetailsModalActions";
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
        MessageDetailsModalActions,
        MessageDetailsModalRepliesTo,
        Modal,
    },
    emits: ["close", "report"],
    setup(props) {
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
            store.commit("messages/SET_REPORT_MESSAGE_ID", "");
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
        };
    },
};
</script>

<template>
    <modal :title="message.subject" @close="$emit('close')" :full-size="false">
        <message-details-modal-actions
            :message-id="messageId"
            :mailbox="mailbox"
            :read="read"
            @close="$emit('close')"
            @report="$emit('report')"
        />
        <ul class="stats base-message">
            <li class="text-left">{{ $t("messages.details.sender") }}</li>
            <li class="text-left" v-if="mailbox === 'out'">
                {{ $t("messages.details.you", { ticker: you }) }}
            </li>
            <li
                class="text-left"
                v-else-if="
                    (mailbox === 'in' || mailbox === 'sys') && message.senderId
                "
            >
                [{{ player(message.senderId).ticker }}]
                {{ player(message.senderId).name }}
            </li>
            <li
                class="text-left"
                v-else-if="
                    (mailbox === 'in' || mailbox === 'sys') && !message.senderId
                "
            >
                {{ $t("messages.systemSender") }}
            </li>

            <li class="text-left" v-if="mailbox === 'out'">
                {{ $t("messages.details.sentAt") }}
            </li>
            <li class="text-left" v-if="mailbox === 'in' || mailbox === 'sys'">
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
