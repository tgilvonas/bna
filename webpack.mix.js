let mix = require('laravel-mix');

mix.js('public/assets-src/frontend/frontend-main.js', 'public/assets-dist/frontend')
    .js('public/assets-src/backend/editor.js', 'public/assets-dist/backend')
    .js('public/assets-src/backend/charts.js', 'public/assets-dist/backend')
    .sass('public/assets-src/frontend/frontend-main.scss', 'public/assets-dist/frontend')
    .sass('public/assets-src/backend/main.scss', 'public/assets-dist/backend')
    .css('public/assets-src/editor-styles.css', 'public/assets-dist');
