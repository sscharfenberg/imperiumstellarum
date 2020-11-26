<script>
/******************************************************************************
 * Page: Starchart
 *****************************************************************************/
import { useStore } from "vuex";
import { onBeforeMount, computed } from "vue";
import GameHeader from "Components/Header/GameHeader";
import AreaSection from "Components/AreaSection/AreaSection";
import StarMap from "./Map/StarMap";
export default {
    name: "PageStarchart",
    components: {
        GameHeader,
        AreaSection,
        StarMap,
    },
    setup() {
        const store = useStore();
        const requesting = computed(() => store.state.starchart.requesting);
        onBeforeMount(() => {
            store.dispatch("starchart/GET_GAME_DATA");
        });
        return {
            requesting,
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
        <star-map />
    </area-section>
</template>
