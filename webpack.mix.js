const mix = require('laravel-mix');
const webpack = require('webpack');
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
 plugins: [
    new webpack.ProvidePlugin({
    $: 'jquery',
    jQuery: 'jquery',
    'window.jQuery': 'jquery'
    }),
    ]
/* mix.js('resources/js/app.js', 'public/js')
    .js('resources/js/dashboard.js', 'public/js')
    .js('resources/js/development.js', 'public/js')
    .js('resources/js/ckeditor/ckeditor.js', 'public/js/ckeditor')
    .js('resources/js/tabs.js', 'public/js')
    .js('resources/js/foundation.js', 'public/js')
    .js('resources/js/communication.js', 'public/js')
    .sass('resources/sass/app.scss', 'public/css')
    .sass('resources/sass/dashboard.scss', 'public/css')
    .sass('resources/sass/login.scss', 'public/css')
    .sass('resources/sass/footer.scss', 'public/css')
    .sass('resources/sass/menu.scss', 'public/css')
    .sass('resources/sass/dark-footer.scss', 'public/css')
    .sass('resources/sass/light-menu.scss', 'public/css')
    .sass('resources/sass/general.scss', 'public/css')
    .sass('resources/sass/us.scss', 'public/css')
    .sass('resources/sass/development.scss', 'public/css')
    .sass('resources/sass/organigrama.scss', 'public/css')
    .sass('resources/sass/collaborators.scss',  'public/css')
    .sass('resources/sass/benefits.scss',  'public/css')
    .sass('resources/sass/blog.scss',  'public/css')
    .sass('resources/sass/foundation.scss',  'public/css')
    .sass('resources/sass/profile.scss',  'public/css')
    .sass('resources/sass/directory.scss',  'public/css')
    .sass('resources/sass/menu_admin.scss',  'public/css')
    .sourceMaps(); */
