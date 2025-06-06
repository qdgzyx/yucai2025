// const mix = require('laravel-mix');

// /*
//  |--------------------------------------------------------------------------
//  | Mix Asset Management
//  |--------------------------------------------------------------------------
//  |
//  | Mix provides a clean, fluent API for defining some Webpack build steps
//  | for your Laravel applications. By default, we are compiling the CSS
//  | file for the application as well as bundling up all the JS files.
//  |
//  */

// mix.js('resources/js/app.js', 'public/js')
//     .postCss('resources/css/app.css', 'public/css', [
//         //
//     ]).version()
//    .copyDirectory('resources/editor/js', 'public/js')
//    .copyDirectory('resources/editor/css', 'public/css');
const mix = require('laravel-mix');
const webpack = require('webpack');

mix.js('resources/js/app.js', 'public/js')
.sass('resources/sass/app.scss', 'public/css').version().copyDirectory('resources/editor/js', 'public/js')
   .copyDirectory('resources/editor/css', 'public/css')
.webpackConfig({
plugins: [
new webpack.ProvidePlugin({
$: 'jquery',
jQuery: 'jquery',
'window.$': 'jquery',
'window.jQuery': 'jquery',
}),
],
});