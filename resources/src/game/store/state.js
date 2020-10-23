/******************************************************************************
 * Vuex module "app" state
 *****************************************************************************/

export const persistantState = {
    foo: "bar",
};
4;
export const nonPersistantState = {
    bar: "foo",
};

const defaultState = {
    ...persistantState,
    ...nonPersistantState,
};

/*
 * get initial module state
 * @returns {Object}
 */
export const state = () => {
    return defaultState;
};
