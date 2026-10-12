import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/css/pages/movies.css',
                'resources/css/pages/showtimes.css',
                'resources/css/pages/select-seats.css',
                'resources/css/pages/checkout.css',
                'resources/css/pages/booking-history.css',
                'resources/css/pages/booking.css',
                'resources/css/pages/admin-movies.css',
                'resources/css/pages/admin-showtimes.css',
                'resources/js/app.js',
            ],
            refresh: true,
        }),
    ],
});
