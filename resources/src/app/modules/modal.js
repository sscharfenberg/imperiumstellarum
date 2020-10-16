/******************************************************************************
 *
 * show/hide modal
 *
 *****************************************************************************/

/**
 * @function event handler for "keydown" => hide all modals
 * @param {KeyboardEvent} e
 */
const onKeyDown = (e) => {
    if (e.key === "Escape") {
        const _modals = document.querySelectorAll(".modal[data-modal]");
        for (let i = 0; i < _modals.length; ++i) {
            _modals[i].style.display = "none";
        }
        document.removeEventListener("keydown", onKeyDown);
    }
};

/**
 * @function show a modal window by data-modal ID
 * @param {string} id
 */
const showModal = (id) => {
    document.querySelector('.modal[data-modal="' + id + '"]').style.display =
        "block";
    document.addEventListener("keydown", onKeyDown);
};

/**
 * @function hide a modal window by data-modal ID
 * @param {string} id
 */
const hideModal = (id) => {
    document.querySelector('.modal[data-modal="' + id + '"]').style.display =
        "none";
    document.removeEventListener("keydown", onKeyDown);
};

export const initModal = () => {
    const _modalTriggers = document.querySelectorAll("button[data-modal]");
    const _modals = document.querySelectorAll(".modal[data-modal]");

    if (_modalTriggers.length === _modals.length) {
        console.log("initializing modal");
        for (let i = 0; i < _modalTriggers.length; ++i) {
            const modalId = _modalTriggers[i].dataset.modal;
            const _cancelElements = document.querySelectorAll(
                '.modal[data-modal="' + modalId + '"] [data-cancel]'
            );
            _modalTriggers[i].addEventListener("click", () => {
                showModal(modalId);
            });
            for (let j = 0; j < _cancelElements.length; ++j) {
                _cancelElements[j].addEventListener("click", () => {
                    hideModal(modalId);
                });
            }
        }
    }
};
