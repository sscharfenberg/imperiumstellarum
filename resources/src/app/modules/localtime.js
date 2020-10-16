/******************************************************************************
 *
 * local time in drawer
 *
 *****************************************************************************/
const _localTime = document.getElementById("localTime");

/**
 * @function add leading zero if necessary
 * @param num
 * @returns {string|void}
 */
const padZero = (num) => {
    if (isNaN(num)) return;
    return num <= 9 ? 0 + num.toString() : num.toString();
};

/**
 * @function update current time in dom
 */
const updateTime = () => {
    const now = new Date();
    const formattedTime = `${padZero(now.getHours())}:${padZero(
        now.getMinutes()
    )}:${padZero(now.getSeconds())}`;
    _localTime.textContent = formattedTime;
    _localTime.setAttribute("datetime", formattedTime);
};

/**
 * @function init
 */
export const initLocalTime = () => {
    console.log("initializing local time");
    updateTime();
    setInterval(updateTime, 1000);
};
