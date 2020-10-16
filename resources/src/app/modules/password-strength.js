/******************************************************************************
 *
 * handle password strength meter
 *
 *****************************************************************************/
// imports
import debounce from "lodash/debounce";

/**
 * @function hide the password strength meter
 * @param {HTMLElement} _meter
 */
const hideMeter = (_meter) => {
    const _parent = _meter.parentNode;
    _parent.style.display = "none";
    _parent.parentNode.setAttribute("aria-hidden", "true");
};

/**
 * @function show the password strength meter
 * @param {HTMLElement} _meter
 */
const showMeter = (_meter) => {
    const _parent = _meter.parentNode;
    _parent.style.display = "block";
    _parent.parentNode.removeAttribute("aria-hidden");
};

/**
 * @function update the password strength meter with new score
 * @param {int} score
 * @param {HTMLElement} _meter
 */
const updateMeter = (score, _meter) => {
    let pct = (score + 1) * 20 - 10;
    if (pct === 90) pct = 100;
    console.log("sore", score, "width:", pct);
    _meter.querySelector(".shrinker").style.width = pct.toString(10) + "%";
};

/**
 * @function reset icon to default state (no state classes)
 * @param {HTMLElement} _icon
 */
const resetIcon = (_icon) => {
    _icon.classList.remove("error", "ok");
};

/**
 * @function update the addon-icon of the input field
 * @param {number} score
 * @param {number} min
 * @param {HTMLElement} _input
 */
const updateIcon = (score, min, _input) => {
    const _icon = _input.parentNode.querySelector(".icon");
    resetIcon(_icon);
    if (min > score) {
        _icon.classList.add("error");
    } else {
        _icon.classList.add("ok");
    }
};

/**
 * @function axios request to API
 * @param {HTMLElement} _input
 * @param {HTMLElement} _meter
 * @returns {Object}
 */
const callApi = (_input, _meter) => {
    window.axios
        .post("/api/passwordStrength", { password: _input.value })
        .then((response) => {
            if (response.data.min) {
                updateMeter(response.data.score, _meter);
                updateIcon(response.data.score, response.data.min, _input);
            }
        })
        .catch((e) => {
            console.error(e);
        });
};

/**
 * @function init
 */
export const initPasswordStrength = () => {
    console.log("initializing password strength");
    const _meters = document.querySelectorAll(".password-strength");
    for (let i = 0; i < _meters.length; ++i) {
        const _input = _meters[i].parentNode.parentNode.querySelector(
            "input[type=password]"
        );
        _input.addEventListener("keyup", () => {
            if (_input.value !== "") {
                showMeter(_meters[i]);
            } else {
                hideMeter(_meters[i]);
                resetIcon(_input.parentNode.querySelector(".icon"));
            }
        });
        _input.addEventListener(
            "keyup",
            debounce(() => callApi(_input, _meters[i]), 500)
        );
    }
};
