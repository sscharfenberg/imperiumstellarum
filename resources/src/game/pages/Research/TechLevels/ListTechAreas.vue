<script>
/******************************************************************************
 * PageComponent: ListTechAreas
 *****************************************************************************/
import { computed } from "vue";
import { useStore } from "vuex";
import ShowTechArea from "./ShowTechArea";
export default {
    name: "ListTechAreas",
    components: { ShowTechArea },
    setup() {
        const store = useStore();
        const techLevels = computed(
            () => store.getters["research/techLevelsSorted"]
        );
        return {
            techLevels,
        };
    },
};
</script>

<template>
    <ul class="tech-areas">
        <show-tech-area
            v-for="area in techLevels"
            :key="area.id"
            :tech-level-id="area.id"
        />
    </ul>
    {{ techLevels }}
</template>

<style lang="scss" scoped>
.tech-areas {
    padding: 0;
    margin: 0;

    list-style: none;
}
</style>
