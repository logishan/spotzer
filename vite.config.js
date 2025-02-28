import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import { viteStaticCopy } from 'vite-plugin-static-copy'

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css', 
                'resources/sass/app.scss',
                'resources/js/app.js',
                'resources/assets/js/select2.js',
                'resources/assets/js/index1.js',
                'resources/assets/js/apexcharts.js',
                'resources/assets/js/sticky.js'
            ],
            refresh: true,
        }),
        viteStaticCopy({
            targets: [
              {
                src: ([
                'resources/assets/images/', 
                'resources/assets/plugins/',
                'resources/assets/iconfonts/',
                'resources/assets/js/form-wizard.js',
            ]),
                dest: 'assets/'
              }
            ]
        }),
        {
            name: 'blade',
            handleHotUpdate({ file, server }) {
                if (file.endsWith('.blade.php')) {
                    server.ws.send({
                        type: 'full-reload',
                        path: '*',
                    });
                }
            },
        }
    ],
    build: {
        chunkSizeWarningLimit: 1600,
    },
});

