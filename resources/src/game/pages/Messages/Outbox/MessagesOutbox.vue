<script>
/******************************************************************************
 * PageComponent: MessagesOutbox
 *****************************************************************************/
import { computed } from "vue";
import { useStore } from "vuex";
export default {
    name: "MessagesOutbox",
    setup() {
        const store = useStore();
        const messages = computed(() => store.state.messages.outbox);
        return { messages };
    },
};
</script>

<template>
    <div class="outbox">
        <h1>{{ messages.length }} Outbox</h1>
        {{ messages }}
    </div>
</template>
