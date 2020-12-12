<script>
/******************************************************************************
 * Component: PlayerResources
 *****************************************************************************/
import { useStore } from "vuex";
import { computed } from "vue";
import ResourceItem from "./ResourceItem";
export default {
    name: "PlayerResources",
    components: { ResourceItem },
    setup() {
        const store = useStore();
        const resources = computed(() => store.getters.sortResources);
        return {
            resources,
        };
    },
};
</script>

<template>
    <div class="resources">
        <resource-item
            v-for="resource in resources"
            :key="resource.type"
            :type="resource.type"
            :amount="resource.amount"
            :max="resource.max"
            :storage-level="resource.level"
            :max-level="resource.maxLevel"
        />
    </div>
</template>

<style lang="scss" scoped>
.resources {
    display: flex;
    flex-direction: row;
    flex-wrap: wrap;

    padding: 0;
    margin: 0 16px 16px 16px;

    list-style: none;
}
</style>
