<script>
/******************************************************************************
 * Page: Research
 *****************************************************************************/
import { useStore } from "vuex";
import { onBeforeMount, computed } from "vue";
import AreaSection from "Components/AreaSection/AreaSection";
import GameHeader from "Components/Header/GameHeader";
import ListQueue from "./Queue/ListQueue";
import ListTechAreas from "./TechLevels/ListTechAreas";
import ResearchPriority from "./Priority/ResearchPriority";
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
        const empireResearchPriority = computed(
            () => store.state.empireResearchPriority
        );
        const numResearchJobs = computed(
            () => store.state.research.researchJobs.length
        );
        const maxResearchJobs = window.rules.tech.queue;
        onBeforeMount(() => {
            store.dispatch("research/GET_GAME_DATA");
        });
        return {
            requesting,
            empireResearchPriority,
            numResearchJobs,
            maxResearchJobs,
        };
    },
};
</script>

<template>
    <game-header area="research" />
    <area-section
        v-if="empireResearchPriority"
        :headline="$t('research.priority.label')"
        :requesting="requesting"
    >
        <research-priority />
    </area-section>
    <area-section
        v-if="empireResearchPriority && numResearchJobs"
        :headline="`${$t(
            'research.queue.hdl'
        )} (${numResearchJobs}/${maxResearchJobs})`"
        :requesting="requesting"
    >
        <list-queue />
    </area-section>
    <area-section
        v-if="empireResearchPriority"
        :headline="$t('research.tl.hdl')"
        :requesting="requesting"
    >
        <list-tech-areas />
    </area-section>
</template>
