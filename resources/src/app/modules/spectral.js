/******************************************************************************
 *
 * add a random spectral star to headline
 *
 *****************************************************************************/

/**
 * @function get random integer
 * @returns {number}
 * Returns a random integer between min (inclusive) and max (inclusive).
 * The value is no lower than min (or the next integer greater than min
 * if min isn't an integer) and no greater than max (or the next integer
 * lower than max if max isn't an integer).
 * Using Math.round() will give you a non-uniform distribution!
 * https://developer.mozilla.org/en-US/docs/Web/JavaScript/Reference/Global_Objects/Math/random
 */
export const getRandomInt = (min, max) => {
    min = Math.ceil(min);
    max = Math.floor(max);
    return Math.floor(Math.random() * (max - min + 1)) + min;
};

/**
 * @function get a random spectral class
 * @returns {string}
 */
const getRandomSpectral = () => {
    const spectrals = ["O", "B", "A", "F", "G", "K", "M", "Y"];
    return spectrals[getRandomInt(0, spectrals.length - 1)];
};

/**
 * @function init
 */
export const initSpectral = () => {
    console.log("initializing spectral");
    const _spectrals = document.querySelectorAll("[data-spectral]");
    for (let i = 0; i < _spectrals.length; i++) {
        const spectralClass = getRandomSpectral();
        const label = _spectrals[i].getAttribute("aria-label");
        _spectrals[i].classList.add("spectral");
        _spectrals[i].classList.add(`spectral--${spectralClass}`);
        _spectrals[i].setAttribute("aria-label", `${label}: ${spectralClass}`);
        _spectrals[i].setAttribute("title", `${label}: ${spectralClass}`);
    }
};
