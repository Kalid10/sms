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
                brand: {
                    50: "rgb(233 213 255)",
                    100: "rgb(147 51 234)",
                }
            },
            minHeight: {
                '8': '2rem',
                '10': '2.5rem',
            },
            minWidth: {
                '8': '2rem',
                '10': '2.5rem',
            },
            maxWidth: {
                '8': '2rem',
                '10': '2.5rem',
            },
            animation: {
                'slide-down': 'slideDown 150ms ease-out forwards',
                'slide-left': 'slideLeft 150ms ease-out',
                'darken': 'darken 150ms ease-out forwards',
                'scale-up': 'scaleUp 150ms ease-out forwards',
            },
            keyframes: {
                slideDown: {
                    '0%': {transform: 'translateY(-100%)'},
                    '100%': {transform: 'translateY(0%)'}
                },
                slideLeft: {
                    '0%': {transform: 'translateX(100%)'},
                    '100%': {transform: 'translateX(0%)'}
                },
                scaleUp: {
                    '0%': {transform: 'scale(.5)'},
                    '100%': {transform: 'scale(1)'}
                },
                darken: {
                    '0%': {backgroundColor: '#FFFFFF0'},
                    '100%': {backgroundColor: '#00000025'}
                }
            }
        },
    },

    plugins: [require('@tailwindcss/forms')],
};
