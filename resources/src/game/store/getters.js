/******************************************************************************
 * Vuex getters
 *****************************************************************************/

export default {
    storageUpgradeByType: (state) => (type) =>
        state.storageUpgrades.find((res) => type === res.resourceType) || {},
};
