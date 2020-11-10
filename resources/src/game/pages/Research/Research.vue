<script>
/******************************************************************************
 * Page: Research
 *****************************************************************************/
import { useI18n } from "vue-i18n";
import { useStore } from "vuex";
import { onBeforeMount, computed } from "vue";
import GameHeader from "Components/Header/GameHeader";
import AreaSection from "Components/AreaSection/AreaSection";
export default {
    name: "PageResearch",
    components: { GameHeader, AreaSection },
    setup() {
        const store = useStore();
        const requesting = computed(() => store.state.research.requesting);
        onBeforeMount(() => {
            store.dispatch("research/GET_GAME_DATA");
        });
        return {
            requesting,
            ...useI18n(),
        };
    },
};
</script>

<template>
    <game-header area="research" />
    <area-section headline="Research!" :requesting="requesting">
        todo
    </area-section>
</template>
