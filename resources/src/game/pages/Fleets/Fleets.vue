<script>
/******************************************************************************
 * Page: Fleets
 *****************************************************************************/
import { useStore } from "vuex";
import { computed, onBeforeMount, ref } from "vue";
import AreaSection from "Components/AreaSection/AreaSection";
import FleetsSummary from "./FleetsSummary";
import GameButton from "Components/Button/GameButton";
import GameHeader from "Components/Header/GameHeader";
import ListShipHolders from "./ListShipHolders/ListShipHolders";
import NewFleetModal from "./New/NewFleetModal";
export default {
    name: "PageFleets",
    components: {
        AreaSection,
        FleetsSummary,
        GameButton,
        GameHeader,
        ListShipHolders,
        NewFleetModal,
    },
    setup() {
        const store = useStore();
        const showCreate = ref(false);
        const fleets = computed(() => store.state.fleets.fleets);
        const shipyards = computed(() => store.state.fleets.shipyards);
        const maxFleets = computed(() => store.state.fleets.maxFleets);
        const ships = computed(() => store.state.fleets.ships);
        const requesting = computed(() => store.state.fleets.requesting);
        const shipView = computed(() => store.state.fleets.shipView);
        const showCreateBtn = computed(() => {
            return (
                maxFleets.value &&
                fleets.value.length < maxFleets.value &&
                !showCreate.value
            );
        });
        const setShipView = (val) => {
            store.commit("fleets/SET_SHIP_VIEW", val);
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
            shipView,
            setShipView,
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
        v-if="fleets.length || shipyards.length"
        :headline="$t('fleets.active.headline')"
        :requesting="requesting"
    >
        <template v-slot:aside>
            <game-button
                class="change-view"
                :class="{ active: shipView === 0 }"
                icon-name="view_detailed"
                @click="setShipView(0)"
                :title="$t('fleets.view.detailed')"
                :aria-label="$t('fleets.view.detailed')"
            />
            <game-button
                class="change-view"
                :class="{ active: shipView === 1 }"
                icon-name="view_collapsed"
                @click="setShipView(1)"
                :title="$t('fleets.view.collapsed')"
                :aria-label="$t('fleets.view.collapsed')"
            />
            <game-button
                class="add"
                v-if="fleets.length < maxFleets"
                icon-name="add"
                :text-string="$t('fleets.new.btnNewFleet')"
                @click="showCreate = true"
            />
        </template>
        <list-ship-holders />
    </area-section>
</template>

<style lang="scss" scoped>
.change-view {
    border-radius: 0;

    &:first-of-type {
        margin-right: 4px;
    }

    &.active {
        @include themed() {
            background-color: t("g-ebony");
            color: t("b-viking");
            border-color: t("b-christine");
        }
    }
}
.add {
    margin-left: 8px;
}
</style>
