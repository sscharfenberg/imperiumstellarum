<script>
/******************************************************************************
 * PageComponent: ResourceType
 *****************************************************************************/
import { useStore } from "vuex";
import { computed } from "vue";
import ShowEmptyResourceSlot from "./ShowEmptyResourceSlot";
import ShowHarvester from "./ShowHarvester";
export default {
    name: "ResourceType",
    props: {
        resourceType: String,
        slots: Number,
        planetId: String,
    },
    components: { ShowEmptyResourceSlot, ShowHarvester },
    setup(props) {
        const store = useStore();
        const harvesters = computed(() =>
            store.getters["empire/harvestersByPlanetId"](props.planetId).filter(
                (harvester) => harvester.resourceType === props.resourceType
            )
        );
        const emptySlots = computed(
            () => props.slots - harvesters.value.length
        );
        return {
            harvesters,
            emptySlots,
        };
    },
};
</script>

<template>
    <li
        class="resource-type"
        v-for="harvester in harvesters"
        :key="harvester.id"
    >
        <show-harvester
            :resource-type="resourceType"
            :harvester-id="harvester.id"
        />
    </li>
    <li
        class="resource-type"
        v-for="n in emptySlots"
        :key="`resourceSlot${planetId}${resourceType}-${n}`"
    >
        <show-empty-resource-slot
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
