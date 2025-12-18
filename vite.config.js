import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from "@tailwindcss/vite";
import viteImagemin from 'vite-plugin-imagemin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
            ],
            refresh: [`resources/views/**/*`],
        }),
        // Optimización de imágenes solo en producción
        viteImagemin({
            pngquant: {
                quality: [0.8, 0.9],
                speed: 4,
            },
            mozjpeg: {
                quality: 80,
                progressive: true,
            },
            gifsicle: {
                optimizationLevel: 3,
                interlaced: true,
            },
            svgo: {
                plugins: [
                    {
                        name: 'removeViewBox',
                        active: false,
                    },
                    {
                        name: 'removeEmptyAttrs',
                        active: true,
                    },
                ],
            },
            webp: {
                quality: 80,
            },
        }),
        tailwindcss(),
    ],
    server: {
        cors: true,
    },
});