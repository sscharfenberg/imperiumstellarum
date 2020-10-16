/******************************************************************************
 *
 * Eslint config
 * @type {NodeJS}
 *
 *****************************************************************************/
module.exports = {
    root: true,
    env: {
        node: true,
    },
    extends: ["eslint:recommended", "plugin:vue/vue3-essential"],
    plugins: ["import"],
    parserOptions: {
        parser: "babel-eslint",
    },
    rules: {
        "no-console": "off",
        "no-debugger": process.env.NODE_ENV === "production" ? "warn" : "off",
        indent: [2, 4, { SwitchCase: 1 }],
        // don"t require .vue extension when importing
        "import/extensions": [
            "error",
            "never",
            {
                json: "always",
                vue: "never",
            },
        ],
    },
    overrides: [
        {
            files: [
                "**/__tests__/*.{j,t}s?(x)",
                "**/tests/unit/**/*.spec.{j,t}s?(x)",
            ],
            env: {
                jest: true,
            },
        },
    ],
};
