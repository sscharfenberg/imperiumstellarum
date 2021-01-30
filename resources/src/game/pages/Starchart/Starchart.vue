<script>
/******************************************************************************
 * Page: Starchart
 *****************************************************************************/
import { useStore } from "vuex";
import { onBeforeMount, computed } from "vue";
import AreaSection from "Components/AreaSection/AreaSection";
import FocussableStars from "./Focus/FocussableStars";
import GameHeader from "Components/Header/GameHeader";
import MapLegend from "./Map/MapLegend";
import MapStage from "./Map/MapStage";
export default {
    name: "PageStarchart",
    components: {
        GameHeader,
        AreaSection,
        FocussableStars,
        MapStage,
        MapLegend,
    },
    setup() {
        const store = useStore();
        const requesting = computed(() => store.state.starchart.requesting);
        const dimensions = computed(() => store.state.starchart.dimensions);
        onBeforeMount(() => {
            store.dispatch("starchart/GET_INITIAL_GAME_DATA");
        });
        return {
            requesting,
            dimensions,
        };
    },
};
</script>

<template>
    <game-header area="starchart" />
    <area-section
        :headline="$t('starchart.map.label')"
        :requesting="requesting"
    >
        <focussable-stars v-if="dimensions" :dimensions="dimensions" />
        <map-stage v-if="dimensions" :dimensions="dimensions" />
        <div id="StarChartModal" />
        <map-legend />
    </area-section>
</template>
