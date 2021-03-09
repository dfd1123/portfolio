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

// mix.js("resources/js/app.js", "public/js").sass(
//     "resources/sass/app.scss",
//     "public/css"
// );

mix.js("resources/js/app.js", "public/js")
    .babel("resources/js/app.js", "public/js/app.es5.js")
    .styles(
        [
            "resources/style/common/typography.css",
            "resources/style/common/normalize-custom.css",
            "resources/style/common/helper.css",

            "resources/style/layout/layout.css",

            "resources/style/components/navigation.css",
            "resources/style/components/button.css",
            "resources/style/components/popup.css",
            "resources/style/components/swal.css",

            "resources/style/pages/main.css",
            "resources/style/pages/sub.css",

            "resources/style/responsive/respon.css"
        ],
        "public/css/ui.css"
    )
    .js("resources/js/admin/scripts.js", "public/js")
	.babel("public/js/scripts.js", "public/js/scripts.es5.js")
    .js("resources/js/plugin/rolling_banner.js", "public/js")
    .styles(
        ["resources/style/admin/styles.css"],
        "public/css/admin/styles.css"
    );
