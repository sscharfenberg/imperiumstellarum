<script>
/******************************************************************************
 * Page: Fleets
 *****************************************************************************/
import { useStore } from "vuex";
import { computed, onBeforeMount } from "vue";
import GameHeader from "Components/Header/GameHeader";
import AreaSection from "Components/AreaSection/AreaSection";
import NewFleet from "./New/NewFleet";
import ListShipHolders from "./ListShipHolders/ListShipHolders";
import FleetsSummary from "./FleetsSummary";
export default {
    name: "PageFleets",
    components: {
        GameHeader,
        AreaSection,
        NewFleet,
        FleetsSummary,
        ListShipHolders,
    },
    setup() {
        const store = useStore();
        const fleets = computed(() => store.state.fleets.fleets);
        const shipyards = computed(() => store.state.fleets.shipyards);
        const maxFleets = computed(() => store.state.fleets.maxFleets);
        const ships = computed(() => store.state.fleets.ships);
        onBeforeMount(() => {
            store.dispatch("fleets/GET_GAME_DATA");
        });
        return {
            fleets,
            shipyards,
            maxFleets,
            ships,
        };
    },
};
</script>

<template>
    <game-header area="fleets" />
    <fleets-summary
        :num-fleets="fleets.length"
        :max-fleets="maxFleets"
        :num-shipyards="shipyards.length"
        :ships="ships"
    />
    <new-fleet v-if="maxFleets && fleets.length < maxFleets" />
    <area-section v-if="fleets.length" :headline="$t('fleets.active.headline')">
        <list-ship-holders />
    </area-section>
</template>
