/******************************************************************************
 * Vuex getters
 *****************************************************************************/

export default {
    // get blueprint by id
    fleetById: (state) => (id) => state.fleets.find((f) => f.id === id) || {},

    // get fleets sorted by name
    fleetsSortedByName: (state) =>
        state.fleets.sort((a, b) =>
            a.name.localeCompare(b.name, "en", { numeric: true })
        ) || [],

    // get star by id
    starById: (state) => (id) =>
        state.stars.find((star) => star.id === id) || {},

    // get ships by fleet id
    shipsByFleetId: (state) => (fleetId) =>
        state.ships.filter((ship) => ship.fleetId === fleetId) || [],

    // get ships by shipyard id
    shipsByShipyardId: (state) => (shipyardId) =>
        state.ships.find((ship) => ship.shipyardId === shipyardId) || [],
};
