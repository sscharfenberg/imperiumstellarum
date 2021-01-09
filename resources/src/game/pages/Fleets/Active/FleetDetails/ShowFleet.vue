<script>
/******************************************************************************
 * PageComponent: ShowFleet
 *****************************************************************************/
import { useStore } from "vuex";
import { computed } from "vue";
import ShowFleetMeta from "./Meta/ShowFleetMeta";
export default {
    name: "ShowFleet",
    props: {
        fleetId: String,
    },
    components: { ShowFleetMeta },
    setup(props) {
        const store = useStore();
        const fleet = computed(() =>
            store.getters["fleets/fleetById"](props.fleetId)
        );
        return {
            fleet,
        };
    },
};
</script>

<template>
    <div class="fleet">
        <show-fleet-meta :fleet-id="fleetId" />
        {{ fleet }}
    </div>
</template>
