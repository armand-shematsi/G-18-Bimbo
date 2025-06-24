import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/**/*.js',
        './resources/**/*.vue',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                primary: {
                    DEFAULT: '#4F46E5',
                    '50': '#EEEDFC',
                    '100': '#DCDAF9',
                    '200': '#B8B5F3',
                    '300': '#9490ED',
                    '400': '#706BE7',
                    '500': '#4F46E5',
                    '600': '#2A22D9',
                    '700': '#211BB0',
                    '800': '#181487',
                    '900': '#0F0D5E',
                    '950': '#08073D'
                }
            }
        },
    },

    plugins: [
        forms,
        require('@tailwindcss/typography'),
    ],
};
