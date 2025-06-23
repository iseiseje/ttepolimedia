const defaultTheme = require('tailwindcss/defaultTheme');

/** @type {import('tailwindcss').Config} */
module.exports = {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    darkMode: 'class',

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
                urbanist: ['Urbanist', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                primary: '#1677ff',
                secondary: '#6b7280',
            }
        },
    },

    plugins: [require('@tailwindcss/forms')],
};
