<script>
/******************************************************************************
 * PageComponent: NewContract
 *****************************************************************************/
import { useStore } from "vuex";
import { computed } from "vue";
import AreaSection from "Components/AreaSection/AreaSection";
import NewContractSelectShipyard from "./NewContractSelectShipyard";
import NewContractSelectBlueprint from "./Blueprint/NewContractSelectBlueprint";
import NewContractPreviewBlueprint from "./Blueprint/NewContractPreviewBlueprint";
export default {
    name: "NewContract",
    components: {
        AreaSection,
        NewContractSelectShipyard,
        NewContractSelectBlueprint,
        NewContractPreviewBlueprint,
    },
    setup() {
        const store = useStore();
        const selectedShipyard = computed(
            () => store.state.shipyards.newContract.shipyard
        );
        const showPreview = computed(
            () => store.state.shipyards.newContract.blueprint
        );
        return { selectedShipyard, showPreview };
    },
};
</script>

<template>
    <area-section :headline="$t('shipyards.construct.newContract.title')">
        <div class="contract__area">
            <div class="contract__section">
                <new-contract-select-shipyard />
                <new-contract-select-blueprint v-if="selectedShipyard" />
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
