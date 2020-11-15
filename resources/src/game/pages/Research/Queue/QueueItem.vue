<script>
/******************************************************************************
 * PageComponent: QueueItem
 *****************************************************************************/
import { useStore } from "vuex";
import { computed, ref } from "vue";
import Icon from "Components/Icon/Icon";
import LinearProgress from "Components/Progress/LinearProgress";
import GameButton from "Components/Button/GameButton";
import DeleteQueueItem from "./DeleteQueueItem";
export default {
    name: "QueueItem",
    props: {
        jobId: String,
    },
    components: { Icon, LinearProgress, GameButton, DeleteQueueItem },
    setup(props) {
        const store = useStore();
        const showModal = ref(false);
        const job = computed(() =>
            store.getters["research/researchJobById"](props.jobId)
        );
        const researchCosts = computed(
            () =>
                window.rules.tech.areas[job.value.type].costs[
                    job.value.level - 1
                ]
        );
        return {
            job,
            researchCosts,
            showModal,
        };
    },
};
</script>

<template>
    <div class="queue-item">
        <icon name="drag" class="drag" />
        <icon :name="`tech-${job.type}`" class="type" />
        <div class="level" aria-hidden="true">{{ job.level }}</div>
        <linear-progress
            :max="researchCosts"
            :value="job.work"
            :show-text="job.order === 1"
        />
        <game-button icon-name="delete" @click="showModal = true" />
        <delete-queue-item
            :job-id="jobId"
            v-if="showModal"
            @close="showModal = false"
        />
    </div>
</template>

<style lang="scss" scoped>
.queue-item {
    display: flex;
    align-items: center;

    padding: 0.8rem;
    border: 1px solid transparent;
    margin-bottom: 1rem;

    @include themed() {
        background-color: t("g-sunken");
        border-color: t("g-deep");
    }

    @include respond-to("medium") {
        padding: 1.6rem;
    }

    &:last-child {
        margin-bottom: 0;
    }

    .icon.drag {
        margin-right: 0.8rem;

        cursor: move;

        @include themed {
            color: t("g-raven");
        }

        @include respond-to("medium") {
            margin-right: 1.6rem;
        }
    }

    .icon.type {
        margin-right: 0.8rem;

        @include respond-to("medium") {
            width: 3.2rem;
            height: 3.2rem;
            margin-right: 1.6rem;
        }
    }

    .level {
        display: flex;
        align-items: center;
        justify-content: center;

        width: 3rem;
        padding: 0.4rem;
        border: 1px solid transparent;
        margin-right: 0.8rem;

        line-height: 1;

        @include themed() {
            background-color: t("g-asher");
            color: t("t-light");
            border-color: t("g-charcoal");
        }

        @include respond-to("medium") {
            padding: 0.8rem;
        }
    }

    .progress {
        margin: 0 0.8rem;
        flex-grow: 1;

        @include respond-to("medium") {
            margin: 0 1.6rem;
        }
    }
}
</style>
