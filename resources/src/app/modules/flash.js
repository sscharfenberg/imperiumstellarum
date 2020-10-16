/******************************************************************************
 *
 * hides flash message if it exists
 *
 *****************************************************************************/

/**
 * @function event handler for "keydown" => hide all flashes
 * @param {KeyboardEvent} e
 */
const onKeyDown = (e) => {
    if (e.key === "Escape") {
        hideFlash();
    }
};

/**
 * @function removes flash element from dom
 */
const hideFlash = () => {
    document.removeEventListener("keydown", onKeyDown);
    if (document.querySelector(".flash__wrapper"))
        document.querySelector(".flash__wrapper").remove();
};

/**
 * @function init
 */
export const initFlash = () => {
    console.log("initializing flash");
    document.addEventListener("keydown", onKeyDown);
    setTimeout(hideFlash, 7000);
    document.getElementById("closeFlash").addEventListener("click", () => {
        hideFlash();
    });
};
