<template>
  <div>
  </div>
</template>

<script>
  export default {
    beforeCreate () {
      if (Number(this.$route.query.state) === 1) {
        this.$router.push('/order/complete?orderId=' + this.$route.query.order_id)
      } else {
        alert(this.$route.query.message)
        this.$router.go(-1)
      }
    }
  }
</script>

<style lang="scss" scoped>
</style>
