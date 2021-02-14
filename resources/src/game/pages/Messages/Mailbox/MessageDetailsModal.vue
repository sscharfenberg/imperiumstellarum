<script>
/******************************************************************************
 * PageComponent: MessageDetailsModal
 *****************************************************************************/
import { computed, onBeforeMount } from "vue";
import { useStore } from "vuex";
import { formatMessageBody } from "@/game/helpers/format";
import GameButton from "Components/Button/GameButton";
import Modal from "Components/Modal/Modal";
export default {
    name: "MessageDetailsModal",
    props: {
        messageId: String,
        mailbox: String, // "in" || "out"
        timestampFormatted: String, // pass as props because otherwise it would differ from whats shown on overview, since the data can be old(er)
        read: Boolean,
    },
    components: { GameButton, Modal },
    emits: ["close"],
    setup(props, { emit }) {
        const store = useStore();
        const requesting = computed(() => store.state.messages.requesting);
        const message = computed(() =>
            props.mailbox === "in"
                ? store.getters["messages/messageById"](props.messageId)
                : store.getters["messages/sentMessageById"](props.messageId)
        );
        const player = (playerId) =>
            store.getters["messages/playerById"](playerId);

        const you = computed(() => `[${store.state.empireTicker}]`);
        const yourId = computed(() => store.state.empireId);

        const onMarkUnreadClick = () => {
            store.dispatch("messages/MARK_MESSAGE_READ", {
                messageId: props.messageId,
                read: false,
            });
            emit("close");
        };

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
            store.commit("messages/SET_PAGE", 2);
            emit("close");
        };

        // before mount, call server and mark as read
        onBeforeMount(() => {
            if (!props.read && props.mailbox === "in") {
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
            formatMessageBody,
            onMarkUnreadClick,
            onreplyClick,
        };
    },
};
</script>

<template>
    <modal :title="message.subject" @close="$emit('close')" :full-size="false">
        <ul class="stats">
            <li class="text-left">{{ $t("messages.details.sender") }}</li>
            <li class="text-left" v-if="mailbox === 'out'">
                {{ $t("messages.details.you", { ticker: you }) }}
            </li>
            <li class="text-left" v-if="mailbox === 'in'">
                [{{ player(message.senderId).ticker }}]
                {{ player(message.senderId).name }}
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
                    :class="{
                        you:
                            recipient === yourId &&
                            message.recipientIds.length > 1,
                    }"
                >
                    [{{ player(recipient).ticker }}]<span
                        v-if="index !== message.recipientIds.length - 1"
                        >,</span
                    >
                </span>
            </li>

            <li class="stats--two-col text-left featured">
                {{ message.subject }}
            </li>

            <li
                class="stats--two-col text-left"
                v-html="formatMessageBody(message.body)"
            />
        </ul>
        <div class="actions" v-if="mailbox === 'in'">
            <game-button
                :text-string="$t('messages.details.reply')"
                icon-name="reply"
                :loading="requesting"
                :disabled="requesting"
                @click="onreplyClick"
            />
            <game-button
                :text-string="$t('messages.details.markUnread')"
                icon-name="markunread"
                :loading="requesting"
                :disabled="requesting"
                @click="onMarkUnreadClick"
            />
        </div>
    </modal>
</template>

<style lang="scss" scoped>
.stats {
    margin-bottom: 0;
}

.you {
    @include themed() {
        color: t("b-christine");
    }
}

.actions {
    display: flex;
    flex-wrap: wrap;

    margin-top: 16px;
    gap: 4px;
}
</style>
