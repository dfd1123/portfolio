<template>
  <div class="email-wrap">
    <p class="email-name">
      <input
        type="text"
        :name="id"
        :id="id"
        title="이메일 아이디 입력"
      >
    </p>
    <p class="email-address">
      <input
        type="text"
        :name="id + '_address'"
        :id="id + '_address'"
        title="이메일 주소 입력"
      >
    </p>
    <p class="email-select">
      <select>
        <option value="none">
          선택하세요.
        </option>
        <option>
          naver.com
        </option>
      </select>
    </p>
  </div>
</template>

<script>
  export default {
    
  }
</script>

<style scoped>

</style>
