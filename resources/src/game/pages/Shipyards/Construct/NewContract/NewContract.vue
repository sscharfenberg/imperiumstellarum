<script>
/******************************************************************************
 * PageComponent: NewContract
 *****************************************************************************/
import { useStore } from "vuex";
import { computed } from "vue";
import AreaSection from "Components/AreaSection/AreaSection";
import NewContractPreviewBlueprint from "./Blueprint/NewContractPreviewBlueprint";
import NewContractSelectAmount from "./NewContractSelectAmount";
import NewContractSelectBlueprint from "./Blueprint/NewContractSelectBlueprint";
import NewContractSelectShipyard from "./NewContractSelectShipyard";
import NewContractSubmit from "./NewContractSubmit";
import Popover from "Components/Popover/Popover";
export default {
    name: "NewContract",
    components: {
        AreaSection,
        NewContractSelectShipyard,
        NewContractSelectBlueprint,
        NewContractPreviewBlueprint,
        NewContractSelectAmount,
        NewContractSubmit,
        Popover,
    },
    setup() {
        const store = useStore();
        const selectedShipyard = computed(
            () => store.state.shipyards.newContract.shipyard
        );
        const selectedBlueprint = computed(
            () => store.state.shipyards.newContract.blueprint
        );
        const amount = computed(() => store.state.shipyards.newContract.amount);
        const showPreview = computed(
            () => store.state.shipyards.newContract.blueprint
        );
        return { selectedShipyard, showPreview, selectedBlueprint, amount };
    },
};
</script>

<template>
    <area-section :headline="$t('shipyards.construct.newContract.title')">
        <template v-slot:aside>
            <popover align="right">
                {{ $t("shipyards.construct.explanation") }}
            </popover>
        </template>
        <div class="contract__area">
            <div class="contract__section">
                <new-contract-select-shipyard />
                <new-contract-select-blueprint v-if="selectedShipyard" />
                <new-contract-select-amount
                    v-if="selectedShipyard && selectedBlueprint"
                />
                <new-contract-submit
                    v-if="selectedShipyard && selectedBlueprint && amount"
                />
            </div>
            <new-contract-preview-blueprint v-if="showPreview" />
        </div>
    </area-section>
</template>

<style lang="scss" scoped>
.contract {
    &__area {
        display: flex;
        flex-direction: column;

        @include respond-to("medium") {
            flex-direction: row;
        }
    }

    &__section {
        padding: 8px;

        @include respond-to("medium") {
            padding: 16px;
            flex-grow: 1;
        }

        @include themed() {
            background-color: t("g-sunken");
        }
    }
}
</style>
