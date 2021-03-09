<template>
  <div class="multi-spinner-container">
    <div class="multi-spinner">
      <div class="multi-spinner">
        <div class="multi-spinner">
          <div class="multi-spinner">
            <div class="multi-spinner">
              <div class="multi-spinner"></div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
  export default {

  }
</script>

<style lang="scss" scoped>
  .multi-spinner-container {
    width: 150px;
    height: 150px;
    position: relative;
    margin: 30px auto;
    overflow: hidden;
    transform: scale(0.5);
  }

  .multi-spinner {
    position: absolute;
    width: calc(100% - 9.9px);
    height: calc(100% - 9.9px);
    border: 5px solid transparent;
    border-top-color: #ffe9f5;
    border-radius: 50%;
    -webkit-animation: spin 2s cubic-bezier(0.17, 0.49, 0.96, 0.76) infinite;
    animation: spin 2s cubic-bezier(0.17, 0.49, 0.96, 0.76) infinite;
  }

  @-webkit-keyframes spin {
    from {
      -webkit-transform: rotate(0deg);
      transform: rotate(0deg);
    }
    to {
      -webkit-transform: rotate(360deg);
      transform: rotate(360deg);
    }
  }

  @keyframes spin {
    from {
      -webkit-transform: rotate(0deg);
      transform: rotate(0deg);
    }
    to {
      -webkit-transform: rotate(360deg);
      transform: rotate(360deg);
    }
  }
</style>
