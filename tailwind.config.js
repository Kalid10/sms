const defaultTheme = require("tailwindcss/defaultTheme");

/** @type {import('tailwindcss').Config} */
module.exports = {
    content: [
        "./vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php",
        "./storage/framework/views/*.php",
        "./resources/views/**/*.blade.php",
        "./resources/js/**/*.vue",
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ["Figtree", ...defaultTheme.fontFamily.sans],
                basic: ["Inter"],
            },
            colors: {
                positive: {
                    50: "#84CC16",
                    100: "#51A928",
                },
                negative: {
                    50: "#DC2626",
                    100: "#FC0000",
                },
                default: {
                    50: "#FDE047",
                    100: "#FACC15",
                },
                brand: {
                    50: "#f5f3ff",
                    100: "#ede9fe",
                    150: "#ddd6fe",
                    200: "#c4b5fd",
                    250: "#a78bfa",
                    300: "#8b5cf6",
                    350: "#7c3aed",
                    400: "#6d28d9",
                    450: "#5b21b6",
                    500: "#581c87",
                    550: "#3b0764",
                },
                "brand-text": {
                    50: "#3b0764",
                    100: "#581c87",
                    150: "#5b21b6",
                    200: "#6d28d9",
                    250: "#7c3aed",
                    300: "#8b5cf6",
                    350: "#a78bfa",
                    400: "#c4b5fd",
                    450: "#ddd6fe",
                    500: "#f5f3ff",
                },
            },
            minHeight: {
                8: "2rem",
                10: "2.5rem",
            },
            minWidth: {
                8: "2rem",
                10: "2.5rem",
            },
            maxWidth: {
                8: "2rem",
                10: "2.5rem",
            },
            animation: {
                "slide-down": "slideDown 150ms ease-out forwards",
                "slide-left": "slideLeft 150ms ease-out",
                darken: "darken 150ms ease-out forwards",
                "scale-up": "scaleUp 150ms ease-out forwards",
                "fade-in": "fadeIn 500ms linear",
                blink: "blink 1s infinite",
            },
            keyframes: {
                slideDown: {
                    "0%": { transform: "translateY(-100%)" },
                    "100%": { transform: "translateY(0%)" },
                },
                slideLeft: {
                    "0%": { transform: "translateX(100%)" },
                    "100%": { transform: "translateX(0%)" },
                },
                scaleUp: {
                    "0%": { transform: "scale(.5)" },
                    "100%": { transform: "scale(1)" },
                },
                darken: {
                    "0%": { backgroundColor: "#FFFFFF0" },
                    "100%": { backgroundColor: "#00000025" },
                },
                fadeIn: {
                    "0%": { opacity: "0%" },
                    "12.5%": { opacity: "12.5%" },
                    "25%": { opacity: "25%" },
                    "100%": { opacity: "100%" },
                },
                blink: {
                    "0%, 100%": { opacity: 1 },
                    "50%": { opacity: 0 },
                },
            },
        },
    },

    plugins: [require("@tailwindcss/forms")],
};
