<script>
/******************************************************************************
 * PageComponent: MessageDetailsModalActions
 *****************************************************************************/
import { computed } from "vue";
import { useStore } from "vuex";
import GameButton from "Components/Button/GameButton";
export default {
    name: "MessageDetailsModalActions",
    props: {
        messageId: String,
        mailbox: String, // "in" || "out"
        read: Boolean,
    },
    components: {
        GameButton,
    },
    emits: ["close", "report"],
    setup(props, { emit }) {
        const store = useStore();
        const requesting = computed(() => store.state.messages.requesting);
        const message = computed(() =>
            store.getters["messages/anyMessageById"](props.messageId)
        );

        const hasReport = computed(
            () => !!store.getters["messages/messageReport"](props.messageId).id
        );

        /**
         * @function click on "mark as unread": action with xhr to server, close modal
         */
        const onMarkUnreadClick = () => {
            store.dispatch("messages/MARK_MESSAGE_READ", {
                messageId: props.messageId,
                read: false,
            });
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

        /**
         * @function click on "delete message"
         */
        const onDeleteClick = () => {
            store.dispatch("messages/DELETE_MESSAGES", {
                mailbox: props.mailbox,
                messageIds: [props.messageId],
            });
            emit("close");
        };

        const onReportClick = () => {
            store.commit("messages/SET_REPORT_MESSAGE_ID", props.messageId);
            emit("close");
        };

        return {
            requesting,
            message,
            hasReport,
            onMarkUnreadClick,
            onreplyClick,
            onReplyAllClick,
            onDeleteClick,
            onReportClick,
        };
    },
};
</script>

<template>
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
        <game-button
            v-if="mailbox === 'in'"
            :text-string="$t('messages.details.report')"
            icon-name="warning"
            :loading="requesting"
            :disabled="requesting || hasReport"
            @click="onReportClick"
        />
    </div>
</template>

<style lang="scss" scoped>
.actions {
    display: flex;
    flex-wrap: wrap;

    margin-bottom: 16px;
    gap: 4px;
}
</style>
