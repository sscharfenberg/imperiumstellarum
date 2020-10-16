/******************************************************************************
 *
 * datepicker wrapper
 *
 *****************************************************************************/
import flatpickr from "flatpickr";

/**
 * initialize datepicker
 */
export const initDatePicker = () => {
    console.log("initializing date picker");
    const _inputs = document.querySelectorAll("[data-datepicker]");
    for (let i = 0; i < _inputs.length; i++) {
        const _hidden = _inputs[i].parentNode.querySelector(
            "[data-timezone-offset]"
        );
        _hidden.value = new Date().getTimezoneOffset();
        flatpickr(_inputs[i], {
            minDate: new Date(),
            dateFormat: "d.m.Y H:i",
            weekNumbers: true,
            enableTime: true,
            time_24hr: true,
        });
    }
};
