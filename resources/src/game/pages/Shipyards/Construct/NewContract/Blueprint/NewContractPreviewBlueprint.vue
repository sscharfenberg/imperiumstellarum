<script>
/******************************************************************************
 * PageComponent: NewContractPreviewBlueprint
 *****************************************************************************/
import { useStore } from "vuex";
import { computed } from "vue";
import ShipClassCard from "Components/Ship/ShipClassCard/ShipClassCard";
import SubHeadline from "Components/SubHeadline/SubHeadline";
import GameButton from "Components/Button/GameButton";
export default {
    name: "NewContractPreviewBlueprint",
    components: { SubHeadline, ShipClassCard, GameButton },
    setup() {
        const store = useStore();
        const blueprint = computed(() => {
            const id = store.state.shipyards.newContract.blueprint;
            return store.getters["shipyards/blueprintById"](id);
        });
        const amount = computed(() => store.state.shipyards.newContract.amount);
        const tls = computed(() => {
            const tls = [];
            const objectTls = blueprint.value.techLevels;
            for (const [type, level] of Object.entries(objectTls)) {
                tls.push({ type, level });
            }
            return tls;
        });
        const onCancel = () => {
            store.commit("shipyards/SET_CONTRACT_BLUEPRINT", "");
        };
        return {
            blueprint,
            tls,
            amount,
            onCancel,
        };
    },
};
</script>

<template>
    <div class="contract__blueprint-preview">
        <sub-headline
            :headline="$t('shipyards.construct.newContract.blueprint.preview')"
        >
            <game-button icon-name="cancel" @click="onCancel" />
        </sub-headline>
        <ship-class-card
            :class-name="blueprint.name"
            :hull-type="blueprint.hullType"
            :modules="blueprint.modules"
            :tls="tls"
            :amount="amount"
        />
    </div>
</template>

<style lang="scss" scoped>
.contract__blueprint-preview {
    order: -1;

    padding: 8px;
    margin-bottom: 8px;
    flex: 0 0 100%;

    @include respond-to("medium") {
        order: 0;

        padding: 16px;
        margin: 0 0 0 16px;
        flex: 0 0 calc(40% - 8px);
    }

    @include themed() {
        background-color: t("g-sunken");
    }

    @include respond-to("medium") {
        order: 1;

        flex: 0 0 calc(40% - 8px);
    }
}
</style>
