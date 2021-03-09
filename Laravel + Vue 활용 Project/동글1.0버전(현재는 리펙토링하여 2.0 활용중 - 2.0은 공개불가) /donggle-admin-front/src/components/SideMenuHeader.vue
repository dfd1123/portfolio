<template>
  <section class="in-brand-con-box show-pc">
    <h1 class="in-brand-logo">
      <a href="#" @click.prevent="$router.push('/main')">
        <img src="/images/logo/logo_header_wh.svg" alt="image" class="in-logo" />
      </a>
    </h1>
  </section>
</template>

<script>
export default {
  name: 'SideMenuHeader'
}
</script>

<style>
</style>
