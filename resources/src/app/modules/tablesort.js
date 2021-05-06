/******************************************************************************
 *
 * submits the form if sort radio buttons are clicked/changed
 *
 *****************************************************************************/

/**
 * initialize table sort by adding event listener for radio buttons
 */
export const initTableSort = () => {
    console.log("initializing table sort");
    const _sortRadios = document.querySelectorAll("input[name=sort]");
    for (let i = 0; i < _sortRadios.length; i++) {
        _sortRadios[i].addEventListener("change", () => {
            _sortRadios[i].closest("form").submit();
        });
    }
};
