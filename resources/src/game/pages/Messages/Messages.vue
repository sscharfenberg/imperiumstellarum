<script>
/******************************************************************************
 * Page: Broadcasts
 *****************************************************************************/
import { useStore } from "vuex";
import { ref, computed, onBeforeMount } from "vue";
import GameHeader from "Components/Header/GameHeader";
import MailboxMassDeleteModal from "Pages/Messages/Mailbox/MailboxMassDeleteModal";
import MessagesInbox from "Pages/Messages/Inbox/MessagesInbox";
import MessagesNavigation from "Pages/Messages/MessagesNavigation";
import MessagesNew from "Pages/Messages/New/MessagesNew";
import MessagesOutbox from "Pages/Messages/Outbox/MessagesOutbox";
import MessagesSysbox from "Pages/Messages/Sysbox/MessagesSysbox";
export default {
    name: "PageDiplomacy",
    components: {
        GameHeader,
        MailboxMassDeleteModal,
        MessagesInbox,
        MessagesNavigation,
        MessagesNew,
        MessagesOutbox,
        MessagesSysbox,
    },
    setup() {
        const store = useStore();
        const showMassDeleteModal = ref(false);
        const pageIndex = computed(() => store.state.messages.page);
        const dead = computed(() => store.state.dead);
        onBeforeMount(() => {
            store.dispatch("messages/GET_GAME_DATA");
        });
        return { pageIndex, showMassDeleteModal, dead };
    },
};
</script>

<template>
    <game-header area="messages" />
    <messages-navigation />
    <transition name="messagepage" mode="out-in">
        <messages-sysbox
            v-if="pageIndex === 0"
            @mass-delete="showMassDeleteModal = true"
        />
        <messages-inbox
            v-else-if="pageIndex === 1"
            @mass-delete="showMassDeleteModal = true"
        />
        <messages-outbox
            v-else-if="pageIndex === 2"
            @mass-delete="showMassDeleteModal = true"
        />
        <messages-new v-else-if="pageIndex === 3 && !dead" />
    </transition>
    <mailbox-mass-delete-modal
        v-if="showMassDeleteModal"
        @close="showMassDeleteModal = false"
    />
</template>

<style lang="scss" scoped>
.messagepage-enter-active,
.messagepage-leave-active {
    transition: all map-get($animation-speeds, "fast") ease;
}
.messagepage-leave-to {
    opacity: 0;

    transform: translateY(-50px);
}
.messagepage-enter-from {
    opacity: 0;

    transform: translateY(50px);
}
</style>
