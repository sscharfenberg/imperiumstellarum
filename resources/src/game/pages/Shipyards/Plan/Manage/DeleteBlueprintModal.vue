<script>
/******************************************************************************
 * PageComponent: DeleteBlueprintModal
 *****************************************************************************/
import { computed } from "vue";
import { useStore } from "vuex";
import Modal from "Components/Modal/Modal";
import GameButton from "Components/Button/GameButton";
export default {
    name: "DeleteBlueprintModal",
    props: {
        blueprintId: String,
    },
    components: { Modal, GameButton },
    emits: ["close"],
    setup(props) {
        const store = useStore();
        const blueprint = computed(() =>
            store.getters["shipyards/blueprintById"](props.blueprintId)
        );
        const onSubmit = () => {
            console.log("do delete blueprint action");
        };
        return {
            onSubmit,
            blueprint,
        };
    },
};
</script>

<template>
    <modal
        :title="
            $t('shipyards.manage.deleteModal.title', {
                name: blueprint.name,
            })
        "
        @close="$emit('close')"
    >
        {{ $t("shipyards.manage.deleteModal.question") }}
        <template v-slot:actions>
            <game-button
                text-string="LÖSCHEN"
                icon-name="delete"
                :primary="true"
                @click="onSubmit"
            />
        </template>
    </modal>
</template>
