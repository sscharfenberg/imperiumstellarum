<script>
/******************************************************************************
 * Page: Shipyards
 *****************************************************************************/
import { useStore } from "vuex";
import { onBeforeMount } from "vue";
import GameHeader from "Components/Header/GameHeader";
export default {
    name: "PageShipyards",
    components: { GameHeader },
    setup() {
        const store = useStore();
        onBeforeMount(() => {
            store.dispatch("shipyards/GET_GAME_DATA");
        });
        return {};
    },
};
</script>

<template>
    <game-header area="shipyards" />
</template>
