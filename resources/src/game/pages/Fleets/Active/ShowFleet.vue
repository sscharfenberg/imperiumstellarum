<script>
/******************************************************************************
 * PageComponent: ShowFleet
 *****************************************************************************/
import { useStore } from "vuex";
import { computed } from "vue";
export default {
    name: "ShowFleet",
    props: {
        fleetId: String,
    },
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
    {{ fleet }}
</template>
