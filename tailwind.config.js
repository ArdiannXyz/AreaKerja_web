import flowbite from 'flowbite/plugin'
/** @type {import('tailwindcss').Config} */
export default {
    content: [
        "./resources/**/*.blade.php",
        "./resources/**/*.js",
        "./resources/**/*.vue",
    ],
    theme: {
        extend: {
            fontFamily: {
                poppins: ['Poppins', 'sans-serif'],
            },
            colors: {
                brand: {
                    DEFAULT: '#00509d',
                    hover: '#003d7a',
                    bg: '#0054a6',
                    light: '#e8f1fb',
                },
            }
        },
    },
    plugins: [
        require('flowbite/plugin'),
    ],
}