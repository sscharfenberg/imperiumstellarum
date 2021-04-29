<script>
/******************************************************************************
 * PageComponent: ShipyardsPlan
 *****************************************************************************/
import { useStore } from "vuex";
import { computed } from "vue";
import DesignBlueprint from "./Design/DesignBlueprint";
import ManageBlueprints from "./Manage/ManageBlueprints";
export default {
    name: "ShipyardsPlan",
    components: { DesignBlueprint, ManageBlueprints },
    setup() {
        const store = useStore();
        const shipyards = computed(() => store.state.shipyards.shipyards);
        const blueprints = computed(() => store.state.shipyards.blueprints);
        const gameOver = computed(() => store.state.gameEnded);
        return {
            shipyards,
            blueprints,
            gameOver,
        };
    },
};
</script>

<template>
    <manage-blueprints v-if="blueprints" />
    <design-blueprint v-if="shipyards && !gameOver" />
</template>
