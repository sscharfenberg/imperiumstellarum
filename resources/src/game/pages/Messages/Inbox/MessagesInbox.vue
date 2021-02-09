<script>
/******************************************************************************
 * PageComponent: MessagesInbox
 *****************************************************************************/
import { computed } from "vue";
import { useStore } from "vuex";
export default {
    name: "MessagesInbox",
    setup() {
        const store = useStore();
        const messages = computed(() => store.state.messages.inbox);
        return { messages };
    },
};
</script>

<template>
    <div class="inbox">
        <h1>{{ messages.length }}Inbox</h1>
        {{ messages }}
    </div>
</template>
