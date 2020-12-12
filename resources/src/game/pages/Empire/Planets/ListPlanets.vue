<script>
/******************************************************************************
 * PageComponent: ListPlanets
 *****************************************************************************/
import { useStore } from "vuex";
import { computed } from "vue";
import ShowPlanet from "./ShowPlanet";
export default {
    name: "ListPlanets",
    props: {
        starId: String,
    },
    components: { ShowPlanet },
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
    <ul class="planets" v-if="planets.length">
        <show-planet
            v-for="planet in planets"
            :key="planet.id"
            :planet-id="planet.id"
            :star-name="starName"
        />
    </ul>
</template>

<style lang="scss" scoped>
.planets {
    display: flex;
    flex-direction: column;

    padding: 0;
    margin: 10px 0 0 0;
    flex: 0 0 100%;

    list-style: none;
}
</style>
