<script>
/******************************************************************************
 * PageComponent: ResourceType
 *****************************************************************************/
import { computed } from "vue";
export default {
    name: "ResourceType",
    props: {
        resourceType: String,
        slots: Number,
        harvesters: {
            type: Array,
        },
        planetId: String,
    },
    setup(props) {
        const emptySlots = computed(
            () => props.slots - props.harvesters.length
        );
        return {
            emptySlots,
        };
    },
};
</script>

<template>
    <li
        class="resource-type"
        v-for="n in emptySlots"
        :key="`resourceSlot${planetId}${resourceType}-${n}`"
    >
        {{ resourceType }}
    </li>
</template>

<style lang="scss" scoped>
.resources {
    padding: 0;
    margin: 0;

    list-style: none;
}
</style>
