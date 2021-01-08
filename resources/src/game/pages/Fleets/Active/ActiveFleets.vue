<script>
/******************************************************************************
 * PageComponent: ActiveFleets
 *****************************************************************************/
import { useStore } from "vuex";
import { computed } from "vue";
import AreaSection from "Components/AreaSection/AreaSection";
import CollapsibleItem from "Components/Collapsible/CollapsibleItem";
import ShowFleet from "./ShowFleet";
import ShowFleetLocation from "./ShowFleetLocation";
export default {
    name: "ActiveFleets",
    components: { AreaSection, CollapsibleItem, ShowFleet, ShowFleetLocation },
    setup() {
        const store = useStore();
        const fleets = computed(() => store.state.fleets.fleets);
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
            icon-name="fleets"
            :topic="fleet.name"
        >
            <template v-slot:right>
                <show-fleet-location :fleet-id="fleet.id" />
            </template>
            <show-fleet :fleet-id="fleet.id" />
        </collapsible-item>
    </area-section>
</template>
