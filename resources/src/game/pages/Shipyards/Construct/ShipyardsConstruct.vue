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
        const shipyards = computed(() => store.state.shipyards.shipyards);
        const contracts = computed(
            () => store.state.shipyards.constructionContracts
        );
        return {
            shipyards,
            contracts,
        };
    },
};
</script>

<template>
    <manage-construction-contracts v-if="contracts" />
    <new-contract v-if="shipyards" />
    <div v-if="!shipyards">
        <p>{{ $t("shipyards.none") }}</p>
        <p>
            <router-link class="text-link" :to="{ name: 'Empire' }">{{
                $t("empire.title")
            }}</router-link>
        </p>
    </div>
</template>
