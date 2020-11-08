<script>
/******************************************************************************
 * PageComponent: ShipyardSlot
 *****************************************************************************/
import { useStore } from "vuex";
import { computed } from "vue";
import InstallShipyard from "./new/InstallShipyard";
import ShowShipyard from "./existing/ShowShipyard";
export default {
    name: "ShipyardSlot",
    props: {
        planetId: String,
    },
    components: { InstallShipyard, ShowShipyard },
    setup(props) {
        const store = useStore();
        const shipyard = computed(() =>
            store.getters["empire/shipyardByPlanetId"](props.planetId)
        );
        return {
            shipyard,
        };
    },
};
</script>

<template>
    <install-shipyard v-if="!shipyard" :planet-id="planetId" />
    <show-shipyard v-if="shipyard" :planet-id="planetId" />
</template>
