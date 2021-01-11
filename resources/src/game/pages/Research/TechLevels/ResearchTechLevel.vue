<script>
/******************************************************************************
 * PageComponent: ResearchTechLevel
 *****************************************************************************/
import { useStore } from "vuex";
import { formatInt } from "@/game/helpers/format";
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
            emit("close");
        };
        return {
            workRequired,
            onSubmit,
            formatInt,
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
            <li class="stats--centered featured">
                {{ formatInt(workRequired()) }}
            </li>
            <li class="stats--two-col">
                {{ $t("research.enqueue.explanation") }}
            </li>
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

<style lang="scss" scoped>
.stats {
    margin-bottom: 0;
}
</style>
