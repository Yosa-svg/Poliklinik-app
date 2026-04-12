/** @type {import('tailwindcss').Config} */
import daisyui from "daisyui";
export default {
    content: [
        "./resources/**/*.blade.php",
        "./resources/**/*.js",
        "./resources/**/*.vue",
    ],
    theme: {
        extend: {
            colors: {
                "primary-blue": "#0066cc",
                "light-blue": "#e6f2ff",
                "dark-blue": "#004499",
            },
            spacing: {
                128: "32rem",
            },
            fontSize: {
                xs: "0.75rem",
            },
        },
    },
    plugins: [daisyui],
};
