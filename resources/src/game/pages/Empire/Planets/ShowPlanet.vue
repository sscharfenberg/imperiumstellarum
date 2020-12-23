<script>
/******************************************************************************
 * PageComponent: ShowPlanet
 *****************************************************************************/
import { useStore } from "vuex";
import { computed } from "vue";
import PlanetType from "./PlanetType";
import PlanetName from "./PlanetName";
import ShowPopulation from "./Population/ShowPopulation";
import ListResources from "./Resources/ListResources";
import ShipyardSlot from "./Shipyard/ShipyardSlot";
export default {
    name: "ShowPlanet",
    props: {
        planetId: String,
        starName: String,
    },
    components: {
        PlanetType,
        PlanetName,
        ShowPopulation,
        ListResources,
        ShipyardSlot,
    },
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
            <show-population
                :planet-id="planet.id"
                v-if="planet.population"
                :star-name="starName"
            />
            <list-resources
                :resources="planet.resources"
                :planet-id="planetId"
                v-if="planet.resources.length"
            />
            <shipyard-slot v-if="planet.population" :planet-id="planetId" />
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
    margin-right: 5px;

    font-size: 16px;
    font-weight: 300;
}
</style>
