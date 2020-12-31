<script>
/******************************************************************************
 * PageComponent: ManageConstructionContracts
 *****************************************************************************/
import { useStore } from "vuex";
import { computed } from "vue";
export default {
    name: "ManageConstructionContracts",
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
    <div>
        {{ contracts }}
    </div>
</template>
