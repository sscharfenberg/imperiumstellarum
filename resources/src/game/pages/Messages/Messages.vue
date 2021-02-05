<script>
/******************************************************************************
 * Page: Broadcasts
 *****************************************************************************/
import { useStore } from "vuex";
import { computed, onBeforeMount } from "vue";
import AreaSection from "Components/AreaSection/AreaSection";
import GameHeader from "Components/Header/GameHeader";
import MessagesInbox from "Pages/Messages/Inbox/MessagesInbox";
import MessagesNavigation from "./MessagesNavigation";
import MessagesNew from "Pages/Messages/New/MessagesNew";
import MessagesOutbox from "Pages/Messages/Outbox/MessagesOutbox";
export default {
    name: "PageDiplomacy",
    components: {
        AreaSection,
        GameHeader,
        MessagesInbox,
        MessagesNavigation,
        MessagesNew,
        MessagesOutbox,
    },
    setup() {
        const store = useStore();
        const pageIndex = computed(() => store.state.messages.page);
        const requesting = computed(() => store.state.messages.requesting);
        onBeforeMount(() => {
            store.dispatch("messages/GET_GAME_DATA");
        });
        return { pageIndex, requesting };
    },
};
</script>

<template>
    <game-header area="messages" />
    <messages-navigation />
    <transition name="messagepage" mode="out-in">
        <area-section
            v-if="pageIndex === 0"
            :requesting="requesting"
            :headline="$t('messages.inbox.title')"
        >
            <messages-inbox />
        </area-section>
        <area-section
            v-else-if="pageIndex === 1"
            :requesting="requesting"
            :headline="$t('messages.outbox.title')"
        >
            <messages-outbox />
        </area-section>
        <area-section
            v-else-if="pageIndex === 2"
            :requesting="requesting"
            :headline="$t('messages.new.title')"
        >
            <messages-new />
        </area-section>
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
