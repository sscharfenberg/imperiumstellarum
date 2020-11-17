/******************************************************************************
 *
 * converts ticker to uppercase
 *
 *****************************************************************************/

/**
 * @function init
 */
export const initTextToUpperCase = () => {
    console.log("initializing uppercase transform");
    const _inputs = document.querySelectorAll("input[type=text].uppercase");
    for (let i = 0; i < _inputs.length; ++i) {
        _inputs[i].addEventListener("keyup", () => {
            _inputs[i].value = _inputs[i].value.toUpperCase();
        });
    }
};
