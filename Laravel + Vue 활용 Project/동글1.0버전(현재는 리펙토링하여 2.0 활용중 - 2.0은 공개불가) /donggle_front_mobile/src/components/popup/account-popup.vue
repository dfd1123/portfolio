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
      </div>
      <div class="_popup_content">
        <!-- 팝업 타이틀 -->
        <div>
          <h5 class="_title">
            계좌를 등록하셔야 환불을 받으실 수 있습니다.
          </h5>
        </div>
        <!-- 팝업 타이틀 E -->

        <fieldset>
          <legend class="dg_blind">
            {{ this.$store.state.user.account_number?'계좌 변경':'계좌 등록' }}
          </legend>
          <div class="dg_write dg-list-lineup_wrap">
            <p class="_label">
              은행명
            </p>
            <select
              class="dg-list-lineup"
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
          <div class="dg_write write_num">
            <label
              for="accountNumber"
              class="_label"
            >계좌번호</label>
            <input
              type="tel"
              id="accountNumber"
              class="input_text_box"
              maxlength="30"
              placeholder="계좌번호 입력(- 없이)"
              v-model="accountNumber"
            >
          </div>
          <div class="dg_write write_name">
            <label
              for="accountName"
              class="_label"
            >예금주명</label>
            <input
              type="text"
              id="accountName"
              class="input_text_box"
              maxlength="30"
              placeholder="예금주명 입력"
              v-model="accountName"
            >
          </div>
        </fieldset>

        <div class="account_btn_wrap">
          <button
            type="button"
            class="square-btn-outline"
            @click="Submit"
          >
            {{ this.$store.state.user.account_number?'계좌 변경하기':'계좌 등록하기' }}
          </button>
        </div>
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
