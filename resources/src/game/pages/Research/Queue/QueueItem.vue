<script>
/******************************************************************************
 * PageComponent: QueueItem
 *****************************************************************************/
import { useStore } from "vuex";
import { computed, ref } from "vue";
import { formatInt } from "@/game/helpers/format";
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
        const isRequesting = computed(() => store.state.research.requesting);
        return {
            job,
            researchCosts,
            showModal,
            isRequesting,
            formatInt,
        };
    },
};
</script>

<template>
    <div class="queue-item">
        <icon name="drag" class="drag" :class="{ disabled: isRequesting }" />
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

    padding: 8px;
    border: 1px solid transparent;
    margin-bottom: 10px;

    @include themed() {
        background-color: t("g-sunken");
        border-color: t("g-deep");
    }

    @include respond-to("medium") {
        padding: 16px;
    }

    &:last-child {
        margin-bottom: 0;
    }

    .icon.drag {
        margin-right: 8px;

        cursor: move;

        @include themed {
            color: t("g-raven");
        }

        @include respond-to("medium") {
            margin-right: 16px;
        }

        &.disabled {
            cursor: not-allowed;
        }
    }

    .icon.type {
        margin-right: 8px;

        @include respond-to("medium") {
            width: 32px;
            height: 32px;
            margin-right: 16px;
        }
    }

    .level {
        display: flex;
        align-items: center;
        justify-content: center;

        width: 30px;
        padding: 4px;
        border: 1px solid transparent;
        margin-right: 8px;

        line-height: 1;

        @include themed() {
            background-color: t("g-asher");
            color: t("t-light");
            border-color: t("g-charcoal");
        }

        @include respond-to("medium") {
            padding: 8px;
        }
    }

    .progress {
        margin: 0 8px;
        flex-grow: 1;

        @include respond-to("medium") {
            margin: 0 16px;
        }
    }
}
</style>
