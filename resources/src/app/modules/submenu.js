/******************************************************************************
 *
 * submenu
 *
 *****************************************************************************/
const SELECTOR_SUBMENU = ".submenu";
const SELECTOR_SUBMENU_TRIGGER = ".has-submenu";
const CLASSNAME_OPEN = "open";
const CLASSNAME_OPENING = "opening";
const CLASSNAME_CLOSING = "closing";
const ANIMATION_DURATION = 150; // needs to be synched to @keyframes in resources/src/app/styles/components/_submenu.scss

/**
 * @function hide a submenu by link
 * @param {HTMLElement} triggerNode - link that should be manipulated.
 */
const hideSubmenu = (triggerNode) => {
    const menu = getSubmenuByTrigger(triggerNode);
    menu.classList.add(CLASSNAME_CLOSING);
    triggerNode.classList.remove(CLASSNAME_OPEN);
    setTimeout(() => {
        menu.classList.remove(
            CLASSNAME_OPEN,
            CLASSNAME_CLOSING,
            CLASSNAME_OPENING
        );
        menu.setAttribute("aria-hidden", "true");
    }, ANIMATION_DURATION);
};

/**
 * @function show a submenu by link
 * @param {HTMLElement} triggerNode - link that should be manipulated.
 */
const showSubmenu = (triggerNode) => {
    const menu = getSubmenuByTrigger(triggerNode);
    triggerNode.classList.add(CLASSNAME_OPEN);
    menu.classList.add(CLASSNAME_OPEN, CLASSNAME_OPENING);
    setTimeout(() => {
        menu.classList.remove(CLASSNAME_OPENING);
        menu.setAttribute("aria-hidden", "false");
    }, ANIMATION_DURATION);
};

/**
 * @function get submenu by link
 * @param {HTMLElement} triggerNode - DOMNode of the link
 * @return {HTMLElement} submenu
 */
const getSubmenuByTrigger = (triggerNode) => {
    return triggerNode.parentNode.querySelector(SELECTOR_SUBMENU);
};

/**
 * @function initialize dropdowns
 */
export const initSubmenus = () => {
    const submenus = document.querySelectorAll(SELECTOR_SUBMENU);
    const triggers = document.querySelectorAll(SELECTOR_SUBMENU_TRIGGER);
    console.log("initializing submenus");

    // fail silently if no submenus, no triggers or # of triggers/submenus is different
    if (
        submenus.length === 0 ||
        triggers.length === 0 ||
        submenus.length !== triggers.length
    )
        return;

    for (let i = 0; i < triggers.length; i++) {
        // click event handler for each submenu trigger
        triggers[i].onclick = function () {
            let openMenuLinks = document.querySelectorAll(
                SELECTOR_SUBMENU + "." + CLASSNAME_OPEN
            );
            // was it open before click?
            if (!this.classList.contains(CLASSNAME_OPEN)) {
                // if there where open menus before click
                if (openMenuLinks.length > 0) {
                    // loop open menus
                    for (let j = 0; j < openMenuLinks.length; j++) {
                        hideSubmenu(openMenuLinks[j]); // and hide them
                    }
                }
                showSubmenu(this); // show this submenu
            } else {
                hideSubmenu(this); // hide this submenu
            }
        };
    }
};
