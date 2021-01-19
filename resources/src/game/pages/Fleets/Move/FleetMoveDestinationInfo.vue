<script>
/******************************************************************************
 * PageComponent: FleetMoveDestinationInfo
 *****************************************************************************/
import { computed } from "vue";
import { useStore } from "vuex";
export default {
    name: "FleetMoveModal",
    setup() {
        const store = useStore();
        const destinationStar = computed(
            () => store.state.fleets.destinationStar
        );
        const destinationOwner = computed(
            () => store.state.fleets.destinationOwner
        );
        return { destinationStar, destinationOwner };
    },
};
</script>

<template>
    <div>{{ destinationStar }}</div>
    <div>{{ destinationOwner }}</div>
</template>
