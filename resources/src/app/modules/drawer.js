/******************************************************************************
 *
 * toggle drawer
 *
 *****************************************************************************/
const _toggleButton = document.getElementById("toggleDrawerBtn");
const _drawer = document.querySelector(".drawer");
const _content = document.querySelector(".wrapper__content");

/**
 * @function update user (if logged in) with setting for drawer
 * @param {Boolean} open - set drawer to open or close
 */
const updateUser = (open) => {
    const user = document.querySelector("body").dataset.userId;
    if (user) {
        window.axios.post("/api/drawer", { drawer: open }).catch((e) => {
            console.error(e);
        });
    }
};

/**
 * @function open drawer by manipulating dom
 */
const openDrawer = () => {
    updateUser(true);
    _drawer.classList.add("open");
    _toggleButton.classList.add("open");
    _content.classList.add("drawer-open");
};

/**
 * @function close drawer by manipulating dom
 */
const closeDrawer = () => {
    updateUser(false);
    _drawer.classList.remove("open");
    _toggleButton.classList.remove("open");
    _content.classList.remove("drawer-open");
};

/**
 * @function get current dom status of drawer
 */
const getDrawerStatus = () => {
    return _drawer.classList.contains("open");
};

/**
 * @function initialize drawer
 */
export const initDrawer = () => {
    console.log("initializing drawer");
    _toggleButton.addEventListener("click", () => {
        const isDrawerOpen = getDrawerStatus();
        if (isDrawerOpen) {
            closeDrawer();
        } else {
            openDrawer();
        }
    });
};
