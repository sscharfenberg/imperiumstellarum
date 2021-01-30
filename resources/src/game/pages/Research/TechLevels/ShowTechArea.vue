<script>
/******************************************************************************
 * PageComponent: ShowTechArea
 *****************************************************************************/
import { useStore } from "vuex";
import { computed, ref } from "vue";
import GameButton from "Components/Button/GameButton";
import Icon from "Components/Icon/Icon";
import ListTechLevels from "./ListTechLevels";
import ResearchTechLevel from "./ResearchTechLevel";
export default {
    name: "ShowTechArea",
    props: {
        techLevelId: String,
    },
    components: { Icon, GameButton, ListTechLevels, ResearchTechLevel },
    setup(props) {
        const store = useStore();
        const showModal = ref(false);
        const tl = computed(() =>
            store.getters["research/techLevelById"](props.techLevelId)
        );
        const researchJobs = computed(() =>
            store.getters["research/researchJobsByType"](tl.value.type)
        );
        const rules = window.rules.tech;
        const nextLevel = computed(() => {
            let current = tl.value.level;
            if (researchJobs.value.length) {
                current = Math.max(
                    ...researchJobs.value.map((job) => job.level)
                );
            }
            return current + 1;
        });
        const isQueueMaxed = computed(
            () =>
                store.getters["research/researchJobsOrdered"].length ===
                window.rules.tech.queue
        );
        return {
            rules,
            tl,
            nextLevel,
            isQueueMaxed,
            showModal,
        };
    },
};
</script>

<template>
    <li class="tech-area" :class="{ finished: nextLevel > rules.bounds.max }">
        <icon :name="`tech-${tl.type}`" :size="4" />
        <section class="overview">
            <h4>{{ $t("research.tl." + tl.type) }}</h4>
            <game-button
                v-if="nextLevel < rules.bounds.max && !isQueueMaxed"
                :text-string="
                    $t('research.tl.researchBtn', { level: nextLevel })
                "
                @click="showModal = true"
            />
            <list-tech-levels :current="tl.level" :type="tl.type" />
        </section>
        <research-tech-level
            :level="nextLevel"
            :type="tl.type"
            v-if="showModal"
            @close="showModal = false"
        />
    </li>
</template>

<style lang="scss" scoped>
.tech-area {
    display: flex;
    //align-items: flex-start;

    padding: 80px;
    border: 1px solid transparent;
    margin-bottom: 8px;

    @include respond-to("medium") {
        padding: 16px;
        margin-bottom: 16px;
    }

    @include themed() {
        background: t("g-sunken");
    }

    &.finished {
        @include themed() {
            border-color: t("g-raven");
        }
    }

    .icon {
        height: 32px;
        flex: 0 0 32px;

        @include respond-to("medium") {
            height: 64px;
            flex: 0 0 64px;
        }
    }
}
.overview {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    flex-wrap: wrap;

    margin-left: 8px;
    flex-grow: 1;

    @include respond-to("medium") {
        margin-left: 16px;
    }

    > h4 {
        margin: 0;

        font-weight: 300;

        @include respond-to("medium") {
            font-size: 22px;
        }
    }
}
</style>
