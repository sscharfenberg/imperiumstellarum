/**
 * @function calculate research costs for the blueprint
 * @param {String} hullType
 * @param {Array} modules - Array of module techTypes
 * @returns {Object} - Costs Object
 */
export const calculateBlueprintCosts = (hullType, modules) => {
    // start with costs for hull
    const costs = {
        energy: window.rules.ships.hullTypes[hullType].costs.energy,
        minerals: window.rules.ships.hullTypes[hullType].costs.minerals,
    };
    // costs for modules
    modules.forEach((type) => {
        const modCosts = window.rules.modules.find(
            (m) => m.techType === type && m.hullType === hullType
        ).costs;
        costs.energy += modCosts.energy;
        costs.minerals += modCosts.minerals;
    });
    // return 10% of minerals+energy as research costs
    return {
        research: Math.round((costs.energy + costs.minerals) * 0.1),
    };
};
