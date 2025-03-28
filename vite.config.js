import { defineConfig } from 'vite';
import vue from '@vitejs/plugin-vue';
import { resolve } from 'path';

export default defineConfig({
    plugins: [
        vue(),
    ],
    build: {
        outDir: 'resources/dist',
        rollupOptions: {
            input: {
                'volet-app': resolve(__dirname, 'resources/js/volet.js'),
                'volet-default': resolve(__dirname, 'resources/css/volet.css')
            },
            output: {
                entryFileNames: '[name].js',
                assetFileNames: '[name][extname]'
            }
        }
    }
});
