<script>
/******************************************************************************
 * PageComponent: DeleteQueueItem
 *****************************************************************************/
import { useStore } from "vuex";
import { computed } from "vue";
import GameButton from "Components/Button/GameButton";
import Modal from "Components/Modal/Modal";
export default {
    name: "DeleteQueueItem",
    props: {
        jobId: String,
    },
    components: { Modal, GameButton },
    setup(props, { emit }) {
        const store = useStore();
        const job = computed(() =>
            store.getters["research/researchJobById"](props.jobId)
        );
        const onSubmit = () => {
            store.dispatch("research/DELETE_RESEARCH_JOB", { id: props.jobId });
            emit("close");
        };
        return {
            job,
            onSubmit,
        };
    },
};
</script>

<template>
    <modal
        :title="
            $t('research.queue.delete.hdl', {
                type: $t('research.tl.' + job.type),
                level: job.level,
            })
        "
        @close="$emit('close')"
    >
        <ul class="stats">
            <li class="stats--two-col featured stats--padded">
                {{ $t("research.queue.delete.explanation") }}
            </li>
            <li>{{ $t("research.queue.delete.lostWork") }}:</li>
            <li class="stats--centered">{{ job.work }}</li>
        </ul>
        <template v-slot:actions>
            <game-button
                :text-string="$t('research.queue.delete.submit')"
                icon-name="delete"
                :primary="true"
                @click="onSubmit"
            />
        </template>
    </modal>
</template>
