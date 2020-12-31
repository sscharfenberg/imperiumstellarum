<script>
/******************************************************************************
 * PageComponent: ManageConstructionContracts
 *****************************************************************************/
import { useStore } from "vuex";
import { computed } from "vue";
import AreaSection from "Components/AreaSection/AreaSection";
export default {
    name: "ManageConstructionContracts",
    components: { AreaSection },
    setup() {
        const store = useStore();
        const contracts = computed(
            () => store.state.shipyards.constructionContracts
        );
        return {
            contracts,
        };
    },
};
</script>

<template>
    <area-section :headline="$t('shipyards.constructions.headline')">
        {{ contracts }}
    </area-section>
</template>
