import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/sass/app.scss',
                'resources/js/app.js',
                'resources/css/becario-dashboard.css',
            ],
            refresh: true,
        }),
    ],
    css: {
        preprocessorOptions: {
            scss: {
                api: 'modern-compiler',
            },
        },
    },
    // AGREGUEN ESTA SECCIÓN PARA LIVE SHARE:
    server: {
        host: true, // Permite que Vite escuche en la red local
        strictPort: true,
        hmr: {
            host: 'localhost', // Mantiene la referencia al túnel de Live Share
        },
    },
});