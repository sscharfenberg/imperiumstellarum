<script>
/******************************************************************************
 * Page: Fleets
 *****************************************************************************/
import { useStore } from "vuex";
import { computed, onBeforeMount } from "vue";
import GameHeader from "Components/Header/GameHeader";
export default {
    name: "PageFleets",
    components: { GameHeader },
    setup() {
        const store = useStore();
        const fleets = computed(() => store.state.fleets.fleets);
        const shipyards = computed(() => store.state.fleets.shipyards);
        onBeforeMount(() => {
            store.dispatch("fleets/GET_GAME_DATA");
        });
        return {
            fleets,
            shipyards,
        };
    },
};
</script>

<template>
    <game-header area="fleets" />
    <h1>Active Fleets</h1>
    <div>{{ fleets }}</div>
    <h1>Shipyard Fleets</h1>
    <div>{{ shipyards }}</div>
</template>
