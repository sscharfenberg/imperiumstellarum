/******************************************************************************
 *
 * Stylelint configuration and rules
 * @type {NodeJS}
 *
 *****************************************************************************/

module.exports = {
    processors: [
        [
            "@mapbox/stylelint-processor-arbitrary-tags",
            { fileFilterRegex: [/\.vue/] },
        ],
    ],
    extends: ["stylelint-config-standard", "stylelint-config-prettier"],
    plugins: ["stylelint-scss", "stylelint-order"],
    rules: {
        indentation: 4,
        "declaration-empty-line-before": null,
        "no-descending-specificity": null,
        "color-named": [
            "never",
            {
                ignore: ["inside-function"],
            },
        ],
        "no-empty-source": null,
        "at-rule-empty-line-before": [
            "always",
            {
                except: ["blockless-after-same-name-blockless", "first-nested"],
                ignore: ["after-comment"],
                ignoreAtRules: ["import"],
            },
        ],
        "media-feature-name-no-unknown": [
            true,
            {
                ignoreMediaFeatureNames: ["/device-pixel-ratio/"],
            },
        ],
        "at-rule-no-unknown": [
            true,
            {
                ignoreAtRules: [
                    "function",
                    "each",
                    "mixin",
                    "return",
                    "if",
                    "else",
                    "content",
                    "include",
                    "extend",
                    "warn",
                    "import-normalize",
                ],
            },
        ],
        "order/properties-order": [
            {
                emptyLineBefore: "always",
                properties: [
                    "display",
                    "position",
                    "top",
                    "right",
                    "bottom",
                    "left",
                    "z-index",
                    "float",
                    "clear",
                    "align-items",
                    "align-content",
                    "align-self",
                    "justify-content",
                    "flex-direction",
                    "flex-wrap",
                    "flex-flow",
                    "order",
                    "grid-area",
                    "grid-template",
                    "grid-template-areas",
                    "grid-template-columns",
                    "grid-template-rows",
                    "grid-auto-columns",
                    "grid-auto-rows",
                    "grid-auto-flow",
                    "grid-column",
                    "grid-column-start",
                    "grid-column-end",
                    "grid-row",
                    "grid-row-start",
                    "grid-row-end",
                    "visibility",
                    "backface-visibility",
                    "opacity",
                ],
            },
            {
                emptyLineBefore: "always",
                properties: [
                    "box-sizing",
                    "overflow",
                    "overflow-x",
                    "overflow-y",
                    "width",
                    "min-width",
                    "max-width",
                    "height",
                    "min-height",
                    "max-height",
                    "padding",
                    "padding-top",
                    "padding-right",
                    "padding-bottom",
                    "padding-left",
                    "border",
                    "border-top",
                    "border-right",
                    "border-bottom",
                    "border-left",
                    "border-width",
                    "border-top-width",
                    "border-right-width",
                    "border-bottom-width",
                    "border-left-width",
                    "margin",
                    "margin-top",
                    "margin-right",
                    "margin-bottom",
                    "margin-left",
                    "clip",
                    "clip-path",
                    "transform",
                    "transform-origin",
                    "flex",
                    "flex-grow",
                    "flex-shrink",
                    "flex-basis",
                    "zoom",
                    "grid",
                    "gap",
                    "grid-gap",
                    "grid-column-gap",
                    "grid-row-gap",
                ],
            },
            {
                emptyLineBefore: "always",
                properties: [
                    "background",
                    "background-color",
                    "background-image",
                    "background-repeat",
                    "background-attachment",
                    "background-position",
                    "background-position-x",
                    "background-position-y",
                    "background-clip",
                    "background-origin",
                    "background-size",
                    "backdrop-filter",
                    "filter",
                    "fill",
                    "fill-opacity",
                    "stroke",
                    "stroke-opacity",
                    "stroke-width",
                    "stroke-dasharray",
                    "stroke-dashoffset",
                    "shape-rendering",
                    "color",
                    "outline",
                    "outline-width",
                    "outline-style",
                    "outline-color",
                    "outline-offset",
                    "list-style",
                    "list-style-position",
                    "list-style-type",
                    "list-style-image",
                    "border-style",
                    "border-top-style",
                    "border-right-style",
                    "border-bottom-style",
                    "border-left-style",
                    "border-radius",
                    "border-top-left-radius",
                    "border-top-right-radius",
                    "border-bottom-left-radius",
                    "border-bottom-right-radius",
                    "border-color",
                    "border-top-color",
                    "border-right-color",
                    "border-bottom-color",
                    "border-left-color",
                    "box-shadow",
                    "cursor",
                    "resize",
                    "user-select",
                    "pointer-events",
                    "touch-action",
                ],
            },
            {
                emptyLineBefore: "always",
                properties: [
                    "font",
                    "font-family",
                    "font-size",
                    "font-style",
                    "font-weight",
                    "font-variant",
                    "font-size-adjust",
                    "font-stretch",
                    "font-effect",
                    "font-emphasize",
                    "font-emphasize-position",
                    "font-emphasize-style",
                    "font-smoothing",
                    "font-feature-settings",
                    "font-display",
                    "hyphens",
                    "src",
                    "line-height",
                    "text-align",
                    "text-align-last",
                    "text-emphasis",
                    "text-emphasis-color",
                    "text-emphasis-style",
                    "text-emphasis-position",
                    "text-decoration",
                    "text-decoration-skip",
                    "text-decoration-skip-ink:",
                    "text-indent",
                    "text-justify",
                    "text-outline",
                    "text-overflow",
                    "text-overflow-ellipsis",
                    "text-overflow-mode",
                    "text-shadow",
                    "text-transform",
                    "text-wrap",
                    "letter-spacing",
                    "word-spacing",
                    "letter-spacing",
                    "text-rendering",
                    "word-break",
                    "word-spacing",
                    "word-wrap",
                    "white-space",
                    "tab-size",
                    "text-overflow",
                    "vertical-align",
                    "direction",
                ],
            },
            {
                emptyLineBefore: "always",
                properties: [
                    "table-layout",
                    "border-collapse",
                    "border-spacing",
                    "vertical-align",
                    "empty-cells",
                    "caption-side",
                ],
            },
            {
                emptyLineBefore: "always",
                properties: [
                    "content",
                    "quotes",
                    "quotes",
                    "counter-reset",
                    "counter-increment",
                ],
            },
            {
                emptyLineBefore: "always",
                properties: [
                    "animation",
                    "animation-name",
                    "animation-duration",
                    "animation-timing-function",
                    "animation-delay",
                    "animation-iteration-count",
                    "animation-direction",
                    "animation-fill-mode",
                    "animation-play-state",
                    "transition",
                    "transition-delay",
                    "transition-timing-function",
                    "transition-duration",
                    "transition-property",
                ],
            },
        ],
    },
};
