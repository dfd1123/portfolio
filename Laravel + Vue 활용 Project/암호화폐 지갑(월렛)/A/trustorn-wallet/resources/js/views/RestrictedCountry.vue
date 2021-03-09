<template>
  <div class="restricted_cntry">
    <div>
      <i class="fal fa-exclamation-triangle"></i>
    </div>
    <h1>Restricted Country</h1>
    <p>Hello! Thank you for visiting Trustorn Wallet. However, this service is not available in your country. <br>Thank you for your understanding.</p>
  </div>
</template>

<script>
  export default {

  }
</script>

<style scoped>
  .restricted_cntry {
    padding: 33% 0;
  }

  .restricted_cntry > div {
    text-align: center;
    margin-bottom: 13px;
  }

  .restricted_cntry > div > i {
    font-size: 50px;
    color: orangered;
  }

  .restricted_cntry > h1 {
    font-size: 30px;
    color: orangered;
    text-align: center;
    margin-bottom: 20px;
  }

  .restricted_cntry > p {
    text-align: center;
    font-size: 20px;
    padding: 0 10px;
    line-height: 1.2;
  }
</style>