<template>
    <div id="progress-loading" v-show="isVisible">
        <div></div>
    </div>
</template>

<script>
export default {
    computed: {
        isVisible() {
            return this.$store.state.isProgressComponentVisible;
        }
    }
};
</script>

<style scoped>
#progress-loading {
    position: fixed;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.3);
    z-index: 100;
}

#progress-loading div {
    position: absolute;
    top: 50%;
    left: 48%;
    margin-top: -16px;
    margin-left: -8px;
    display: inline-block;
    width: 35px;
    height: 35px;
    border: 4px solid rgba(255, 255, 255, 0.6);
    border-radius: 50%;
    border-top-color: #2E87C8;
    animation: spin 0.7s ease-in-out infinite;
    -moz-animation: spin 1s ease-in-out infinite;
    -ms-animation: spin 1s ease-in-out infinite;
    -webkit-animation: spin 0.7s ease-in-out infinite;
}

@keyframes spin {
    to {
        -webkit-transform: rotate(360deg);
    }
}
@-webkit-keyframes spin {
    to {
        transform: rotate(360deg);
    }
}

@-ms-keyframes spin {
    to {
        transform: rotate(360deg);
    }
}

@-moz-keyframes spin {
    to {
        transform: rotate(360deg);
    }
}
</style>
