/******************************************************************************
 * Vuex getters
 *****************************************************************************/
import { convertLatinToRoman } from "@/game/helpers/format";

export default {
    // get star by id
    starById: (state) => (id) =>
        state.stars.find((star) => star.id === id) || {},

    // get planets belonging to a star
    planetsByStarId: (state) => (id) =>
        state.planets
            .filter((planet) => planet.starId === id)
            .sort((a, b) => {
                return a.orbitalIndex - b.orbitalIndex;
            }) || {},

    // is the starId expanded and showing planets?
    isStarCollapsed: (state) => (id) => {
        return state.collapsedStars.includes(id) || false;
    },

    // get name of star by id
    starNameById: (state) => (id) =>
        state.stars.find((star) => star.id === id).name || "",

    // get planet by id
    planetById: (state) => (id) =>
        state.planets.find((planet) => planet.id === id),

    // get harvesters of a planet
    harvestersByPlanetId: (state) => (id) =>
        state.harvesters.filter((harvester) => harvester.planetId === id),

    // get harvester by id
    harvesterById: (state) => (id) =>
        state.harvesters.find((harvester) => harvester.id === id),

    // get planetName by planetId
    planetNameById: (state) => (id) => {
        const planet = state.planets.find((planet) => planet.id === id);
        const star = state.stars.find((star) => star.id === planet.starId);
        return `${star.name} - ${convertLatinToRoman(planet.orbitalIndex)}`;
    },

    // get Shipyard by planetId
    shipyardByPlanetId: (state) => (id) =>
        state.shipyards.find((shipyard) => shipyard.planetId === id),
};
