<script>
/******************************************************************************
 * PageComponent: ShowFleet
 *****************************************************************************/
import { useStore } from "vuex";
import { computed } from "vue";
import ShowFleetMetaActions from "./Meta/ShowFleetMetaActions";
import ShowFleetMetaStatus from "./Meta/ShowFleetMetaStatus";
export default {
    name: "ShowFleet",
    props: {
        fleetId: String,
    },
    components: { ShowFleetMetaActions, ShowFleetMetaStatus },
    setup(props) {
        const store = useStore();
        const fleet = computed(() =>
            store.getters["fleets/fleetById"](props.fleetId)
        );
        const ships = computed(() =>
            store.getters["fleets/shipsByFleetId"](props.fleetId)
        );
        return {
            fleet,
            ships,
        };
    },
};
</script>

<template>
    <div class="fleet">
        <div class="fleet-meta">
            <show-fleet-meta-status :fleet-id="fleetId" />
            <show-fleet-meta-actions :fleet-id="fleetId" />
        </div>
        {{ fleet }}
        <div>{{ ships }}</div>
    </div>
</template>

<style lang="scss" scoped>
.fleet-meta {
    display: flex;
    flex-direction: column;
    flex-wrap: wrap;

    @include respond-to("medium") {
        flex-direction: row;
    }
}
</style>
