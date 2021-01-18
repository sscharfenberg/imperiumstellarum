<script>
/******************************************************************************
 * PageComponent: FleetMoveAnySystem
 *****************************************************************************/
import { computed } from "vue";
import { useStore } from "vuex";
export default {
    name: "FleetMoveOtherSystem",
    props: {
        fleetId: String,
    },
    setup(props) {
        const store = useStore();
        const fleet = computed(() =>
            store.getters["fleets/fleetById"](props.fleetId)
        );
        return { fleet };
    },
};
</script>

<template>{{ fleet }}</template>
