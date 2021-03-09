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
/*
mix.js('resources/js/app.js', 'public/js')
    .sass('resources/sass/app.scss', 'public/css')

*/
mix.js('resources/js/app.js', 'public/js')



    //KJS추가
    //.js('resources/adminassets/plugins/*', 'public/adminaseets/plugins/')
    
    .styles([
        "resources/css/admin_ver/animate.css",
        "resources/css/admin_ver/material.css",
        "resources/css/admin_ver/spinners.css",
        "resources/css/admin_ver/style.css",
    ],  "public/css/admin_ver/common.css")
    //KJS추가끝
    
    
    
    .js('resources/js/common.js', 'public/js/common.js')
    .sass('resources/sass/app.scss', 'public/css')
    .styles(
        [
            "resources/css/fonts/spoqahans.css",
        ],
        "public/css/fonts/spoqahans.css"
    )
    .styles(
        [
            "resources/css/user_ver/reset.min.css",
            "resources/css/user_ver/normalize.css",
            "resources/css/user_ver/common.css",
            "resources/css/user_ver/air-datepicker.css",
        ],
        "public/css/user_ver/common.css"
    )
    .styles(
        [
            "resources/css/vendor/swiper.min.css",
            "resources/css/vendor/animate.css",
            "resources/css/vendor/sweetalert.css",
            "resources/css/vendor/datepicker.min.css",
            "resources/css/vendor/jquery-ui.css",
            "resources/css/vendor/jquery-ui.css",
        ],
        "public/css/user_ver/vendor.css"
    )
    .styles("resources/css/user_ver/header.css", "public/css/user_ver/header.css")
    .styles("resources/css/user_ver/estimate_request.css", "public/css/user_ver/estimate_request.css")
    .styles("resources/css/user_ver/estimate_manage.css", "public/css/user_ver/estimate_manage.css")
    .styles("resources/css/user_ver/result_confirm.css", "public/css/user_ver/result_confirm.css")
    .styles("resources/css/user_ver/company_page.css", "public/css/user_ver/company_page.css")
    .styles("resources/css/user_ver/corporation_status.css", "public/css/user_ver/corporation_status.css")
    .styles("resources/css/user_ver/main.css", "public/css/user_ver/main.css")
    .styles("resources/css/user_ver/ask_estimate.css", "public/css/user_ver/ask_estimate.css")
    .styles("resources/css/user_ver/construct_status.css", "public/css/user_ver/construct_status.css")
    .styles("resources/css/user_ver/request_complete.css", "public/css/user_ver/request_complete.css")
    .styles("resources/css/login.css", "public/css/login.css")
    .styles("resources/css/register.css", "public/css/register.css")
    .babel(
        [
            "resources/js/user_ver/common.js",
        ],
        "public/js/user_ver/common.js"
    )
    .babel(
        [
            "resources/js/vendor/jquery-3.4.1.min.js",
            "resources/js/vendor/swiper.min.js",
            "resources/js/vendor/sweetalert.min.js",
            "resources/js/vendor/datepicker.min.js",
            "resources/js/vendor/i18n/datepicker.en.js",
            "resources/js/vendor/jquery-ui.min.js",
            "resources/js/vendor/jQuery.longclickDetector.js",
            "resources/js/vendor/wow.min.js",
        ],
        "public/js/user_ver/vendor.js"
    )
    .styles(
        [
            "resources/css/company_ver/reset.min.css",
            "resources/css/company_ver/normalize.css",
            "resources/css/company_ver/common.css",
            "resources/css/company_ver/air-datepicker.css",
        ],
        "public/css/company_ver/common.css"
    )
    .styles(
        [
            "resources/css/company_ver/company_regist.css",
        ],
        "public/css/company_ver/company_regist.css"
    )
    .styles(
        [
            "resources/css/company_ver/company_signup.css",
        ],
        "public/css/company_ver/company_signup.css"
    )
    .styles(
        [
            "resources/css/company_ver/company_login.css",
        ],
        "public/css/company_ver/company_login.css"
    )
    .styles(
        [
            "resources/css/company_ver/company_construction.css",
        ],
        "public/css/company_ver/company_construction.css"
    )
    .styles(
        [
            "resources/css/user_ver/result_confirm.css",
            "resources/css/company_ver/company_bidding_detail.css",
        ],
        "public/css/company_ver/company_bidding_detail.css"
    )
    .styles("resources/css/company_ver/company_bidding.css", "public/css/company_ver/company_bidding.css")
    .styles("resources/css/company_ver/company_bidding_regist.css", "public/css/company_ver/company_bidding_regist.css")
    .styles("resources/css/company_ver/company_myagent.css", "public/css/company_ver/company_myagent.css")
    .styles("resources/css/admin_ver/common.css", "public/css/admin_ver/common.css")
    .babel(
        [
            "resources/js/company_ver/common.js",
        ],
        "public/js/company_ver/common.js"
    );
