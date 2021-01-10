<script>
/******************************************************************************
 * PageComponent: FleetDeleteModal
 *****************************************************************************/
import { computed } from "vue";
import { useStore } from "vuex";
import Modal from "Components/Modal/Modal";
import GameButton from "Components/Button/GameButton";
export default {
    name: "FleetDeleteModal",
    props: {
        fleetId: String,
    },
    components: { Modal, GameButton },
    emits: ["close"],
    setup(props, { emit }) {
        const store = useStore();
        const fleet = computed(() =>
            store.getters["fleets/fleetById"](props.fleetId)
        );
        const onSubmit = () => {
            store.dispatch("fleets/DELETE_FLEET", {
                id: props.fleetId,
            });
            emit("close");
        };
        return {
            fleet,
            onSubmit,
        };
    },
};
</script>

<template>
    <modal
        :title="$t('fleets.active.deleteFleet.headline', { name: fleet.name })"
        @close="$emit('close')"
    >
        {{ $t("fleets.active.deleteFleet.text", { name: fleet.name }) }}
        <template v-slot:actions>
            <game-button
                :text-string="$t('fleets.active.deleteFleet.submit')"
                icon-name="delete"
                :primary="true"
                @click="onSubmit"
            />
        </template>
    </modal>
</template>
