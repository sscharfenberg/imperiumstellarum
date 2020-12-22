<script>
/******************************************************************************
 * Page: Shipyards
 *****************************************************************************/
import { useStore } from "vuex";
import { computed, onBeforeMount } from "vue";
import GameHeader from "Components/Header/GameHeader";
import ShipyardNavigation from "./ShipyardNavigation";
import ShipyardsPlan from "./Plan/ShipyardsPlan";
import ConstructIndex from "./Construct/ShipyardsConstruct";
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
        return {
            shipyardPage,
        };
    },
};
</script>

<template>
    <game-header area="shipyards" />
    <shipyard-navigation />
    <shipyards-plan v-if="shipyardPage === 0" />
    <construct-index v-if="shipyardPage === 1" />
</template>
