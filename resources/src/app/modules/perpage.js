/******************************************************************************
 *
 * submits the form if 'per page' select is changed
 *
 *****************************************************************************/

/**
 * initialize per page selects
 */
export const initPerPage = () => {
    console.log("initializing per page select");
    const _selects = document.querySelectorAll("[data-perpage]");
    for (let i = 0; i < _selects.length; i++) {
        _selects[i].addEventListener("change", () => {
            _selects[i].closest("form").submit();
        });
    }
};
