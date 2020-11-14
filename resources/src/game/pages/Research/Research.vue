<script>
/******************************************************************************
 * Page: Research
 *****************************************************************************/
import { useI18n } from "vue-i18n";
import { useStore } from "vuex";
import { onBeforeMount, computed } from "vue";
import GameHeader from "Components/Header/GameHeader";
import AreaSection from "Components/AreaSection/AreaSection";
import ResearchPriority from "./Priority/ResearchPriority";
import ListQueue from "./Queue/ListQueue";
import ListTechAreas from "./TechLevels/ListTechAreas";
export default {
    name: "PageResearch",
    components: {
        GameHeader,
        AreaSection,
        ResearchPriority,
        ListQueue,
        ListTechAreas,
    },
    setup() {
        const store = useStore();
        const requesting = computed(() => store.state.research.requesting);
        const researchPriority = computed(
            () => store.state.empireResearchPriority
        );
        onBeforeMount(() => {
            store.dispatch("research/GET_GAME_DATA");
        });
        return {
            requesting,
            researchPriority,
            ...useI18n(),
        };
    },
};
</script>

<template>
    <game-header area="research" />
    <area-section
        :headline="t('research.priority.label')"
        :requesting="requesting"
    >
        <research-priority v-if="researchPriority !== 0" />
    </area-section>
    <area-section :headline="t('research.queue.hdl')" :requesting="requesting">
        <list-queue v-if="researchPriority !== 0" />
    </area-section>
    <area-section :headline="t('research.tl.hdl')" :requesting="requesting">
        <list-tech-areas v-if="researchPriority !== 0" />
    </area-section>
</template>
