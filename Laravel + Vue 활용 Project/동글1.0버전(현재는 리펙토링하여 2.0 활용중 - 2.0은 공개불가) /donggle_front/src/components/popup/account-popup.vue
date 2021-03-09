<template>
  <div class="_popup_wrapper _account_popup_wrapper">
    <div class="_bg"></div>
    <div class="_popup_wrap">
      <div class="_popup_title">
        <h4>
          {{ this.$store.state.user.account_number?'계좌 변경':'계좌 등록' }}<button
            type="button"
            class="_close_btn"
            @click="$emit('close-popup')"
          >
          </button>
        </h4>
        <span>계좌를 등록하셔야 환불을 받으실 수 있습니다.</span>
      </div>
      <div class="_popup_content">
        <div class="_popup_text_wrap">
          <form method="post">
            <fieldset class="dg-write_form">
              <legend class="dg_blind">
                {{ this.$store.state.user.account_number?'계좌 변경':'계좌 등록' }}
              </legend>
              <div class="dg_write write_bank">
                <label for="#">은행명</label>
                <select
                  class="input_text_box reg_select"
                  v-model="bankName"
                >
                  <option
                    v-for="(bank, index) in bankArr"
                    :key="'bank'+index"
                    :value="(bank.num || '')+'/'+(bank.name || '')"
                  >
                    {{ bank.name || '' }}
                  </option>
                </select>
              </div>
              <div class="dg_write write_number">
                <label for="accountNumber">계좌번호</label>
                <input
                  type="number"
                  id="accountNumber"
                  class="input_text_box"
                  maxlength="30"
                  placeholder="계좌번호를 입력하세요(- 없이)"
                  v-model="accountNumber"
                >
              </div>
              <div class="dg_write write_name">
                <label for="accountName">예금주명</label>
                <input
                  type="text"
                  id="accountName"
                  class="input_text_box"
                  placeholder="예금주명 입력"
                  v-model="accountName"
                >
              </div>
            </fieldset>
          </form>
          <div class="dg-dubble_btn_wrap clear_both">
            <button
              type="button"
              class="dg-btn_line dg-dubble_btn"
              @click="Submit"
            >
              {{ this.$store.state.user.account_number?'계좌 변경하기':'계좌 등록하기' }}
            </button>
          </div>
        </div>
        <!-- _popup_text_wrap E -->
      </div>
    </div>
  </div>
</template>

<script>
  const bank = require('@/store/bank.js')

  const bankArr = bank()
  export default {
    data: function () {
      return {
        bankArr: bankArr,
        bankName: this.$store.state.user.account_bank,
        accountNumber: this.$store.state.user.account_number,
        accountName: this.$store.state.user.account_name
      }
    },
    methods: {
      Validation () {
        if (!this.bankName || this.bankName === '') {
          this.WarningAlert('은행을 선택하세요.')
          return false
        }

        if (!this.accountNumber || this.accountNumber === '') {
          this.WarningAlert('계좌번호를 입력하세요.')
          return false
        }

        if (!this.accountName || this.accountName === '') {
          this.WarningAlert('예금주를 입력하세요.')
          return false
        }

        return true
      },
      async Submit () {
        if (this.Validation()) {
          this.$emit('close-popup')
          this.$store.commit('ProgressShow')
          const params = {
            account_bank: this.bankName,
            account_number: this.accountNumber,
            account_name: this.accountName
          }

          try {
            let res = (await this.$http.put(this.$APIURI + 'users/change_account', params)).data

            if (res.state === 1) {
              res = (await this.$http.get(this.$APIURI + 'users/user_info')).data
              this.$store.commit('UserStoreInfor', res.query)
            } else {
              console.log(res.msg)
            }
          } catch (e) {
            console.log(e)
          } finally {
            this.$store.commit('ProgressHide')
          }
        }
      }
    }
  }
</script>

<style lang="scss" scoped>
</style>
