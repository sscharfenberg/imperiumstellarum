/******************************************************************************
 * Vuex getters
 *****************************************************************************/

export default {
    // get and sort tech levels
    starsWithOwners: (state) =>
        state.starsShown.map((star) => {
            const player = state.players.find(
                (player) => player.id === star.ownerId
            );
            if (player) {
                star.ownerId = player.id;
                star.ownerColour = "#" + player.colour;
                star.ownerTicker = player.ticker;
                star.ownerName = player.name;
            } else {
                star.ownerColour = "transparent";
            }
            return star;
        }),

    // get star by id
    starById: (state) => (id) =>
        state.stars.find((star) => star.id === id) || {},

    // get fleet by id
    fleetById: (state) => (id) => state.fleets.find((f) => f.id === id) || {},

    // get ships by fleet id
    shipsByFleetId: (state) => (fleetId) =>
        state.ships.filter((ship) => ship.fleetId === fleetId) || [],
};
