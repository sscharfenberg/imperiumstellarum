<script>
/******************************************************************************
 * PageComponent: MailboxMassDeleteModal
 *****************************************************************************/
//import { useI18n } from "vue-i18n";
import { computed } from "vue";
import { useStore } from "vuex";
import GameButton from "Components/Button/GameButton";
import Modal from "Components/Modal/Modal";
export default {
    name: "NewFleet",
    components: {
        GameButton,
        Modal,
    },
    emits: ["close"],
    setup(props, { emit }) {
        const store = useStore();
        //const i18n = useI18n();
        const requesting = computed(() => store.state.messages.requesting);
        const messageIds = computed(() => store.state.messages.massDeleteIds);
        const numMessages = computed(() => messageIds.value.length);
        const onSubmit = () => {
            store.dispatch("messages/DELETE_MESSAGES", {
                mailbox: store.state.messages.massDeleteMailbox,
                messageIds: messageIds.value,
            });
            emit("close");
        };
        return {
            requesting,
            numMessages,
            onSubmit,
        };
    },
};
</script>

<template>
    <modal
        :title="$tc('messages.massDelete.title', numMessages)"
        @close="$emit('close')"
    >
        {{
            $tc("messages.massDelete.explanation", numMessages, {
                num: numMessages,
            })
        }}
        <template v-slot:actions>
            <game-button
                :text-string="
                    $tc('messages.massDelete.submit', numMessages, {
                        num: numMessages,
                    })
                "
                icon-name="delete"
                :disabled="false"
                :loading="requesting"
                :primary="true"
                @click="onSubmit"
            />
        </template>
    </modal>
</template>
