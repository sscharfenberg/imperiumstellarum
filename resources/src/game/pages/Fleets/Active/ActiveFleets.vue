<script>
/******************************************************************************
 * PageComponent: ActiveFleets
 *****************************************************************************/
import { useStore } from "vuex";
import { computed } from "vue";
import AreaSection from "Components/AreaSection/AreaSection";
import CollapsibleItem from "Components/Collapsible/CollapsibleItem";
import ShowFleet from "./FleetDetails/ShowFleet";
import ShowFleetLocation from "./ShowFleetLocation";
import Icon from "Components/Icon/Icon";
export default {
    name: "ActiveFleets",
    components: {
        AreaSection,
        CollapsibleItem,
        ShowFleet,
        ShowFleetLocation,
        Icon,
    },
    setup() {
        const store = useStore();
        //const fleets = computed(() => store.state.fleets.fleets);
        const fleets = computed(
            () => store.getters["fleets/fleetsSortedByName"]
        );
        const max = computed(() => store.state.fleets.maxFleets);
        return {
            fleets,
            max,
        };
    },
};
</script>

<template>
    <area-section
        :headline="$t('fleets.active.headline', { num: fleets.length, max })"
    >
        <collapsible-item
            v-for="fleet in fleets"
            :key="fleet.id"
            :expanded="true"
        >
            <template v-slot:topic>
                <icon class="topic-icon" name="fleets" />
                <span class="topic-title">{{ fleet.name }}</span>
            </template>
            <template v-slot:aside>
                <show-fleet-location :fleet-id="fleet.id" />
            </template>
            <show-fleet :fleet-id="fleet.id" />
        </collapsible-item>
    </area-section>
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
