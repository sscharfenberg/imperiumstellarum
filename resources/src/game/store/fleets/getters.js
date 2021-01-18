/******************************************************************************
 * Vuex getters
 *****************************************************************************/

export default {
    /**
     * singletons
     */

    // get fleet by id
    fleetById: (state) => (id) => state.fleets.find((f) => f.id === id) || {},

    // get shipyard by id
    shipyardById: (state) => (id) =>
        state.shipyards.find((y) => y.id === id) || {},

    // get star by id
    starById: (state) => (id) =>
        state.stars.find((star) => star.id === id) || {},

    // get ship by id
    shipById: (state) => (shipId) =>
        state.ships.find((ship) => ship.id === shipId) || {},

    playerById: (state) => (playerId) =>
        state.players.find((player) => player.id === playerId) || {},

    /**
     * multiples
     */

    // get fleets sorted by name
    fleetsSortedByName: (state) =>
        state.fleets.sort((a, b) =>
            a.name.localeCompare(b.name, "en", { numeric: true })
        ) || [],

    // get ships by fleet id
    shipsByFleetId: (state) => (fleetId) =>
        state.ships.filter((ship) => ship.fleetId === fleetId) || [],

    // get ships by shipyard id
    shipsByShipyardId: (state) => (shipyardId) =>
        state.ships.filter((ship) => ship.shipyardId === shipyardId) || [],
};
