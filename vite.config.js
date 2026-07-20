import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
        tailwindcss(),
    ],
    // server: {
    //     host: '0.0.0.0',
    //     port: 5174, // Pastikan port sesuai dengan yang digunakan Vite (di kasusmu 5174)
    //     hmr: {
    //         host: '10.68.110.142', // Gunakan IP Kali Linux kamu
    //     },
    // },
});
