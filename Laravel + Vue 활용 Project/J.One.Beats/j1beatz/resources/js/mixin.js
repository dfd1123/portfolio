import axios from "axios";
import store from "./stores/store";

export default {
    methods: {
        // 언어화 기능
        __(key, replace = {}) {
            window.__ = window.__ || {};

            let translation = key
                .split(".")
                .reduce((t, i) => t[i] || null, window.__);

            for (var placeholder in replace) {
                translation = translation.replace(
                    `:${placeholder}`,
                    replace[placeholder]
                );
            }

            return translation;
        },
        async __info() {
            if (localStorage.laravel_token) {
                try {
                    const { user, producer } = await axios
                        .get("/api/info")
                        .then(response => response.data);

                    store.commit("updateUser", user);
                    store.commit("updateProducer", producer);
                } catch (e) {
                    console.log(e);
                }
            }
        },
        $RENDER_JQUERY_slick(selector, option) {
            this.$nextTick(() => {
                $(`${selector}.slick-initialized`).slick("unslick");
                this.$nextTick(() => {
                    $(selector).slick(option);
                });
            });
        },
        $RENDER_JQUERY_mCustomScrollbar(selector, style, options = {}) {
            $(`${selector}`).mCustomScrollbar({
                ...{
                    theme: style, // 테마 적용
                    mouseWheelPixels: 300, // 마우스휠 속도
                    scrollInertia: 400 // 부드러운 스크롤 효과 적용
                },
                ...options
            });
        },
        $RENDER_JQUERY_mCustomScrollbar_scrollTo(selector, position) {
            const scrollBar = $(`${selector}`);
            scrollBar.mCustomScrollbar("update"); // 업데이트
            scrollBar.mCustomScrollbar("scrollTo", position); // 아래로 스크롤
        },
        $clipboard(text) {
            this.$root.$refs.clipboard.setAttribute("data-clipboard-text", text);
            this.$root.$refs.clipboard.click();
        }
    }
};
