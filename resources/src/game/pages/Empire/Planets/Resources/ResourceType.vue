<script>
/******************************************************************************
 * PageComponent: ResourceType
 *****************************************************************************/
import { computed } from "vue";
import EmptyResourceSlot from "./EmptyResourceSlot";
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
    components: { EmptyResourceSlot },
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
        <empty-resource-slot
            :planet-id="planetId"
            :resource-type="resourceType"
        />
    </li>
</template>

<style lang="scss" scoped>
.resource-type {
    margin-right: 0.5rem;
}
</style>
