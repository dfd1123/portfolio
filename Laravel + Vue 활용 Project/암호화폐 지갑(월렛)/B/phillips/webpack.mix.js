const mix = require("laravel-mix");
const glob = require("glob");
const path = require("path");
// require('laravel-mix-bundle-analyzer');

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

mix.js("resources/js/app.js", "public/js");

if (process.env.npm_lifecycle_event !== "hot") {
    mix.version();
}

if (mix.inProduction()) {
    mix.extract();
} else {
    mix.sourceMaps();
    // mix.bundleAnalyzer();
}

mix.options({
    postCss: [require("autoprefixer")],
    hmrOptions: {
        host: "phillips.local",
        port: 8080
    }
});

mix.webpackConfig({
    output: {
        chunkFilename: "[name].js?id=[chunkhash]"
    }
});
