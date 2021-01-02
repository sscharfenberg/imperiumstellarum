/******************************************************************************
 * calculation helpers
 *****************************************************************************/

/**
 *
 * @param {Array} costs - array of costs, as defined in /config/rules.php
 * @param {Array} resources - array of objects, as sent to client in xhr: /app/Services/FormatApiResponseService.php
 * @param {Number} population - optional population if the costs include population
 * @returns {boolean}
 */
export const isEntityAffordable = (costs, resources, population = null) => {
    let isAffordable = true;
    for (const resType in costs) {
        // 'normal' resources - energy, minerals, research, food.
        if (
            resType !== "turns" &&
            resType !== "population" &&
            costs[resType] >
                resources.find((res) => res.type === resType)?.amount
        ) {
            isAffordable = false;
        }
        // population
        if (
            resType === "population" &&
            population &&
            costs["population"] > population
        ) {
            isAffordable = false;
        }
    }
    return isAffordable;
};
