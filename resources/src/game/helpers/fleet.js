/**
 * @function get the hullTypes in a collection of ships
 * @param {Array} ships - Array of ship objects
 * @param {Array} rules - window.rules Array
 * @param {Boolean} reverse - if this is true => biggest hullTypes first
 * @returns {Array} - Array of Strings with hullTypes
 */
export const getFleetHullTypes = (ships, rules, reverse = false) => {
    const order = Object.keys(rules.ships.hullTypes);
    let hullTypes = ships
        .map((b) => b.hullType) // pass an array that only contains hullTypes
        // check if it is the first index, so we pass only uniques.
        .filter((value, index, self) => self.indexOf(value) === index)
        // sort hullTypes according to our preferred sort order (ascending)
        .sort((a, b) => {
            return order.indexOf(a) - order.indexOf(b);
        });
    if (reverse) hullTypes = hullTypes.reverse();
    return hullTypes;
};
