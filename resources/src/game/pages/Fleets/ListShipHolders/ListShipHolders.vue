<script>
/******************************************************************************
 * PageComponent: ListShipHolders
 *****************************************************************************/
import { useStore } from "vuex";
import { computed } from "vue";
import CollapsibleItem from "Components/Collapsible/CollapsibleItem";
import ShowShipHolder from "../FleetDetails/ShowShipHolder";
import ShowShipHolderLocation from "./ShowShipHolderLocation";
import Icon from "Components/Icon/Icon";
export default {
    name: "ListShipHolders",
    components: {
        CollapsibleItem,
        ShowShipHolder,
        ShowShipHolderLocation,
        Icon,
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
        return {
            allShipHolders,
            max,
        };
    },
};
</script>

<template>
    <collapsible-item
        v-for="holder in allShipHolders"
        :key="holder.id"
        :expanded="true"
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
        </template>
        <template v-slot:aside>
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
    font-size: 20px;
    font-weight: 300;
    line-height: 1;

    @include themed() {
        color: t("b-viking");
    }
}
</style>
