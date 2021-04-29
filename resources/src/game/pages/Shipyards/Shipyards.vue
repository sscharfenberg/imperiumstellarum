<script>
/******************************************************************************
 * Page: Shipyards
 *****************************************************************************/
import { useStore } from "vuex";
import { computed, onBeforeMount } from "vue";
import ConstructIndex from "./Construct/ShipyardsConstruct";
import GameHeader from "Components/Header/GameHeader";
import ShipyardNavigation from "./ShipyardNavigation";
import ShipyardsPlan from "./Plan/ShipyardsPlan";
export default {
    name: "PageShipyards",
    components: {
        GameHeader,
        ShipyardNavigation,
        ShipyardsPlan,
        ConstructIndex,
    },
    setup() {
        const store = useStore();
        const shipyardPage = computed(() => store.state.shipyards.page);
        onBeforeMount(() => {
            store.dispatch("shipyards/GET_GAME_DATA");
        });
        const gameOver = computed(() => store.state.gameEnded);
        return {
            shipyardPage,
            gameOver,
        };
    },
};
</script>

<template>
    <game-header area="shipyards" />
    <shipyard-navigation />
    <shipyards-plan v-if="shipyardPage === 0" />
    <construct-index v-else-if="shipyardPage === 1 && !gameOver" />
</template>
