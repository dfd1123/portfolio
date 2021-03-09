const mix = require("laravel-mix");
const MomentLocalesPlugin = require('moment-locales-webpack-plugin');
require("laravel-mix-polyfill");
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

mix.js("resources/js/app.js", "public/js").polyfill({
    enabled: true,
    useBuiltIns: "usage",
    targets: false
});

mix.styles(
    [
        "resources/styles/lib/normalize_custom_head.css",
        "node_modules/normalize.css/normalize.css",
        "resources/styles/lib/normalize_custom_tail.css"
    ],
    "public/css/normalize-custom.css"
);

mix.options({
    processCssUrls: false,
    postCss: [require("autoprefixer")()],
    hmrOptions: {
        // HMR 모드일 때 localhost 이외의 HMR 서버에 접속 설정
        host: "j1beatz.local",
        port: 8080
    }
});

if (process.env.npm_lifecycle_event !== "hot") {
    // HMR 모드일 때 version, extract 함수 호출 시 작동오류
    if (mix.inProduction()) {
        mix.extract();
        mix.disableNotifications();
    } else {
        mix.sourceMaps();
        // mix.bundleAnalyzer();
    }

    mix.version();
}

mix.webpackConfig({
    plugins: [
        MomentLocalesPlugin({
            localesToKeep: ['ko'],
        })
    ],
    output: {
        // Dynamic Import 사용 시 chunk 캐싱 설정
        chunkFilename: "[name].js?id=[chunkhash]"
    }
});
