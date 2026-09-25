import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    darkMode: 'class',

    theme: {
        extend: {
            fontFamily: {
                sans: ['Inter', ...defaultTheme.fontFamily.sans],
                display: ['"Baloo 2"', ...defaultTheme.fontFamily.sans],
                mono: ['"IBM Plex Mono"', ...defaultTheme.fontFamily.mono],
            },
            colors: {
                amber: {
                    50: '#fdf6e8', 100: '#fbe9c2', 200: '#f6d78e', 300: '#f6c453',
                    400: '#f5b83a', 500: '#f5a623', 600: '#d9820a', 700: '#b06908',
                    800: '#8a5307', 900: '#6b4106',
                },
                slate: {
                    950: '#0b0d12', 900: '#12141a', 850: '#14171f', 800: '#181b24',
                },
            },
            boxShadow: {
                glow: '0 8px 24px rgba(245, 166, 35, 0.28)',
            },
        },
    },

    plugins: [forms],
};
