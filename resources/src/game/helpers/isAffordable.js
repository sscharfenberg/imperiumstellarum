/******************************************************************************
 * calculation helpers
 *****************************************************************************/

/**
 *
 * @param costs - array of costs, as defined in /config/rules.php
 * @param resources - array of resources, as sent to client in xhr: /app/Services/FormatApiResponseService.php
 * @returns {boolean}
 */
export const isEntityAffordable = (costs, resources) => {
    let isAffordable = true;
    for (const resType in costs) {
        if (
            resType !== "turns" &&
            costs[resType] >
                resources.find((res) => res.type === resType).amount
        ) {
            isAffordable = false;
        }
    }
    return isAffordable;
};
