<script>
/******************************************************************************
 * PageComponent: MessagesInbox
 *****************************************************************************/
import { computed, onBeforeMount, onBeforeUnmount } from "vue";
import { useStore } from "vuex";
import AreaSection from "Components/AreaSection/AreaSection";
import GameButton from "Components/Button/GameButton";
import MailboxOverview from "../Mailbox/MailboxOverview";
import Popover from "Components/Popover/Popover";
export default {
    name: "MessagesInbox",
    components: { AreaSection, GameButton, MailboxOverview, Popover },
    emits: ["mass-delete"],
    setup(props, { emit }) {
        const store = useStore();
        const messages = computed(() =>
            store.state.messages.inbox.filter((m) => !!m.senderId)
        );
        const requesting = computed(() => store.state.messages.requesting);
        const massDeleteMessageIds = computed(
            () => store.state.messages.massDeleteIds
        );
        const onMassDelete = () => emit("mass-delete");
        onBeforeMount(() =>
            store.commit("messages/SET_MASS_DELETE_MAILBOX", "in")
        );
        onBeforeUnmount(() =>
            store.commit("messages/SET_MASS_DELETE_MAILBOX", "")
        );
        return { messages, requesting, massDeleteMessageIds, onMassDelete };
    },
};
</script>

<template>
    <area-section
        :requesting="requesting"
        :headline="$t('messages.inbox.title')"
    >
        <template v-slot:aside>
            <game-button
                class="mass-delete"
                v-if="messages.length && massDeleteMessageIds.length"
                icon-name="delete"
                :text-string="
                    $tc(
                        'messages.mailbox.massDelete',
                        massDeleteMessageIds.length,
                        {
                            num: massDeleteMessageIds.length,
                        }
                    )
                "
                @click="onMassDelete"
            />
            <popover align="right">
                {{ $t("messages.inbox.explanation") }}
            </popover>
        </template>
        <mailbox-overview :messages="messages" mailbox="in" />
    </area-section>
</template>
