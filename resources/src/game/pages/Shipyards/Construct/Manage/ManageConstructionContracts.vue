<script>
/******************************************************************************
 * PageComponent: ManageConstructionContracts
 *****************************************************************************/
import { useStore } from "vuex";
import { computed } from "vue";
import AreaSection from "Components/AreaSection/AreaSection";
import CollapsibleItem from "Components/Collapsible/CollapsibleItem";
import ConstructionContractDetails from "./ConstructionContractDetails";
import Icon from "Components/Icon/Icon";
import Popover from "Components/Popover/Popover";
export default {
    name: "ManageConstructionContracts",
    components: {
        AreaSection,
        CollapsibleItem,
        ConstructionContractDetails,
        Icon,
        Popover,
    },
    setup() {
        const store = useStore();
        const contracts = computed(
            () => store.state.shipyards.constructionContracts
        );
        const blueprints = computed(() => store.state.shipyards.blueprints);
        const shipyards = computed(() => store.state.shipyards.shipyards);
        const blueprint = (id) => blueprints.value.find((b) => b.id === id);
        const shipyard = (id) => shipyards.value.find((s) => s.id === id);
        return {
            contracts,
            blueprint,
            shipyard,
        };
    },
};
</script>

<template>
    <area-section :headline="$t('shipyards.constructions.headline')">
        <template v-slot:aside>
            <popover align="right">
                {{ $t("shipyards.constructions.explanation") }}
            </popover>
        </template>
        <collapsible-item
            v-for="contract in contracts"
            :key="contract.id"
            :collapsible-id="contract.id"
        >
            <template v-slot:topic>
                <icon
                    class="shipclass-icon"
                    :name="`hull-${contract.hullType}`"
                />
                {{
                    $t("shipyards.constructions.contractTopic", {
                        num: contract.amount,
                        type: $t("shipyards.hulls." + contract.hullType),
                        name: blueprint(contract.blueprintId).name,
                        planetName: shipyard(contract.shipyardId).planetName,
                    })
                }}
            </template>
            <construction-contract-details :contract-id="contract.id" />
        </collapsible-item>
    </area-section>
</template>

<style lang="scss" scoped>
.shipclass-icon {
    margin: 0 16px 0 8px;

    @include themed() {
        color: t("b-christine");
    }
}
</style>
