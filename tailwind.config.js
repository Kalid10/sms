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
                    50: "#fafafa",
                    100: "#f4f4f5",
                    150: "#e4e4e7",
                    200: "#d4d4d8",
                    250: "#a1a1aa",
                    300: "#71717a",
                    350: "#52525b",
                    400: "#3f3f46",
                    450: "#27272a",
                    500: "#18181b",
                    550: "#09090b",
                },
                "brand-text": {
                    50: "#18181b",
                    100: "#27272a",
                    150: "#3f3f46",
                    200: "#52525b",
                    250: "#71717a",
                    300: "#a1a1aa",
                    350: "#d4d4d8",
                    400: "#e4e4e7",
                    450: "#f4f4f5",
                    500: "#fafafa",
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
