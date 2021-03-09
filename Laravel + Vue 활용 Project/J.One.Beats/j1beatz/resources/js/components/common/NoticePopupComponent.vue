<template>
    <div
        v-show="visible"
    >
        <div
            class="black-overlay"
            style="display: initial"
        />
        <div
            class="popup-layer"
        >
            <div class="popup-01">
                <h4 class="popup-layer-title">
                    {{ titleText }}
                </h4>
                <slot />
                <div class="btn-group">
                    <button
                        ref="button"
                        type="button"
                        class="btn btn-01"
                        @click="closeButtonClick"
                    >
                        확인
                    </button>
                </div>
                <button
                    type="button"
                    class="popup-layer-close-btn"
                    @click="closeButtonClick"
                >
                    <span class="none">팝업 닫기버튼</span>
                </button>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        visible: {
            type: Boolean,
            default: false
        },
        titleText: {
            type: String,
            default: ""
        },
        closeText: {
            type: String,
            default: "확인"
        }
    },
    data() {
        return {};
    },
    watch: {
        visible() {
            if (this.visible) {
                this.$nextTick(() => {
                    this.$refs.button.focus();
                    this.$refs.button.blur();
                });
            }
        }
    },
    methods: {
        closeButtonClick() {
            this.$emit("update:visible", false);
            this.$emit("closeButtonClick");
        }
    }
};
</script>

<style lang="scss">
</style>
