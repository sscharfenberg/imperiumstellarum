<script>
/******************************************************************************
 * PageComponent: ConstructIndex
 *****************************************************************************/
import { useStore } from "vuex";
import { computed } from "vue";
import NewContract from "./NewContract/NewContract";
import ManageConstructionContracts from "./Manage/ManageConstructionContracts";
export default {
    name: "ConstructIndex",
    components: { NewContract, ManageConstructionContracts },
    setup() {
        const store = useStore();
        const contracts = computed(
            () => store.state.shipyards.constructionContracts
        );
        const shipyards = computed(() => {
            const shipyardsBuilding = contracts.value.map((c) => c.shipyardId);
            return store.state.shipyards.shipyards.filter((s) => {
                return !shipyardsBuilding.includes(s.id);
            });
        });
        return {
            shipyards,
            contracts,
        };
    },
};
</script>

<template>
    <manage-construction-contracts v-if="contracts" />
    <new-contract v-if="shipyards.length" />
    <div v-if="!shipyards">
        <p>{{ $t("shipyards.none") }}</p>
        <p>
            <router-link class="text-link" :to="{ name: 'Empire' }">{{
                $t("empire.title")
            }}</router-link>
        </p>
    </div>
</template>
