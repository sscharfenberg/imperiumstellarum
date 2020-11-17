<script>
/******************************************************************************
 * PageComponent: ListQueue
 *****************************************************************************/
import { useStore } from "vuex";
import { computed } from "vue";
import QueueItem from "./QueueItem";
import { VueDraggableNext } from "vue-draggable-next";
export default {
    name: "ListQueue",
    components: { QueueItem, draggable: VueDraggableNext },
    setup() {
        const store = useStore();
        const researchJobs = computed({
            get: () => {
                return store.getters["research/researchJobsOrdered"];
            },
            set: (val) => {
                console.log(val);
                store.dispatch(
                    "research/CHANGE_RESEARCH_JOB_ORDER",
                    val.map((job) => job.id)
                );
            },
        });
        const isRequesting = computed(() => store.state.research.requesting);
        return {
            researchJobs,
            isRequesting
        };
    },
    methods: {
        // prevent dragImage while dragging
        setData: function (dataTransfer) {
            const img = document.createElement("img");
            dataTransfer.setDragImage(img, 0, 0); // `dataTransfer` object of HTML5 DragEvent
        },
    },
};
</script>

<template>
    <draggable
        v-model="researchJobs"
        ghost-class="ghost"
        drag-class="drag"
        v-if="researchJobs.length > 0"
        :set-data="setData"
        :disabled="isRequesting"
        :aria-disabled="isRequesting"
    >
        <transition-group type="transition" name="flip-list">
            <queue-item
                v-for="job in researchJobs"
                :key="job.id"
                :job-id="job.id"
            />
        </transition-group>
    </draggable>
</template>

<style lang="scss" scoped>
.ghost {
    @include themed() {
        background: t("g-ebony");
    }
}
.flip-list-move {
    transition: transform 0.2s;
}
</style>
