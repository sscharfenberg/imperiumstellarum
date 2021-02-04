<script>
/******************************************************************************
 * Page: Broadcasts
 *****************************************************************************/
import { useStore } from "vuex";
import { computed, onBeforeMount } from "vue";
import GameHeader from "Components/Header/GameHeader";
export default {
    name: "PageDiplomacy",
    components: { GameHeader },
    setup() {
        const store = useStore();
        const requesting = computed(() => store.state.messages.requesting);
        onBeforeMount(() => {
            store.dispatch("messages/GET_GAME_DATA");
        });
        return { requesting };
    },
};
</script>

<template>
    <game-header area="messages" />
    {{ requesting }}
    Broadcasts!
</template>
