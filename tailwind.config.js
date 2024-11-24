/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './resources/views/**/*.blade.php',
    ],
    safelist: [
        'text-green-500',
        'text-red-500',
        'ring-amber-400',
        'ring-2'
    ],
    theme: {
        screens: {
            'sm': '640px',
            'md': '768px',
            'lg': '1024px',
            'xl': '1280px',
            '2xl': '1440px',
        },
        extend: {
            fontFamily: {
                sans: ['Montserrat', 'sans-serif'],
                heading: ['Michroma', 'sans-serif'],
            },
        },
    },
    plugins: [],
}

