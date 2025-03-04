import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    // server: {
    //   hmr: false,  // Disable Hot Module Replacement
    // },
    resolve: {
        alias: {
          '@js': '/resources/js/teacherTable',
        },
      },
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/css/breakPoints.css', 'resources/js/app.js', 'resources/js/navigation.js', 'resources/js/users.js'],
            refresh: true,
        }),
    ],
});

