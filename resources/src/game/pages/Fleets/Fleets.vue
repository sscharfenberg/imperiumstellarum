<script>
/******************************************************************************
 * Page: Fleets
 *****************************************************************************/
import { useStore } from "vuex";
import { computed, onBeforeMount, ref } from "vue";
import GameHeader from "Components/Header/GameHeader";
import AreaSection from "Components/AreaSection/AreaSection";
import NewFleet from "./New/NewFleet";
import ListShipHolders from "./ListShipHolders/ListShipHolders";
import FleetsSummary from "./FleetsSummary";
import GameButton from "Components/Button/GameButton";
export default {
    name: "PageFleets",
    components: {
        GameHeader,
        AreaSection,
        GameButton,
        NewFleet,
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
        const showCreateBtn = computed(() => {
            return (
                maxFleets.value &&
                fleets.value.length < maxFleets.value &&
                !showCreate.value
            );
        });
        const onCreateClick = () => {
            store.commit("fleets/SET_SHOW_CREATE", true);
        };

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
            onCreateClick,
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
    <area-section v-if="showCreate" :headline="$t('fleets.new.headline')">
        <new-fleet />
    </area-section>
    <area-section v-if="fleets.length" :headline="$t('fleets.active.headline')">
        <template v-slot:aside>
            <game-button
                v-if="showCreateBtn"
                icon-name="add"
                :text-string="$t('fleets.new.btnNewFleet')"
                @click="showCreate = true"
            />
            <game-button
                v-if="!showCreateBtn"
                icon-name="add"
                :text-string="$t('fleets.new.btnCancel')"
                @click="showCreate = false"
            />
        </template>
        <list-ship-holders />
    </area-section>
</template>
