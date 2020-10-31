/******************************************************************************
 * Vuex root getters
 *****************************************************************************/

export default {
    // get storageUpgrade by type
    storageUpgradeByType: (state) => (type) =>
        state.storageUpgrades.find((res) => type === res.resourceType) || {},

    // get resources and sort them by type so we have a consistent order
    sortResources: (state) =>
        state.resources.sort((a, b) => {
            return a.type.localeCompare(b.type);
        }),
};
