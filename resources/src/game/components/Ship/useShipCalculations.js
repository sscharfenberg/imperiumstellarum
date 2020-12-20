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
        const modRules = window.rules.modules.find(
            (m) => m.techType === type && m.hullType === hullType
        ).costs;
        costs.energy += modRules.energy;
        costs.minerals += modRules.minerals;
    });
    // return 10% of minerals+energy as research costs
    return {
        research: Math.round((costs.energy + costs.minerals) * 0.1),
    };
};

/**
 * @function calculate hitpoints as object
 * @param {String} hullType
 * @param {Array} modules
 * @param {Array} techLevels
 * @returns {Object}
 */
export const calculateEffectiveHitpoints = (hullType, modules, techLevels) => {
    const rules = window.rules;
    const baseHp = rules.ships.hullTypes[hullType].baseHp; // base hp for this hull type
    const factor = rules.tech.tlFactor; // percentage increase of a single techlevel > 0
    const defensiveMods = rules.modules.filter(
        (mod) => mod.moduleType === "defensive" && mod.hullType === hullType
    );
    const hp = {
        structure: baseHp.structure,
        armour: baseHp.armour,
        shields: baseHp.shields,
    };

    // add hitpoints from defensive modules
    modules.forEach((module) => {
        const modRules = defensiveMods.find((mod) => mod.techType === module);
        if (modRules) hp[modRules.techType] += modRules.baseHp;
    });

    // apply tech level increases
    for (const [area] of Object.entries(hp)) {
        const tl = techLevels.find((tl) => tl.type === area);
        if (tl) {
            hp[area] = hp[area] * (1 + (factor * tl.level) / 100);
        }
    }
    return {
        structure: Math.round(hp.structure),
        armour: Math.round(hp.armour),
        shields: Math.round(hp.shields),
    };
};

/**
 * @function calculate damage of a shipclass
 * @param {String} hullType
 * @param {Array} modules
 * @param {Array} techLevels
 * @returns {Object}
 */
export const calculateShipDamage = (hullType, modules, techLevels) => {
    const rules = window.rules;
    // ensure we only look at offensive modules of the correct hull type
    const modRules = rules.modules.filter(
        (mod) => mod.moduleType === "offensive" && mod.hullType === hullType
    );
    const factor = rules.tech.tlFactor; // percentage increase of a single techlevel > 0
    const d = [];

    // baseDmg of modules
    modules.forEach((module) => {
        // define the rules for this module
        const mod = modRules.find((mod) => mod.techType === module);
        // does the damagetype already exist in return array?
        if (mod && d.find((m) => m.type === mod.techType)) {
            const objIndex = d.findIndex((obj) => obj.type === mod.techType);
            d[objIndex].dmg += mod.baseDmg;
        } else if (mod) {
            // if not, create entry in return array.
            d.push({
                type: mod.techType,
                dmg: mod.baseDmg,
                range: mod.range,
            });
        }
    });

    // apply tech level dmg increases
    d.map((area) => {
        const tl = techLevels.find((tl) => tl.type === area.type);
        if (tl) {
            area.dmg = Math.round(area.dmg * (1 + (factor * tl.level) / 100));
        }
        return area;
    });

    return d;
};

/**
 * @function calculate acceleration of a shipclass
 * @param {String} hullType
 * @param {Array} modules
 * @returns {Number}
 */
export const calculateAcceleration = (hullType, modules) => {
    const rules = window.rules;
    const hullRules = rules.ships.hullTypes[hullType];
    const moduleRules = rules.modules.filter(
        (mod) => mod.hullType === hullType
    );
    let acceleration = 0;

    // base acceleration of hullType
    acceleration = hullRules.baseAcceleration;

    // check modules and increase acceleration for modules that provide acceleration
    modules.forEach((module) => {
        const modRules = moduleRules.find((m) => m.techType === module);
        if (moduleRules && modRules.acceleration) {
            acceleration += modRules.acceleration;
        }
    });

    return acceleration;
};
