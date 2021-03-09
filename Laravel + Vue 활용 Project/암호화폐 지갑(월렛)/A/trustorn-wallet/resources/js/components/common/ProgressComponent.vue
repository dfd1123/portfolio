<template>
    <div id="progress-loading" v-show="isVisible">
        <div class="nb-spinner"></div>
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
    top:0;
    left:0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.3);
    z-index: 100;
}

.nb-spinner{
  position: absolute;
  top: 50%;
  left: 48%;
  width: 75px;
  height: 75px;
  margin: 0;
  margin-top: -32.5px;
  margin-left: -32.5px;
  background: transparent;
  border-top: 4px solid rgb(44, 221, 177);
  border-right: 4px solid transparent;
  border-radius: 50%;
  -webkit-animation: 1s spin linear infinite;
  animation: 1s spin linear infinite;
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
