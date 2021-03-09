<template>
    <div
        v-show="isGlobalLoading"
        id="global-loading-indicator"
    >
        <div
            class="black-overlay"
            style="display: block"
        />
        <button
            ref="button"
            class="loader-bar"
        />
    </div>
</template>

<script>
import { mapState } from "vuex";

export default {
    computed: {
        ...mapState({
            isGlobalLoading: "isGlobalLoading"
        })
    },
    watch: {
        isGlobalLoading() {
            if (this.isGlobalLoading) {
                this.$nextTick(() => {
                    this.$refs.button.focus();
                    this.$refs.button.blur();
                });
            }
        }
    }
};
</script>

<style lang="scss">
@charset "UTF-8";
@import "../../../styles/scss/utils/_variable.scss";
@import "../../../styles/scss/utils/_path.scss";
@import "../../../styles/scss/utils/_mixin.scss";

#global-loading-indicator {
    .loader-bar {
        position: fixed;
        left: 50%;
        top: 50%;
        transform: translate(-50%,-50%);
        z-index: 1000;
        border: 0px;
        pointer-events: none;

        &:focus {
            outline: 0px;
        }
    }
}
</style>
