<script>
/******************************************************************************
 * Page: Shipyards
 *****************************************************************************/
import { useStore } from "vuex";
import { computed, onBeforeMount } from "vue";
import GameHeader from "Components/Header/GameHeader";
import ShipyardNavigation from "./ShipyardNavigation";
import DesignIndex from "./Design/DesignIndex";
import BuildIndex from "./Build/BuildIndex";
export default {
    name: "PageShipyards",
    components: { GameHeader, ShipyardNavigation, DesignIndex, BuildIndex },
    setup() {
        const store = useStore();
        const shipyardPage = computed(() => store.state.shipyards.page);
        onBeforeMount(() => {
            store.dispatch("shipyards/GET_GAME_DATA");
        });
        return {
            shipyardPage,
        };
    },
};
</script>

<template>
    <game-header area="shipyards" />
    <shipyard-navigation />
    <design-index v-if="shipyardPage === 0" />
    <build-index v-if="shipyardPage === 1" />
</template>
