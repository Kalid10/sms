module.exports = {
    env: {
        browser: true,
        es2021: true,
    },
    extends: [
        "plugin:vue/vue3-recommended",
        "plugin:tailwindcss/recommended",
        "prettier",
    ],
    overrides: [],
    parserOptions: {
        ecmaVersion: "latest",
        sourceType: "module",
    },
    plugins: ["vue"],
    rules: {
        "vue/multi-word-component-names": "off",
        "vue/no-v-text-v-html-on-component": "off",
        "vue/no-setup-props-destructure": "off",
        "tailwindcss/no-custom-classname": "off",
        "vue/no-template-shadow": "off",
        "vue/no-mutating-props": "off",
        "vue/no-v-html":"off"
    },
};
