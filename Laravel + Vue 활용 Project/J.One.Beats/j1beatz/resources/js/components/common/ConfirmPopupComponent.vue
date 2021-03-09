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
                <div class="btn-group _double">
                    <button
                        ref="button"
                        type="button"
                        class="btn btn-02"
                        @click="leftButtonClick"
                    >
                        {{ leftButtonText }}
                    </button>
                    <button
                        type="button"
                        class="btn btn-01"
                        @click="rightButtonClick"
                    >
                        {{ rightButtonText }}
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
        leftButtonText: {
            type: String,
            default: "취소"
        },
        rightButtonText: {
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
        leftButtonClick() {
            this.$emit("update:visible", false);
            this.$emit("leftButtonClick");
        },
        rightButtonClick() {
            this.$emit("update:visible", false);
            this.$emit("rightButtonClick");
        },
        closeButtonClick() {
            this.$emit("update:visible", false);
            this.$emit("closeButtonClick");
        }
    }
};
</script>

<style lang="scss">
</style>
