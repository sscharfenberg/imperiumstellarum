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
            :key="resource.resourceType"
            :resource-type="resource.resourceType"
            :slots="resource.slots"
            :planet-id="planetId"
        />
    </ul>
</template>

<style lang="scss" scoped>
.resources {
    display: flex;
    flex-wrap: wrap;

    padding: 0;
    margin: 0;

    list-style: none;
}
</style>
