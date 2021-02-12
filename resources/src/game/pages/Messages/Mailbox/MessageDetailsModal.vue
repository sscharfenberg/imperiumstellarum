<script>
/******************************************************************************
 * PageComponent: MessageDetailsModal
 *****************************************************************************/
import { computed, onBeforeMount } from "vue";
import { useStore } from "vuex";
import Modal from "Components/Modal/Modal";
export default {
    name: "MessageDetailsModal",
    props: {
        messageId: String,
        mailbox: String, // "in" || "out"
    },
    components: {
        Modal,
    },
    emits: ["close"],
    setup(props) {
        const store = useStore();
        const message = computed(() =>
            props.mailbox === "in"
                ? store.getters["messages/messageById"](props.messageId)
                : store.getters["messages/sentMessageById"](props.messageId)
        );
        onBeforeMount(() => {
            if (props.mailbox === "in") {
                console.log("xhr call => mark read");
            }
        });
        return { message };
    },
};
</script>

<template>
    <modal title="fuddel" @close="$emit('close')" :full-size="false">
        {{ message }}
        {{ messageId }}
    </modal>
</template>
