import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';
import colors from 'tailwindcss/colors'; 

/** @type {import('tailwindcss').Config} */
export default {
    // Garante que ele lê TODOS os seus ficheiros .blade.php
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php', // <-- Esta linha "vê" o auth/login.blade.php
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
            
            colors: {
                gray: colors.neutral,
                indigo: colors.neutral, 
                // Permite o uso de green e red
            },
        },
    },

    plugins: [forms],
};