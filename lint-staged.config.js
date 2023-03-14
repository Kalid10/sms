module.exports = {
    "**/*.{js,vue}": ["npm run lint -- --fix"],
    "**/*.php": "./vendor/bin/pint",
};
