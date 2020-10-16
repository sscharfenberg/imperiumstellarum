/******************************************************************************
 *
 * slider setup
 *
 *****************************************************************************/
import noUiSlider from "nouislider";
const SELECTOR_SLIDERS = "[data-slider]";
const CLASSNAME_INPUT = "slider__input";

/**
 * noUiSlider default options
 * @type {Object}
 */
let defaultUiSliderOptions = {
    connect: true,
    orientation: "horizontal",
    step: 1,
    behavior: "tap",
    tooltips: true,
    format: {
        to: (number) => parseInt(number, 10),
        from: (number) => parseInt(number, 10),
    },
    pips: {
        // Show a scale with the slider
        mode: "count",
        stepped: true,
        values: 20,
        density: 5,
    },
};

/**
 * @function get distance values from slider input
 * @param {HTMLElement} _slider
 */
export const getDistance = (_slider) => {
    const _inputs = _slider.querySelectorAll("input[type=hidden]");
    const returnValues = [];
    for (let _input of _inputs) {
        returnValues.push(parseInt(_input.value, 10));
    }
    return returnValues;
};

/**
 * @function get hidden inputs that belong to a _slider
 * @param {HTMLElement} _slider
 * @returns {array}
 */
const getSliderInputs = (_slider) => {
    const _inputs = _slider.querySelectorAll("input[type=hidden]");
    let returnList = [];
    for (let _input of _inputs) {
        if (_input.classList.contains(CLASSNAME_INPUT)) returnList.push(_input);
    }
    return returnList;
};

/**
 * @function update the hidden fields after a slider has been changed
 * @param {HTMLElement} _slider
 * @param {array} values
 */
const updateSliderInputs = (_slider, values) => {
    const _inputs = getSliderInputs(_slider);
    _inputs.forEach((input, index) => {
        input.value = values[index];
    });
    console.log(`updated #${_slider.id} with values [${values}].`);
};

/**
 * @function initialize slider
 * @param {HTMLElement} _slider - DOM node of slider
 */
const initSlider = (_slider) => {
    const _inputs = getSliderInputs(_slider);
    const min = parseInt(_slider.getAttribute("data-min"), 10);
    const max = parseInt(_slider.getAttribute("data-max"), 10);
    let sliderOptions = defaultUiSliderOptions;
    let values = [];
    _inputs.forEach((_input) => {
        if (_input.classList.contains(CLASSNAME_INPUT))
            values.push(_input.value);
    });
    sliderOptions.range = {
        min: [min],
        max: [max],
    };
    sliderOptions.start = values;
    noUiSlider.create(_slider, sliderOptions);
    _slider.noUiSlider.on("change", function (values) {
        updateSliderInputs(_slider, values);
    });
    console.log(
        `bound #${_slider.id} with ${_inputs.length} hidden fields and [${values}] as values.`
    );
};

/**
 * @function initialize all sliders
 */
export const initSliders = () => {
    console.log("initializing sliders");
    const _sliders = document.querySelectorAll(SELECTOR_SLIDERS);
    if (!_sliders || !_sliders.length) return;
    console.log(`binding ${_sliders.length} range inputs.`);
    for (let _slider of _sliders) initSlider(_slider);
};
