<script>
/******************************************************************************
 * Page: Fleets
 *****************************************************************************/
import { useStore } from "vuex";
import { computed, onBeforeMount } from "vue";
import GameHeader from "Components/Header/GameHeader";
import NewFleet from "./New/NewFleet";
import ActiveFleets from "./Active/ActiveFleets";
export default {
    name: "PageFleets",
    components: { GameHeader, NewFleet, ActiveFleets },
    setup() {
        const store = useStore();
        const fleets = computed(() => store.state.fleets.fleets);
        const shipyards = computed(() => store.state.fleets.shipyards);
        const maxFleets = computed(() => store.state.fleets.maxFleets);
        onBeforeMount(() => {
            store.dispatch("fleets/GET_GAME_DATA");
        });
        return {
            fleets,
            shipyards,
            maxFleets,
        };
    },
};
</script>

<template>
    <game-header area="fleets" />
    <new-fleet v-if="maxFleets && fleets.length < maxFleets" />
    <active-fleets v-if="maxFleets" :num="fleets.length" :max="maxFleets" />
    {{ fleets }}
    <h1>Shipyard Fleets</h1>
    <div>{{ shipyards }}</div>
</template>
