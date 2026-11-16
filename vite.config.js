import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css', 
                'resources/css/calendar.css', 
                'resources/css/activities.css',
                'resources/js/app.js', 
                'resources/js/notifications.js', 
                'resources/js/real-time-notifications.js',
                'resources/js/activities.js',
                'resources/js/volunteer-assistant.js'
            ],
            refresh: true,
        }),
    ],
});
