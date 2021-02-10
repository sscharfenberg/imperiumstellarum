<script>
/******************************************************************************
 * PageComponent: MessagesOutbox
 *****************************************************************************/
import { computed } from "vue";
import { useStore } from "vuex";
import MailboxOverview from "../Mailbox/MailboxOverview";
export default {
    name: "MessagesOutbox",
    components: { MailboxOverview },
    setup() {
        const store = useStore();
        const messages = computed(() => store.state.messages.outbox);
        const onClick = (messageId) => {
            console.log(messageId);
        };
        return { messages, onClick };
    },
};
</script>

<template>
    <h1>{{ messages.length }} Outbox</h1>
    {{ messages }}
    <mailbox-overview :messages="messages" mailbox="out" />
</template>
