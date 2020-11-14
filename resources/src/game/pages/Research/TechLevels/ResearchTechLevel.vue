<script>
/******************************************************************************
 * PageComponent: ResearchTechLevel
 *****************************************************************************/
import { useStore } from "vuex";
import Modal from "Components/Modal/Modal";
import GameButton from "Components/Button/GameButton";
export default {
    name: "ResearchTechLevel",
    props: {
        level: Number,
        type: String,
    },
    components: { Modal, GameButton },
    setup(props, { emit }) {
        const store = useStore();
        const workRequired = () =>
            window.rules.tech.areas[props.type].costs[props.level - 1];
        const onSubmit = () => {
            store.dispatch("research/ENQUEUE_TECHLEVEL", {
                type: props.type,
                level: props.level,
            });
            //emit("close")
            console.log(emit);
        };
        return {
            workRequired,
            onSubmit,
        };
    },
};
</script>

<template>
    <modal
        :title="
            $t('research.enqueue.title', {
                type: $t('research.tl.' + type),
                level,
            })
        "
        @close="$emit('close')"
    >
        <ul class="stats">
            <li>{{ $t("research.enqueue.costs") }}</li>
            <li class="stats--centered featured">{{ workRequired() }}</li>
        </ul>
        <template v-slot:actions>
            <game-button
                :text-string="$t('research.enqueue.submit', { level })"
                icon-name="done"
                :primary="true"
                @click="onSubmit"
            />
        </template>
    </modal>
</template>
