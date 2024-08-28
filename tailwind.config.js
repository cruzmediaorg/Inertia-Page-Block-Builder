import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './packages/cruzmediaorg/inertia-page-block-builder/resources/views/**/*.blade.php',
        './packages/cruzmediaorg/inertia-page-block-builder/resources/js/components/**/*.vue',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.vue',
        './resources/js/IPBB/Blocks/**/*.vue',
    ],
    variants:{
        extend: {
            opacity: ['group-hover'],
        }
    },
    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
        },
    },

    plugins: [forms],
};
