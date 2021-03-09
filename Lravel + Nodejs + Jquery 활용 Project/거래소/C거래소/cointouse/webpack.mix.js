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
    //.js('resources/js/app.js', 'public/js')
    .babel(
        [
            "resources/js/common/pc/common.js",
            "resources/js/common/pc/register.js"
        ],
        "public/js/common/pc/market.js"
    ) //프랜차이즈 거래소 공통 회원가입 js
    .babel(
        [
            "resources/js/theme/basic/pc/custom/market/ws_market.js",
            "resources/js/theme/basic/pc/custom/market/market.js",
            "resources/js/theme/basic/pc/custom/market/trading_view.js"
        ],
        "public/js/theme/basic/pc/market.js"
    )
    .babel(
        [
            "resources/js/theme/basic/pc/custom/market_btc/ws_market.js",
            "resources/js/theme/basic/pc/custom/market_btc/market.js",
        ],
        "public/js/theme/basic/pc/market_btc.js"
    )
    .babel(
        [
            "resources/js/theme/basic/mobile/custom/market/ws_market.js",
            "resources/js/theme/basic/mobile/custom/market/market.js",
            "resources/js/theme/basic/mobile/custom/market/trading_view.js"
        ],
        "public/js/theme/basic/mobile/market.js"
    )
    .babel(
        [
            "resources/js/theme/basic/mobile/custom/market_btc/ws_market.js",
            "resources/js/theme/basic/mobile/custom/market_btc/market.js",
        ],
        "public/js/theme/basic/mobile/market_btc.js"
    )
    .js(
        ["resources/js/theme/basic/pc/custom/home_new.js"],
        "public/js/theme/basic/pc/home_new.js"
    )
    .js(
        ["resources/js/theme/basic/mobile/custom/home_new.js"],
        "public/js/theme/basic/mobile/home_new.js"
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
    )
    .styles(
        [
            "resources/css/theme/basic/pc/normalize.css", //초기화
            "resources/css/theme/basic/pc/common.css", //공통속성
            "resources/css/theme/basic/pc/header.css", //헤더속성
            "resources/css/theme/basic/pc/footer.css", //푸터속성
            "resources/css/theme/basic/pc/market.css", //거래소 스타일
            "resources/css/theme/basic/pc/market_media.css", //거래소 1080px까지 반응형
            "resources/css/theme/basic/pc/main.css", //메인페이지 스타일
            "resources/css/theme/basic/pc/main_media.css", //메인페이지 1080px까지 반응형
            "resources/css/theme/basic/pc/auth.css", //로그인,회원가입,비밀번호 찾기,otp2차로그인 스타일
            "resources/css/theme/basic/pc/trans_wallet.css", //입출금 스타일
            "resources/css/theme/basic/pc/my_asset.css", //내 자산 스타일
            "resources/css/theme/basic/pc/notice.css", //공지사항 스타일
            "resources/css/theme/basic/pc/my_page.css", //마이페이지 스타일
            "resources/css/theme/basic/pc/p2p.css", //p2p(장외거래) 스타일
            "resources/css/theme/basic/pc/ico.css", //ico 스타일
            "resources/css/theme/basic/pc/p2p_ico_nav.css", //p2p 및 ico 왼쪽네비게이션 스타일 
            "resources/css/theme/basic/pc/banner.css", // p2p 및 ico 배너 스타일
            "resources/css/theme/basic/pc/game.css" // 게임스타일
        ],
        "public/css/theme/basic/pc/basic_market.css"
    ) // theme/basic/pc CSS
    .babel(
        [
            "resources/js/theme/basic/pc/common/common.js",
            "resources/js/theme/basic/pc/custom/main/slick_custom.js",
            "resources/js/theme/basic/pc/custom/market_ui.js",
            "resources/js/theme/basic/pc/custom/transWallet.js",
            "resources/js/theme/basic/pc/custom/my_asset.js", //내자산 > 기간검색 js
            "resources/js/theme/basic/pc/custom/notice.js", // 고객센터 js
            "resources/js/theme/basic/pc/custom/p2p.js", // p2p장외거래 js
            "resources/js/theme/basic/pc/custom/my_page.js", // 마이페이지 js
            "resources/js/theme/basic/pc/custom/popup.js", //팝업 js

            "resources/js/theme/basic/pc/custom/p2p_ico_nav.js", //p2p 및 ico 왼쪽네비게이션 js
            "resources/js/theme/basic/pc/custom/register.js", //회원가입 js
            "resources/js/theme/basic/pc/custom/ico_create.js", //ico 작성페이지 js
            "resources/js/theme/basic/pc/custom/security_lv.js" //기존회원 보안인증 js
        ],
        "public/js/theme/basic/pc/basic.js"
    ) // basic 거래소 통합 js
    .styles(
        [
            "resources/css/theme/basic/mobile/normalize.css", //초기화
            "resources/css/theme/basic/mobile/common.css", //공통속성
            "resources/css/theme/basic/mobile/header.css", //헤더속성
            "resources/css/theme/basic/mobile/main.css", //메인페이지 스타일
            "resources/css/theme/basic/mobile/auth.css", //로그인,회원가입,비밀번호 찾기,otp2차로그인 스타일
            "resources/css/theme/basic/mobile/market.css", //거래소 스타일
            "resources/css/theme/basic/mobile/trans_wallet.css", //입출금 스타일
            "resources/css/theme/basic/mobile/my_asset.css", //내자산 스타일
            "resources/css/theme/basic/mobile/notice.css", //고객센터-공지사항 스타일
            "resources/css/theme/basic/mobile/my_page.css", //마이페이지 스타일
            "resources/css/theme/basic/mobile/ico.css", //ICO 스타일
            "resources/css/theme/basic/mobile/p2p.css", //p2p 스타일
            "resources/css/theme/basic/mobile/sweetalert_mobile.css", //alert 스타일
            "resources/css/theme/basic/mobile/game.css", //게임 스타일
        ],
        "public/css/theme/basic/mobile/m_basic_market.css"
    )
    .styles(
        [
            "resources/css/theme/basic/pc/kr.css"
        ],
        "public/css/theme/basic/pc/kr.css"
    )
    .styles(
        [
            "resources/css/theme/basic/pc/jp.css"
        ],
        "public/css/theme/basic/pc/jp.css"
    )
    .styles(
        [
            "resources/css/theme/basic/pc/ch.css"
        ],
        "public/css/theme/basic/pc/ch.css"
    )
    .styles(
        [
            "resources/css/theme/basic/pc/en.css"
        ],
        "public/css/theme/basic/pc/en.css"
    )
    .styles(
        [
            "resources/css/theme/basic/mobile/kr.css"
        ],
        "public/css/theme/basic/mobile/kr.css"
    )
    .styles(
        [
            "resources/css/theme/basic/mobile/jp.css"
        ],
        "public/css/theme/basic/mobile/jp.css"
    )
    .styles(
        [
            "resources/css/theme/basic/mobile/ch.css"
        ],
        "public/css/theme/basic/mobile/ch.css"
    )
    .styles(
        [
            "resources/css/theme/basic/mobile/en.css"
        ],
        "public/css/theme/basic/mobile/en.css"
    )
    .babel(
        ["resources/js/common/mobile/register.js"],
        "public/js/common/mobile/market.js"
    ) //모바일버전 프랜차이즈 거래소 공통 회원가입 js
    .babel(
        [
        	"resources/js/theme/basic/mobile/common/common.js", //filter 등등 js
            "resources/js/theme/basic/mobile/custom/market/market_ui.js", //거래소 js
            "resources/js/theme/basic/mobile/custom/trans_wallet.js", //입출금 js
            "resources/js/theme/basic/mobile/custom/my_asset.js", //내자산 js
            "resources/js/theme/basic/mobile/custom/notice.js", //고객센터 js
            "resources/js/theme/basic/mobile/custom/popup.js", //팝업 js
            "resources/js/theme/basic/mobile/custom/my_page.js", //팝업 js
            "resources/js/theme/basic/mobile/custom/p2p.js", //p2p js
            "resources/js/theme/basic/mobile/custom/main.js", //p2p js
            "resources/js/theme/basic/mobile/custom/ico_create.js", //ico 작성페이지 js
            "resources/js/theme/basic/mobile/custom/register.js", //ico 작성페이지 js
            "resources/js/theme/basic/mobile/custom/infinit_scroll.js", //무한스크롤 js
            "resources/js/theme/basic/mobile/custom/auth.js", //회원가입 동의 팝업 js
            "resources/js/theme/basic/pc/custom/security_lv.js" //기존회원 보안인증 js
        ],
        "public/js/theme/basic/mobile/basic.js"
    ); //모바일버전 basic 거래소 통합 js
