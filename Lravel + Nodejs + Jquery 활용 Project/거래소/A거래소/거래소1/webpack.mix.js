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

mix .styles(
        [
            "resources/css/vendor/jquery-confirm.min.css",
            "resources/css/vendor/alertify.core.css",
            "resources/css/vendor/alertify.default.css",
            "resources/css/vendor/pikaday.css", //데이터피커(캘린더) 플러그인 css
            "resources/css/vendor/jquery.timepicker.css", //타임피커(시간) 플러그인 css
            "resources/css/vendor/slick.css", //slick 플러그인 css
            "resources/css/vendor/slick-theme.css", // slick 테마 플러그인 css
            "resources/css/vendor/sweetalert.css"
        ],
        "public/css/vendor/vendor.css"
    )
    .scripts(
        [
            "resources/js/vendor/jquery-3.4.1.min.js",
            "resources/js/vendor/jquery-confirm.min.js",
            "resources/js/vendor/slick.min.js",
            "resources/js/vendor/clipboard.min.js",
            "resources/js/vendor/pikaday.js", //데이터피커(캘린더) 플러그인 js
            "resources/js/vendor/jquery.timepicker.min.js", //타임피커(시간설정) 플러그인 js
            "resources/js/vendor/alertify.min.js",
            "resources/js/vendor/sweetalert.min.js"
        ],
        "public/js/vendor/vendor.js"
    )
    /*거래소 node 시작*/
    .babel(
        [
            "resources/js/pc/custom/market/ws_market.js",
            "resources/js/pc/custom/market/market.js",
            "resources/js/pc/custom/market/trading_view.js"
        ],
        "public/js/pc/original/market.js"
    )
    .babel(
        [
            "resources/js/theme/basic/pc/custom/market_btc/ws_market.js",
            "resources/js/theme/basic/pc/custom/market_btc/market.js",
        ],
        "public/js/pc/original/market_btc.js"
    )
    .babel(
        [
            "resources/js/mobile/custom/market/ws_market.js",
            "resources/js/mobile/custom/market/market.js",
            "resources/js/mobile/custom/market/trading_view.js"
        ],
        "public/js/mobile/original/market.js"
    )
    .babel(
        [
            "resources/js/mobile/custom/market_btc/ws_market.js",
            "resources/js/mobile/custom/market_btc/market.js",
        ],
        "public/js/mobile/original/market_btc.js"
    )
    .js(
        ["resources/js/pc/custom/home_new.js"],
        "public/js/pc/original/home_new.js"
    )
    .js(
        ["resources/js/mobile/custom/home_new.js"],
        "public/js/mobile/original/home_new.js"
    )
    /*거래소 node 끝*/
    .babel(
        ["resources/js/admin/common.js", "resources/js/admin/jw_modal.js"],
        "public/js/admin/common.js"
    )
    /* 관리자 JS, CSS 시작 */
    // 관리자페이지 공통 js
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
    .styles(
        [
            "resources/css/admin/sb-admin.css",
            "resources/css/admin/jw_modal.css",
            "resources/css/admin/settings.css"
        ],
        "public/css/admin/sb-admin.css"
    ) // 관리자페이지 css
    /* 관리자 JS,CSS 끝 */

    /* 거래소 PC버전 JS,CSS 시작  */
    .styles(
        [
            "resources/css/pc/original/market.css", //거래소 스타일
            "resources/css/pc/original/market_media.css", //거래소 1080px까지 반응형
            "resources/css/pc/original/auth.css", //로그인,회원가입,비밀번호 찾기,otp2차로그인 스타일
            "resources/css/pc/original/trans_wallet.css", //입출금 스타일
            "resources/css/pc/original/my_asset.css", //내 자산 스타일
            "resources/css/pc/original/notice.css", //공지사항 스타일
            "resources/css/pc/original/my_page.css", //마이페이지 스타일
            "resources/css/pc/original/p2p.css", //p2p(장외거래) 스타일
            "resources/css/pc/original/ico.css", //ico 스타일
            "resources/css/pc/original/p2p_ico_nav.css", //p2p 및 ico 왼쪽네비게이션 스타일 
            "resources/css/pc/original/banner.css", // p2p 및 ico 배너 스타일
        ],
        "public/css/pc/original/basic_market.css"
    ) // theme/basic/pc CSS
    .styles(
        [
            "resources/css/pc/original/kr.css"
        ],
        "public/css/pc/original/kr.css"
    )
    .styles(
        [
            "resources/css/pc/original/jp.css"
        ],
        "public/css/pc/original/jp.css"
    )
    .styles(
        [
            "resources/css/pc/original/ch.css"
        ],
        "public/css/pc/original/ch.css"
    )
    .styles(
        [
            "resources/css/pc/original/en.css"
        ],
        "public/css/pc/original/en.css"
    )
    /* SPOWIDE 거래소 CSS 시작 */
    .styles(
        [
            "resources/css/vendor/normalize.css",
            "resources/css/pc/common.css", //공통속성 및 폰트속성
            "resources/css/pc/header.css", //header 스타일링
            "resources/css/pc/footer.css", //footer 스타일링
        ],
        "public/css/pc/spowide.css"
    )
    .styles("resources/css/pc/main.css", "public/css/pc/main.css") //메인페이지 스타일링
    .styles("resources/css/pc/mypage.css", "public/css/pc/mypage.css") //메인페이지 스타일링
    .styles("resources/css/pc/register.css", "public/css/pc/register.css") //메인페이지 스타일링
    .styles("resources/css/pc/login.css", "public/css/pc/login.css") //메인페이지 스타일링
    .styles("resources/css/pc/password.css", "public/css/pc/password.css") //메인페이지 스타일링
    /* SPOWIDE 거래소 CSS 끝 */
    .babel(
        [
            'resources/js/pc/main_slide.js',
            'resources/js/pc/modal.js'
        ], 
        "public/js/pc/main_slide.js"
    )
    .babel(
        [
            "resources/js/pc/common/common.js",
            "resources/js/pc/custom/main/slick_custom.js",
            "resources/js/pc/custom/market_ui.js",
            "resources/js/pc/custom/transWallet.js",
            "resources/js/pc/custom/my_asset.js", //내자산 > 기간검색 js
            "resources/js/pc/custom/notice.js", // 고객센터 js
            "resources/js/pc/custom/p2p.js", // p2p장외거래 js
            "resources/js/pc/custom/my_page.js", // 마이페이지 js
            "resources/js/pc/custom/popup.js", //팝업 js

            "resources/js/pc/custom/p2p_ico_nav.js", //p2p 및 ico 왼쪽네비게이션 js
            "resources/js/pc/custom/register.js", //회원가입 js
            "resources/js/pc/custom/ico_create.js", //ico 작성페이지 js
            "resources/js/pc/custom/security_lv.js" //기존회원 보안인증 js
        ],
        "public/js/pc/original/basic.js"
    ) // basic 거래소 통합 js
    /* 거래소 PC버전 JS,CSS 끝  */

    /* 거래소 MOBILE버전 JS,CSS 시작  */
    .styles(
        [
            "resources/css/mobile/original/normalize.css", //초기화
            "resources/css/mobile/original/common.css", //공통속성
            "resources/css/mobile/original/header.css", //헤더속성
            "resources/css/mobile/original/main.css", //메인페이지 스타일
            "resources/css/mobile/original/auth.css", //로그인,회원가입,비밀번호 찾기,otp2차로그인 스타일
            "resources/css/mobile/original/market.css", //거래소 스타일
            "resources/css/mobile/original/trans_wallet.css", //입출금 스타일
            "resources/css/mobile/original/my_asset.css", //내자산 스타일
            "resources/css/mobile/original/notice.css", //고객센터-공지사항 스타일
            "resources/css/mobile/original/my_page.css", //마이페이지 스타일
            "resources/css/mobile/original/ico.css", //ICO 스타일
            "resources/css/mobile/original/p2p.css", //p2p 스타일
            "resources/css/mobile/original/sweetalert_mobile.css", //alert 스타일
        ],
        "public/css/mobile/original/m_basic_market.css"
    )
    .styles(
        [
            "resources/css/mobile/original/kr.css"
        ],
        "public/css/mobile/original/kr.css"
    )
    .styles(
        [
            "resources/css/mobile/original/jp.css"
        ],
        "public/css/mobile/original/jp.css"
    )
    .styles(
        [
            "resources/css/mobile/original/ch.css"
        ],
        "public/css/mobile/original/ch.css"
    )
    .styles(
        [
            "resources/css/mobile/original/en.css"
        ],
        "public/css/mobile/original/en.css"
    )
    .babel(
        ["resources/js/common/mobile/register.js"],
        "public/js/common/mobile/market.js"
    ) //모바일버전 프랜차이즈 거래소 공통 회원가입 js
    .babel(
        [
            "resources/js/mobile/common/common.js", //filter 등등 js
            "resources/js/mobile/custom/market/market_ui.js", //거래소 js
            "resources/js/mobile/custom/trans_wallet.js", //입출금 js
            "resources/js/mobile/custom/my_asset.js", //내자산 js
            "resources/js/mobile/custom/notice.js", //고객센터 js
            "resources/js/mobile/custom/popup.js", //팝업 js
            "resources/js/mobile/custom/my_page.js", //팝업 js
            "resources/js/mobile/custom/p2p.js", //p2p js
            "resources/js/mobile/custom/main.js", //p2p js
            "resources/js/mobile/custom/ico_create.js", //ico 작성페이지 js
            "resources/js/mobile/custom/register.js", //ico 작성페이지 js
            "resources/js/mobile/custom/infinit_scroll.js", //무한스크롤 js
            "resources/js/mobile/custom/auth.js", //회원가입 동의 팝업 js
            "resources/js/pc/custom/security_lv.js" //기존회원 보안인증 js
        ],
        "public/js/mobile/basic.js"
    ); //모바일버전 basic 거래소 통합 js

    /* 거래소 MOBILE버전 JS,CSS 끝  */
    
