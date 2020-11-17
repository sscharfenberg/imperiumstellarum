/******************************************************************************
 *
 * colour picker initialization
 *
 *****************************************************************************/
import iro from '@jaames/iro';

const _input = document.querySelector("input[data-colour]");
const _preview = document.querySelector(".colour-preview");

/**
 * @function change input value
 * @param {String} value
 */
const changeInput = (value) => {
    _input.value = value.replace("#","");
}

/**
 * @function update preview box background color
 * @param value
 */
const updatePreview = (value) => {
    _preview.style.backgroundColor = value;
}

/**
 * @function init
 */
export const initColourPicker = () => {
    console.log("initializing colour picker");
    const colorPicker = new iro.ColorPicker(".colour-picker[data-colour]", {
        layoutDirection: "horizontal",
        width: 280,
        borderWidth: 2,
    });
    colorPicker.on("color:change", (color) => {
        changeInput(color.hexString);
        updatePreview(color.hexString);
    });
    colorPicker.on("mount", () => {
        if (_input.value.length) {
            colorPicker.color.set("#" + _input.value)
            updatePreview("#" + _input.value);
        } else {
            colorPicker.color.set("#ffffff");
            changeInput("#ffffff");
            updatePreview("#ffffff");
        }
    });
};
