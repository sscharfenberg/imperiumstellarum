/******************************************************************************
 *
 * disables submit button after being clicked and shows loading spinner
 *
 *****************************************************************************/

/**
 * @function init
 */
export const initSubmitButton = () => {
    console.log("initializing submit button handling");
    const _forms = document.querySelectorAll("form");
    for (let i = 0; i < _forms.length; ++i) {
        _forms[i].addEventListener("submit", () => {
            const _button = _forms[i].querySelector(".app-btn--submit");
            if (_button) {
                const _loading = _button.querySelector("svg.spinner");
                _button.disabled = true;
                _loading.style.display = "block";
            }
        });
    }
};
