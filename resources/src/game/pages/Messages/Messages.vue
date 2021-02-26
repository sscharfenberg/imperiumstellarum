<script>
/******************************************************************************
 * Page: Broadcasts
 *****************************************************************************/
import { useStore } from "vuex";
import { computed, onBeforeMount } from "vue";
import GameHeader from "Components/Header/GameHeader";
import MessagesInbox from "Pages/Messages/Inbox/MessagesInbox";
import MessagesNavigation from "Pages/Messages/MessagesNavigation";
import MessagesNew from "Pages/Messages/New/MessagesNew";
import MessagesOutbox from "Pages/Messages/Outbox/MessagesOutbox";
import MessagesSysbox from "Pages/Messages/Sysbox/MessagesSysbox";
export default {
    name: "PageDiplomacy",
    components: {
        GameHeader,
        MessagesInbox,
        MessagesNavigation,
        MessagesNew,
        MessagesOutbox,
        MessagesSysbox,
    },
    setup() {
        const store = useStore();
        const pageIndex = computed(() => store.state.messages.page);
        onBeforeMount(() => {
            store.dispatch("messages/GET_GAME_DATA");
        });
        return { pageIndex };
    },
};
</script>

<template>
    <game-header area="messages" />
    <messages-navigation />
    <transition name="messagepage" mode="out-in">
        <messages-sysbox v-if="pageIndex === 0" />
        <messages-inbox v-else-if="pageIndex === 1" />
        <messages-outbox v-else-if="pageIndex === 2" />
        <messages-new v-else-if="pageIndex === 3" />
    </transition>
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
