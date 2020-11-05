<script>
/******************************************************************************
 * PageComponent: ListResources
 *****************************************************************************/
import ResourceType from "./ResourceType";
export default {
    name: "ListResources",
    props: {
        resources: {
            type: Array,
            required: true,
        },
        harvesters: {
            type: Array,
        },
        planetId: {
            type: String,
            required: true,
        },
    },
    components: { ResourceType },
    setup() {
        return {};
    },
};
</script>

<template>
    <ul class="resources planet__item">
        <resource-type
            v-for="resource in resources"
            :key="resource.type"
            :resource-type="resource.resourceType"
            :slots="resource.slots"
            :harvesters="[]"
            :planet-id="planetId"
        />
    </ul>
</template>

<style lang="scss" scoped>
.resources {
    display: flex;

    padding: 0;
    margin: 0 0 0.5rem 0;

    list-style: none;
}
</style>
