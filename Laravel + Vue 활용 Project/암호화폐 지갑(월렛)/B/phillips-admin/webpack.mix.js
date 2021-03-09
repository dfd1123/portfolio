const mix = require("laravel-mix");

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

mix.scripts(
    [
        "resources/vendor/js/jquery-3.3.1.min.js",
        "resources/vendor/js/jquery-confirm.min.js",
        "resources/vendor/js/slick.min.js",
        "resources/vendor/js/clipboard.min.js",
        "resources/vendor/js/pikaday.js", //데이터피커(캘린더) 플러그인 js
        "resources/vendor/js/jquery.timepicker.min.js", //타임피커(시간설정) 플러그인 js
        "resources/vendor/js/alertify.min.js",
        "resources/vendor/js/sweetalert.min.js"
    ],
    "public/js/vendor/vendor.js"
)

    .babel(
        ["resources/js/admin/common.js", "resources/js/admin/jw_modal.js"],
        "public/js/admin/common.js"
    ) // 관리자페이지 공통 js
    .babel(
        [
            "resources/js/admin/sb-admin.js",
            "resources/js/admin/register.js",
            "resources/js/admin/user.js",
            "resources/js/admin/coin_out.js",
            "resources/js/admin/ico.js",
            "resources/js/admin/coin_has_list.js"
        ],
        "public/js/admin/market_admin.js"
    ) // 관리자페이지 js
    .sass("resources/sass/app.scss", "public/css")
    .styles(
        [
            "resources/css/admin/sb-admin.css",
            "resources/css/admin/jw_modal.css",
            "resources/css/admin/settings.css"
        ],
        "public/css/admin/sb-admin.css"
    ) // 관리자페이지 css
    .styles(
        [
            "resources/vendor/css/jquery-confirm.min.css",
            "resources/vendor/css/alertify.core.css",
            "resources/vendor/css/alertify.default.css",
            "resources/vendor/css/pikaday.css", //데이터피커(캘린더) 플러그인 css
            "resources/vendor/css/jquery.timepicker.css", //타임피커(시간) 플러그인 css
            "resources/vendor/css/slick.css", //slick 플러그인 css
            "resources/vendor/css/slick-theme.css", // slick 테마 플러그인 css
            "resources/vendor/css/sweetalert.css"
        ],
        "public/css/vendor/lib.css"
    );
