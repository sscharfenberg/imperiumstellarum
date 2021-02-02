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
import Popover from "Components/Popover/Popover";
export default {
    name: "PageResearch",
    components: {
        GameHeader,
        AreaSection,
        ResearchPriority,
        ListQueue,
        ListTechAreas,
        Popover,
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
        <template v-slot:aside>
            <popover align="right">
                {{ $t("research.explanation") }}
            </popover>
        </template>
        <research-priority />
    </area-section>
    <area-section
        v-if="empireResearchPriority && numResearchJobs"
        :headline="`${$t(
            'research.queue.hdl'
        )} (${numResearchJobs}/${maxResearchJobs})`"
        :requesting="requesting"
    >
        <template v-slot:aside>
            <popover align="right">
                {{ $t("research.queue.explanation") }}
            </popover>
        </template>
        <list-queue />
    </area-section>
    <area-section
        v-if="empireResearchPriority"
        :headline="$t('research.tl.hdl')"
        :requesting="requesting"
    >
        <template v-slot:aside>
            <popover align="right">
                {{ $t("research.tl.explanation") }}
            </popover>
        </template>
        <list-tech-areas />
    </area-section>
</template>
