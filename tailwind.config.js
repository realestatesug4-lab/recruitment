import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['DM Sans', ...defaultTheme.fontFamily.sans],
                dm: ['DM Sans', ...defaultTheme.fontFamily.sans],
                syne: ['Syne', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                cream: '#F5F2EC',
                deep: '#0D1117',
                forest: '#1B4332',
                sage: '#2D6A4F',
                mint: '#52B788',
                'pale-mint': '#D8F3DC',
                amber: '#F4A261',
                'text-dark': '#141C1A',
                'text-mid': '#4A5650',
                'text-light': '#8FA89E',
            },
        },
    },

    plugins: [forms],
};
