const defaultTheme = require('tailwindcss/defaultTheme')

/** @type {import('tailwindcss').Config} */
module.exports = {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/ts/**/*'
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Nunito', ...defaultTheme.fontFamily.sans]
            },
            keyframes: {
                appear: {
                    '0%': {
                        transform: 'scale(0.65)',
                        opacity: 0.3
                    },
                    '50%': {
                        transform: 'scale(0.85)',
                        opacity: 0.7
                    },
                    '100%': {
                        transform: 'scale(1)',
                        opacity: 1
                    }
                }
            },
            animation: {
                appear: 'appear 0.2s ease 0s forwards'
            }
        }
    },

    plugins: [require('@tailwindcss/forms')]
}
