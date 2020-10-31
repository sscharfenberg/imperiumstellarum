<script>
/******************************************************************************
 * PageComponent: ListPlanets
 *****************************************************************************/
import { useStore } from "vuex";
import { computed } from "vue";
export default {
    name: "ListPlanets",
    props: {
        starId: String,
    },
    setup(props) {
        const store = useStore();
        const planets = computed(() =>
            store.getters["empire/planetsByStarId"](props.starId)
        );
        const starName = computed(() =>
            store.getters["empire/starNameById"](props.starId)
        );
        return {
            planets,
            starName,
        };
    },
};
</script>

<template>
    <ul class="planets">
        <li v-for="planet in planets" :key="planet.id">
            {{ starName }} - {{ planet.orbitalIndex }} || {{ planet.type }}
        </li>
    </ul>
</template>

<style lang="scss" scoped>
.planets {
    padding: 0;
    margin: 1.3rem 0 0 0;
    flex: 0 0 100%;

    list-style: none;
}
</style>
