<template>
    <div
        v-show="isLoading"
        id="loading-indicator"
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
export default {
    props: {
        isLoading: {
            type: Boolean,
            default: true
        }
    },
    watch: {
        isGlobalLoading() {
            if (this.isLoading) {
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
#loading-indicator {
    .black-overlay {
        position: absolute;
        opacity: 0.1;
    }
    .loader-bar {
        position: absolute;
        left: 50%;
        top: 50%;
        transform: translate(-50%,-50%);
        z-index: 1000;
        border: 0px;
        pointer-events: none;
        opacity: 0.3;

        &:focus {
            outline: 0px;
        }
    }
}
</style>
