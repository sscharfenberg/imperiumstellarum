<script>
/******************************************************************************
 * PageComponent: MessageDetailsModal
 *****************************************************************************/
import { computed } from "vue";
import { useStore } from "vuex";
import CollapsibleItem from "Components/Collapsible/CollapsibleItem";
export default {
    name: "MessageDetailsModal",
    props: {
        messageId: String,
        mailbox: String, // "in" || "out"
        sender: String,
        sentAt: String,
        recipientIds: Array,
        subject: String,
        body: String,
    },
    components: { CollapsibleItem },
    setup() {
        const store = useStore();
        const yourTicker = computed(() => `[${store.state.empireTicker}]`);
        const yourId = computed(() => store.state.empireId);
        const player = (playerId) =>
            store.getters["messages/playerById"](playerId);
        return { player, yourId, yourTicker };
    },
};
</script>

<template>
    <collapsible-item :collapsible-id="`repliesTo-${messageId}`" :alt-bg="true">
        <template v-slot:topic>
            {{ $t("messages.details.repliesTo") }}
        </template>
        <ul class="stats">
            <li class="text-left">{{ $t("messages.details.sender") }}</li>
            <li class="text-left">
                {{ sender }}
            </li>

            <li class="text-left" v-if="mailbox === 'in'">
                {{ $t("messages.details.sentAt") }}
            </li>
            <li class="text-left" v-if="mailbox === 'out'">
                {{ $t("messages.details.recievedAt") }}
            </li>
            <li class="text-left">
                {{ sentAt }}
            </li>

            <li class="text-left">
                {{ $tc("messages.details.recipients", recipientIds.length) }}
            </li>
            <li class="text-left">
                <span
                    v-for="(recipient, index) in recipientIds"
                    :key="recipient"
                >
                    <span v-if="recipient === yourId">{{
                        $t("messages.details.you", { ticker: yourTicker })
                    }}</span
                    ><span v-else>[{{ player(recipient).ticker }}]</span
                    ><span v-if="index !== recipientIds.length - 1">,</span>
                </span>
            </li>

            <li class="stats--two-col text-left featured">
                {{ $t("messages.details.subject") }}:
            </li>
            <li class="stats--two-col text-left">
                {{ subject }}
            </li>

            <li class="stats--two-col text-left featured">
                {{ $t("messages.details.body") }}:
            </li>
            <li class="stats--two-col text-left" v-html="body" />
        </ul>
    </collapsible-item>
</template>
