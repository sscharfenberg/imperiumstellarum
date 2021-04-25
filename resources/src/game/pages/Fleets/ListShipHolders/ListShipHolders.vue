<script>
/******************************************************************************
 * PageComponent: ListShipHolders
 *****************************************************************************/
import { useStore } from "vuex";
import { computed } from "vue";
import CollapsibleItem from "Components/Collapsible/CollapsibleItem";
import FleetShipSummary from "Components/Fleet/FleetShipSummary";
import Icon from "Components/Icon/Icon";
import ShowShipHolder from "../FleetDetails/ShowShipHolder";
import ShowShipHolderLocation from "./ShowShipHolderLocation";
import ShowFleetRange from "Pages/Fleets/ListShipHolders/ShowFleetRange";
import ShowShipyardStatus from "./ShowShipyardStatus";
export default {
    name: "ListShipHolders",
    components: {
        CollapsibleItem,
        FleetShipSummary,
        Icon,
        ShowShipHolder,
        ShowShipHolderLocation,
        ShowFleetRange,
        ShowShipyardStatus,
    },
    setup() {
        const store = useStore();
        const fleets = computed(
            () => store.getters["fleets/fleetsSortedByName"]
        );
        const shipyards = computed(() => store.state.fleets.shipyards);
        const allShipHolders = computed(() => [
            ...fleets.value,
            ...shipyards.value,
        ]);
        const max = computed(() => store.state.fleets.maxFleets);
        const holderShips = (holderId) => {
            const fleetShips = store.getters["fleets/shipsByFleetId"](holderId);
            const shipyardShips = store.getters["fleets/shipsByShipyardId"](
                holderId
            );
            return fleetShips.length ? fleetShips : shipyardShips;
        };
        return {
            allShipHolders,
            holderShips,
            max,
        };
    },
};
</script>

<template>
    <collapsible-item
        v-for="holder in allShipHolders"
        :key="holder.id"
        :collapsible-id="holder.id"
    >
        <template v-slot:topic>
            <icon v-if="holder.name" class="topic-icon" name="fleets" />
            <icon
                v-if="holder.planetName"
                class="topic-icon"
                name="shipyards"
            />
            <span class="topic-title">{{
                holder.name ? holder.name : holder.planetName
            }}</span>
            <fleet-ship-summary
                v-if="holderShips(holder.id).length"
                :ships="holderShips(holder.id)"
            />
        </template>
        <template v-slot:aside>
            <show-shipyard-status
                v-if="holder.planetName"
                :shipyard-id="holder.id"
            />
            <show-fleet-range
                v-if="holder.preferredRange && holderShips(holder.id).length"
                :range="holder.preferredRange"
            />
            <show-ship-holder-location :holder-id="holder.id" />
        </template>
        <show-ship-holder :holder-id="holder.id" />
    </collapsible-item>
</template>

<style lang="scss" scoped>
.topic-icon {
    margin: 0 16px 0 8px;

    @include themed() {
        color: t("b-christine");
    }
}

.topic-title {
    overflow: hidden;

    font-size: 20px;
    font-weight: 300;
    line-height: 1;
    white-space: nowrap;
    text-overflow: ellipsis;

    @include themed() {
        color: t("b-viking");
    }
}

.summary {
    padding-left: 8px;

    @include respond-to("medium") {
        padding-left: 16px;
    }
}

.range {
    padding: 5px;

    line-height: 1;

    @include themed() {
        background: t("g-bunker");
    }
}
</style>
