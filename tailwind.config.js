import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    darkMode: 'class',
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                // Colores para pollería - Modo claro
                polleria: {
                    50: '#fef7ee',
                    100: '#fdedd3',
                    200: '#fad7a5',
                    300: '#f6b96d',
                    400: '#f19332',
                    500: '#ed7512',
                    600: '#de5a08',
                    700: '#b84209',
                    800: '#93350f',
                    900: '#772d10',
                    950: '#401405',
                },
                // Colores para pollería - Modo oscuro (azul marino)
                'polleria-dark': {
                    50: '#eef5ff',
                    100: '#d9e8ff',
                    200: '#bcd7ff',
                    300: '#8ebeff',
                    400: '#599aff',
                    500: '#3375ff',
                    600: '#1b52f5',
                    700: '#143de1',
                    800: '#1733b6',
                    900: '#192f8f',
                    950: '#0f1a42',
                },
            },
        },
    },

    plugins: [forms],
};
