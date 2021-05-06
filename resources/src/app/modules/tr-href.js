/******************************************************************************
 *
 * click on <tr> event handler
 *
 *****************************************************************************/

/**
 * initialize table sort by adding event listener for radio buttons
 */
export const initTrHref = () => {
    console.log("initializing tr href");
    const _trs = document.querySelectorAll("[data-tr-href] tr[data-id]");
    for (let i = 0; i < _trs.length; i++) {
        _trs[i].addEventListener("click", (e) => {
            const _tr = e.target.closest("tr");
            const href = _tr.closest("table").dataset.trHref;
            location.href = href + _tr.dataset.id;
        });
    }
};
