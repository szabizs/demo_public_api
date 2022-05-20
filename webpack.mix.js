const mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel applications. By default, we are compiling the CSS
 | file for the application as well as bundling up all the JS files.
 |
 */

mix.js('resources/js/app.js', 'public/js')
    .vue()
    .postCss('resources/css/app.css', 'public/css', [
        require('postcss-import'),
        require('tailwindcss'),
        require('autoprefixer'),
    ])
    .alias({
        '@': 'resources/js',
    });

mix.webpackConfig({
    devServer: {
        host: '0.0.0.0',
        port: 8080,
    },
});

mix.webpackConfig({
    output: {
        // Overrides the default 0.0.0.0 host (from above) to use localhost when
        // creating URLs, since the 0.0.0.0 address doesn't work when the
        // HMR server reloads in Chrome
        publicPath: `http://localhost:8080/`,
    },
});

/** https://stackoverflow.com/questions/50587679/vue-loader-15-with-laravel-mix **/

if (mix.inProduction()) {
    mix.version()
    mix.disableNotifications()
}
