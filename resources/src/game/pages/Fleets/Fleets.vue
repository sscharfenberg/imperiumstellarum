<script>
/******************************************************************************
 * Page: Fleets
 *****************************************************************************/
import { useStore } from "vuex";
import { computed, onBeforeMount, ref } from "vue";
import GameHeader from "Components/Header/GameHeader";
import AreaSection from "Components/AreaSection/AreaSection";
import NewFleetModal from "./New/NewFleetModal";
import ListShipHolders from "./ListShipHolders/ListShipHolders";
import FleetsSummary from "./FleetsSummary";
import GameButton from "Components/Button/GameButton";
export default {
    name: "PageFleets",
    components: {
        GameHeader,
        AreaSection,
        GameButton,
        NewFleetModal,
        FleetsSummary,
        ListShipHolders,
    },
    setup() {
        const store = useStore();
        const showCreate = ref(false);
        const fleets = computed(() => store.state.fleets.fleets);
        const shipyards = computed(() => store.state.fleets.shipyards);
        const maxFleets = computed(() => store.state.fleets.maxFleets);
        const ships = computed(() => store.state.fleets.ships);
        const requesting = computed(() => store.state.fleets.requesting);
        const showCreateBtn = computed(() => {
            return (
                maxFleets.value &&
                fleets.value.length < maxFleets.value &&
                !showCreate.value
            );
        });
        onBeforeMount(() => {
            store.dispatch("fleets/GET_GAME_DATA");
        });
        return {
            fleets,
            shipyards,
            maxFleets,
            ships,
            showCreate,
            showCreateBtn,
            requesting,
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
    <new-fleet-modal v-if="showCreate" @close="showCreate = false" />
    <area-section
        v-if="fleets.length"
        :headline="$t('fleets.active.headline')"
        :requesting="requesting"
    >
        <template v-slot:aside>
            <game-button
                v-if="fleets.length < maxFleets"
                icon-name="add"
                :text-string="$t('fleets.new.btnNewFleet')"
                @click="showCreate = true"
            />
        </template>
        <list-ship-holders />
    </area-section>
</template>
