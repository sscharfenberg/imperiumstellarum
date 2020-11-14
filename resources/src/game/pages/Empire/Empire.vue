<script>
/******************************************************************************
 * Page: Empire
 *****************************************************************************/
import { useI18n } from "vue-i18n";
import { useStore } from "vuex";
import { onBeforeMount, computed } from "vue";
import GameHeader from "Components/Header/GameHeader";
import ListStars from "./Stars/ListStars";
import ShowSummary from "./Summary/ShowSummary";
import AreaSection from "Components/AreaSection/AreaSection";
export default {
    name: "PageEmpire",
    components: { GameHeader, AreaSection, ListStars, ShowSummary },
    setup() {
        const store = useStore();
        const requesting = computed(() => store.state.empire.requesting);
        const numStars = computed(() => store.state.empire.stars.length);
        const researchPriority = computed(
            () => store.state.empireResearchPriority
        );
        onBeforeMount(() => {
            store.dispatch("empire/GET_GAME_DATA");
        });
        return {
            researchPriority,
            requesting,
            numStars,
            ...useI18n(),
        };
    },
};
</script>

<template>
    <game-header area="empire" />
    <area-section
        v-if="researchPriority !== 0"
        :headline="t('empire.summary.title')"
        :requesting="requesting"
    >
        <show-summary />
    </area-section>
    <area-section
        :requesting="requesting"
        :headline="t('empire.stars', { num: numStars })"
    >
        <list-stars />
    </area-section>
</template>
