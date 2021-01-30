<script>
/******************************************************************************
 * PageComponent: DeleteConstructionContractModal
 *****************************************************************************/
import { useStore } from "vuex";
import { computed } from "vue";
import GameButton from "Components/Button/GameButton";
import Modal from "Components/Modal/Modal";
export default {
    name: "DeleteConstructionContractModal",
    props: {
        contractId: String,
    },
    components: { Modal, GameButton },
    emits: ["close"],
    setup(props, { emit }) {
        const store = useStore();
        const contract = computed(() =>
            store.getters["shipyards/contractById"](props.contractId)
        );
        const onSubmit = () => {
            store.dispatch("shipyards/DELETE_CONSTRUCTION_CONTRACT", {
                id: props.contractId,
            });
            emit("close");
        };
        return {
            contract,
            onSubmit,
        };
    },
};
</script>

<template>
    <modal
        :title="$t('shipyards.constructions.deleteModal.title')"
        @close="$emit('close')"
    >
        <p>
            {{
                $t("shipyards.constructions.deleteModal.explanation", {
                    min: contract.costs.minerals,
                    ene: contract.costs.energy,
                })
            }}
        </p>
        <template v-slot:actions>
            <game-button
                :text-string="$t('shipyards.constructions.deleteModal.submit')"
                icon-name="done"
                :primary="true"
                @click="onSubmit"
            />
        </template>
    </modal>
</template>
