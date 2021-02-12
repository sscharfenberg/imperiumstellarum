<script>
/******************************************************************************
 * PageComponent: MessagesInbox
 *****************************************************************************/
import { computed } from "vue";
import { useStore } from "vuex";
import MailboxOverview from "../Mailbox/MailboxOverview";
export default {
    name: "MessagesInbox",
    components: { MailboxOverview },
    setup() {
        const store = useStore();
        const messages = computed(() => store.state.messages.inbox);
        const onClick = (messageId) => {
            console.log(messageId);
        };
        return { messages, onClick };
    },
};
</script>

<template>
    <mailbox-overview :messages="messages" mailbox="in" />
</template>
