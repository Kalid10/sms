const defaultTheme = require('tailwindcss/defaultTheme');

/** @type {import('tailwindcss').Config} */
module.exports = {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.vue',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
                basic: ['Inter'],
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
            },
            minHeight: {
                '8': '2rem',
                '10': '2.5rem',
            },
            animation: {
                'slide-down': 'slideDown 150ms ease-out forwards',
                'scale-up': 'scaleUp 150ms ease-out forwards',
            },
            keyframes: {
                slideDown: {
                    '0%': {transform: 'translateY(-100%)'},
                    '100%': {transform: 'translateY(0%)'}
                },
                scaleUp: {
                    '0%': {transform: 'scale(.5)'},
                    '100%': {transform: 'scale(1)'}
                }
            }
        },
    },

    plugins: [require('@tailwindcss/forms')],
};
