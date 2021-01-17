<script>
/******************************************************************************
 * PageComponent: FleetMoveModal
 *****************************************************************************/
import { computed } from "vue";
import { useStore } from "vuex";
import Modal from "Components/Modal/Modal";
import GameButton from "Components/Button/GameButton";
export default {
    name: "FleetMoveModal",
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
            console.log(emit);
            //emit("close");
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
        :title="$t('fleets.move.headline', { name: fleet.name })"
        @close="$emit('close')"
    >
        TODO: Current Fleet Status (ships), Location<br />
        TODO: find a good way to have the player choose destinations.<br />
        {{ fleet }}
        <template v-slot:actions>
            <game-button
                :text-string="$t('fleets.move.submit')"
                icon-name="save"
                @click="onSubmit"
            />
        </template>
    </modal>
</template>

<style lang="scss" scoped>
.app-form {
    padding: 0;
    margin: 0;

    @include themed() {
        background-color: t("g-sunken");
    }

    p {
        margin: 0;
    }
}

.form-row.submit {
    padding: 0;
}
</style>
