<script>
/******************************************************************************
 * PageComponent: MessagesInbox
 *****************************************************************************/
import { computed } from "vue";
import { useStore } from "vuex";
import AreaSection from "Components/AreaSection/AreaSection";
import MailboxOverview from "../Mailbox/MailboxOverview";
export default {
    name: "MessagesInbox",
    components: { AreaSection, MailboxOverview },
    setup() {
        const store = useStore();
        const messages = computed(() =>
            store.state.messages.inbox.filter((m) => !!m.senderId)
        );
        const requesting = computed(() => store.state.messages.requesting);
        return { messages, requesting };
    },
};
</script>

<template>
    <area-section
        :requesting="requesting"
        :headline="$t('messages.inbox.title')"
    >
        <mailbox-overview :messages="messages" mailbox="in" />
    </area-section>
</template>
