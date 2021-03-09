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

mix.styles(
        [
            "resources/css/vendor/animate.min.css",
            "resources/css/vendor/jw_modal.css",
            "resources/css/vendor/slick.css",
            "resources/css/vendor/swiper.css",
            "resources/css/vendor/slick-theme.css",
            "resources/css/vendor/jquery.bxslider.css",
        ],
        "public/css/vendor/vendor.css"
    ) // vendor css
   .styles(
        [
            "resources/css/admin/sb-admin.css",
            "resources/css/vendor/jw_modal.css",
        ],
        "public/css/admin/sb-admin.css"
    ) // 관리자페이지 css
    .styles(
        [
            "resources/css/pc/board.css",
            "resources/css/pc/common.css",
            "resources/css/pc/content.css",
            "resources/css/pc/layout.css",
            "resources/css/pc/member.css",
            "resources/css/pc/mypage.css",
            "resources/css/pc/product.css",
        ],
        "public/css/pc/tellusart.css"
    ) // PC버전 css
    .styles(
        [
            "resources/css/pc/diffrent_hd.css",
        ],
        "public/css/pc/diffrent_hd.css"
    )
    .styles(
        [
            "resources/css/mobile/common.css",
            "resources/css/mobile/content.css",
            "resources/css/mobile/layout.css",
            "resources/css/mobile/comm_layout.css",
            "resources/css/mobile/main.css",
            "resources/css/mobile/order.css",
            "resources/css/mobile/member.css",
            "resources/css/mobile/coin.css",
            "resources/css/mobile/create.css",
            "resources/css/mobile/betting.css",
            "resources/css/mobile/product.css",
            "resources/css/mobile/credit.css",
            "resources/css/mobile/board.css",
            "resources/css/mobile/privacy.css",
        ],
        "public/css/mobile/tellusart.css"
    ) // Mobile 버전
    .styles(
        [
            "resources/css/vendor/mobile/swiper.css",
            "resources/css/vendor/mobile/swiper_main.css",
            "resources/css/vendor/jquery.bxslider.css",
        ],
        "public/css/vendor/vendor_mobile.css"
    ) // Mobile 버전
    .babel(
        [
            "resources/js/admin/sb-admin.js",
            "resources/js/admin/result_calculate.js",
            "resources/js/admin/admin_user.js",
        ],
        "public/js/admin/admin.js"
    ) // 관리자페이지 js
    .babel(
        [
			"resources/js/pc/common.js",
            "resources/js/pc/faq.js",
            "resources/js/pc/mypage.js",
            "resources/js/pc/order.js",
            "resources/js/pc/product.js",
            "resources/js/pc/product_struct.js",
            "resources/js/pc/review_struct.js",
        ],
        "public/js/pc/platform.js"
    ) // PC js
    .scripts(
        [
            "resources/js/vendor/jquery-1.8.3.min.js",
            "resources/js/vendor/jquery.easing.1.3.js",
			"resources/js/vendor/html5shiv.js",
            "resources/js/vendor/imagesloaded.pkgd.min.js",
            "resources/js/vendor/modernizr.custom.js",
            "resources/js/vendor/respond.src.js",
            "resources/js/vendor/masonry/jquery.masonry.min.js",
            "resources/js/vendor/modal/jquery.leanModal.min.js",
            "resources/js/vendor/slick/slick.min.js",
            "resources/js/vendor/touchslide/jquery.event.drag-1.5.min.js",
            "resources/js/vendor/touchslide/jquery.touchSlider.js",
            "resources/js/vendor/jquery.bxslider.js",
        ],
        "public/js/vendor/vendor.js"
    ) // PC vendor js
    .babel(
        [
            "resources/js/mobile/common.js",
			"resources/js/mobile/menu.js",
            "resources/js/mobile/leftmenu.js",
            "resources/js/mobile/poppy.js",
            "resources/js/mobile/mypage.js",
            "resources/js/mobile/product.js",
            "resources/js/mobile/order.js",
            "resources/js/mobile/board.js",
        ],
        "public/js/mobile/platform.js"
    ) // Mobile js
    .scripts(
        [
            "resources/js/vendor/jquery-1.11.3.js",
            "resources/js/vendor/imagesloaded.pkgd.min.js",
            "resources/js/vendor/masonry/jquery.masonry.min.js",
            "resources/js/vendor/swiper/swiper.min.js",
            "resources/js/vendor/modal/jquery.leanModal.min.js",
            "resources/js/vendor/jquery.bxslider.js",
        ],
        "public/js/vendor/vendor_mobile.js"
    ) // Mobile vendor js
    .scripts(
        [
            "resources/js/pc/review_infinite_scroll.js",
            "resources/js/pc/review_struct.js",
        ],
        "public/js/pc/review_infinite_scroll.js"
    )
    .scripts(
        [
            "resources/js/pc/glist_infinite_scroll.js",
            "resources/js/pc/product_struct.js",
        ],
        "public/js/pc/glist_infinite_scroll.js"
    )
    .scripts(
        [
            "resources/js/pc/blist_infinite_scroll.js",
            "resources/js/pc/product_struct.js",
        ],
        "public/js/pc/blist_infinite_scroll.js"
    )
    .scripts(
        [
            "resources/js/pc/sblist_infinite_scroll.js",
            "resources/js/pc/product_struct.js",
        ],
        "public/js/pc/sblist_infinite_scroll.js"
    )
    .scripts(
        [
            "resources/js/mobile/glist_infinite_scroll.js",
            "resources/js/mobile/product_struct.js",
        ],
        "public/js/mobile/glist_infinite_scroll.js"
    )
    .scripts(
        [
            "resources/js/mobile/sblist_infinite_scroll.js",
            "resources/js/mobile/product_struct.js",
        ],
        "public/js/mobile/sblist_infinite_scroll.js"
    )
    ;