<script>
/******************************************************************************
 * PageComponent: ManageConstructionContracts
 *****************************************************************************/
import { useStore } from "vuex";
import { computed } from "vue";
import AreaSection from "Components/AreaSection/AreaSection";
import CollapsibleItem from "Components/Collapsible/CollapsibleItem";
import ConstructionContractDetails from "./ConstructionContractDetails";
export default {
    name: "ManageConstructionContracts",
    components: { AreaSection, CollapsibleItem, ConstructionContractDetails },
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
        <collapsible-item
            v-for="contract in contracts"
            :key="contract.id"
            :topic="
                $t('shipyards.constructions.contractTopic', {
                    num: contract.amount,
                    type: $t('shipyards.hulls.' + contract.hullType),
                    name: blueprint(contract.blueprintId).name,
                    planetName: shipyard(contract.shipyardId).planetName,
                })
            "
            :icon-name="`hull-${contract.hullType}`"
            :expanded="true"
            :aria-expanded="true"
        >
            <construction-contract-details :contract-id="contract.id" />
        </collapsible-item>
    </area-section>
</template>
