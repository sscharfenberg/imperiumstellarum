<script>
/******************************************************************************
 * PageComponent: ShowPlanet
 *****************************************************************************/
import { useStore } from "vuex";
import { computed } from "vue";
import PlanetType from "./PlanetType";
import PlanetName from "./PlanetName";
export default {
    name: "ShowPlanet",
    props: {
        planetId: String,
        starName: String,
    },
    components: { PlanetType, PlanetName },
    setup(props) {
        const store = useStore();
        const planet = computed(() =>
            store.getters["empire/planetById"](props.planetId)
        );
        return {
            planet,
        };
    },
};
</script>

<template>
    <li class="planet">
        <planet-type :planet-id="planetId" />
        <div class="planet__data">
            <planet-name :star-name="starName" :index="planet.orbitalIndex" />
        </div>
    </li>
</template>

<style lang="scss" scoped>
.planet {
    display: flex;

    &__data {
        display: flex;
        align-items: flex-start;
        flex-direction: row;
        flex-wrap: wrap;
    }
}
</style>

<style lang="scss">
.planet__item {
    margin: 0 0.5rem 0.5rem 0;

    font-size: 16px;

    &:last-of-type {
        margin-right: 0;
    }
}
</style>
