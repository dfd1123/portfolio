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
mix.scripts(
    [
        "resources/js/allcoinpay.js",
        "resources/js/custom.min.js",
    ],
    "public/js/app.js"
)
.babel(
    [
        "resources/js/landing/jquery-ui.min.js",
        "resources/js/landing/jquery.transit.min.js",
        "resources/js/landing/swiper.min.js",
        "resources/js/landing/layout_3.js",
    ],
    "public/js/landing.js"
) 
.styles(
    [
    	"resources/css/custom.min.2.css",
        "resources/css/admin_style12.css",
        "resources/css/allcoin_pay_mobile3.css",
        "resources/css/my_page.css",
    ],
    "public/css/app.css"
)
.styles(
    [
    	"resources/css/login_style2.css",
    ],
    "public/css/login.css"
)
.styles(
    [
    	"resources/css/Payment_step1_style.css",
    ],
    "public/css/payment_window.css"
)
.styles(
    [
    	"resources/css/landing/common.css",
        "resources/css/landing/pc.css",
        "resources/css/landing/tap.css",
        "resources/css/landing/m_1.css",
        "resources/css/landing/swiper.css",
    ],
    "public/css/landing.css"
) 
/*mix.js('resources/js/app.js', 'public/js')
    .sass('resources/sass/app.scss', 'public/css');*/
