import tailwindcss from "@tailwindcss/vite";
import laravel from "laravel-vite-plugin";
import { defineConfig } from "vite";

export default defineConfig({
    plugins: [
        laravel({
            input: ["resources/css/app.css", "resources/js/app.js"],
            refresh: true,
        }),
        tailwindcss(),
    ],
    server: {
        host: true, // penting
        hmr: {
            host: "https://max-thirstless-michal.ngrok-free.dev/", // ganti dengan domain ngrok
            protocol: "wss",
        },
    },
});
