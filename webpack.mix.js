const mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */

// Application styles and scripts
mix.js('resources/js/app.js', 'public/js')
   .sass('resources/sass/app.scss', 'public/css');

// Custom styles and scripts
mix.js(['resources/js/common.js', 'resources/js/lizard.js'], 'public/js/lizard.js')
    .sass('resources/sass/lizard.scss', 'public/css')
    .version();

// admin styles  and scripts
mix.js(['resources/js/common.js', 'resources/js/admin.js'], 'public/js/admin.js')
    .sass('resources/sass/admin.scss', 'public/css');
