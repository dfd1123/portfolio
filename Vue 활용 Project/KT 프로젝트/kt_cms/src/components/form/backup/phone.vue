<template>
  <div>
    <div class="phone-wrap">
      <div class="first">
        <select title="휴대폰 첫번재 4자리 선택">
          <option>
            010
          </option>
          <option>
            011
          </option>
        </select>
      </div>
      <div class="second">
        <input
          type="text"
          name=""
          maxlength="4"
          title="휴대폰 두번째 4자리 입력"
        >
      </div>
      <div class="three">
        <input
          type="text"
          name=""
          maxlength="4"
          title="휴대폰 세번째 4자리 입력"
        >
      </div>
    </div>
    <div
      v-if="verify === true"
      class="input-btns ml20"
    >
      <input
        type="text"
        name=""
        id=""
        style="width:100px"
      >
      <a
        href="#"
        class="btn-01 type-02"
      >인증요청</a>
      <div class="right-check">
        <p class="check-type01">
          <input
            type="checkbox"
            id="selectCheck02"
            name="selectCheck02"
          ><label for="selectCheck02"><span></span>체크박스 버튼 1</label>
        </p>
      </div>
    </div>
  </div>
</template>

<script>
  export default {
    
  }
</script>

<style scoped>

</style>
