let mix = require('laravel-mix');

const webpackConfig = {
    resolve: {
        alias: {
            sass: path.resolve(__dirname, 'resources/assets/sass'),
            '@': path.resolve(__dirname, 'resources/assets/js')
        }
    }
}

const options = {
    // extractVueStyles: true,
    // globalVueStyles: 'normalize.css/normalize'
}

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

mix.js('resources/assets/js/app.js', 'public/js')
   .sass('resources/assets/sass/app.scss', 'public/css')
   .options(options)
   .webpackConfig(webpackConfig);
