import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import react from '@vitejs/plugin-react'; // ✅ add this

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.jsx', // ✅ use JSX entry point instead of app.js
            ],
            refresh: true,
        }),
        react(), // ✅ enable React plugin
    ],
});
