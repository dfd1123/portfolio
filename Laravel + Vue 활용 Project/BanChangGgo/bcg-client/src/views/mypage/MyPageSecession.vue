<template>
  <div id="mypage_secession" class="page page--hd_default">
    <header-component hd-title="회원탈퇴" :hd-theme-ylw="true" @privButtonClick="privButtonClick" />
    <div class="page_container">
      <transition appear name="slide-fade">
        <div class="contents form-stretch">
          <h2>회원탈퇴 안내</h2>
          <!-- 탈퇴주의사항 -->
          <article class="secession_article secession_article--dbcheck">
            <h3>탈퇴 신청 전 아래 사항을 꼭 확인해주세요.</h3>
            <ul>
              <li v-for="item in cautionList" :key="item.id">{{ item.text }}</li>
            </ul>
          </article>
          <!-- end -->
          <!-- 사유선택 -->
          <article class="secession_article secession_article--reason">
            <h3>회원탈퇴 사유 선택</h3>
            <ul>
              <li v-for="item in reasonList" :key="item.id">
                <label v-bind:for="'reason_type_'+item.id" class="checkbox_02">
                  <input
                    v-bind:id="'reason_type_'+item.id"
                    type="radio"
                    class="none"
                    name="reason"
                    :value="item.id"
                    v-model="reason"
                    @change="reasonType = item.id"
                  />
                  <checkbox-svg-type02></checkbox-svg-type02>
                  <span>{{ item.text }}</span>
                  <div class="reason_etc_textarea" v-if="reasonType === 5 && item.detail">
                    <textarea-autosize
                      v-model="item.detailText"
                      placeholder="내용을 입력해주세요."
                      rows="1"
                    />
                  </div>
                </label>
              </li>
              <li>
                <label for="agree1" class="checkbox_02">
                  <input id="agree1" type="checkbox" class="none" v-model="agree1" />
                  <checkbox-svg-type02></checkbox-svg-type02>
                  <span>안내사항을 모두 확인하였으며, 이에 동의합니다.</span>
                </label>
              </li>
            </ul>
          </article>
          <input
            type="button"
            value="탈퇴하기"
            class="wide_btn btn_clear_to_theme step_btn active"
            @click="secessionBtnClick"
          />
          <!-- end -->
        </div>
      </transition>
    </div>
  </div>
</template>

<script>
import Vue from 'vue'
import TextareaAutosize from 'vue-textarea-autosize'

Vue.use(TextareaAutosize)

export default {
  name: 'MyPageSecession',
  data () {
    return {
      cautionList: [
        { id: 1, text: '탈퇴 시 회원정보를 포함한 유료상품 및 모든 서비스 내역이 삭제되며 삭제된 데이터는 복구되지 않습니다.' }
        // { id: 2, text: '작성사항 2' }
      ],
      reasonList: [
        { id: 1, text: '컨텐츠 내용 부족', detail: false },
        { id: 2, text: '서비스 이용 불편', detail: false },
        { id: 3, text: '가격 불만족', detail: false },
        { id: 4, text: '방문횟수 거의 없음', detail: false },
        { id: 5, text: '기타', detail: true, detailText: '' }
      ],
      reason: 5,
      reasonType: 5,
      agree1: false
    }
  },
  methods: {
    privButtonClick () {
      window.history.back()
    },
    async secessionBtnClick () {
      if (!this.agree1) {
        this.$swal({
          text: '안내사항에 동의하셔야 합니다',
          confirmButtonText: '확인'
        })
        return
      }

      const value = await this.$swal({
        text: '정말로 탈퇴하시겠습니까?',
        showCancelButton: true,
        cancelButtonText: '아니요',
        confirmButtonText: '네, 탈퇴합니다.'
      }).then(result => result.value)

      if (!value) {
        return
      }

      try {
        const result = this.reasonList.find(x => x.id === this.reason)
        const info = result.detail ? result.detailText : result.text

        await this.$axios
          .post('/secession', {
            unreg_info: info
          })
          .then(response => response.data)

        localStorage.removeItem('token')

        this.$swal({
          text: '회원탈퇴가 완료되었습니다',
          confirmButtonText: '확인'
        }).then(result => window.location.reload())
      } catch (e) {
        console.log(e)
      }
    }
  }
}
</script>

<style lang="scss" scoped>
#mypage_secession {
  .page_container {
    overflow-x: hidden;
  }

  .contents {
    padding: 0 2.25rem;
    overflow-y: scroll;
    & > h2 {
      @include remFont("20px", $weight: bold, $color: $theme-02);
      padding: 0.9rem 0;
    }
  }

  .step_btn {
    margin-top: auto;
    min-height: 53px;
  }

  .form-stretch {
    display: flex;
    flex-direction: column;
    min-height: 100%;
    padding-bottom: 2rem;
  }

  .secession_article {
    padding: 0.9rem 0;
    & > h3 {
      @include remFont("15px", $weight: bold, $color: $gray-01);
      padding: 0.25rem 0;
    }
  }
  .secession_article--dbcheck {
    border-top: 1px solid #e6e6e6;
    border-bottom: 1px solid #e6e6e6;
    ul li {
      @include remFont("13px", $weight: 400);
      padding-left: 8px;
      position: relative;
      margin: 10px 0;
    }
    ul li:before {
      @include position($t: 0px, $l: 0);
      content: "-";
    }
  }
  .secession_article--reason {
    ul li {
      margin: 10px 0;
    }
    ul li:last-child {
      padding-top: 10px;
      border-top: 1px solid #cccccc;
    }
    ul li:last-child label {
      color: #c3c3c3;
    }
    label {
      @include remFont("13px", $weight: 400);
      display: inline-block;
      vertical-align: middle;
      width: 100%;
    }
    label span {
      display: inline-block;
      vertical-align: middle;
      line-height: 28px;
    }
  }

  .reason_etc_textarea {
    padding-left: 35px;
    textarea {
      -webkit-appearance: none;
      border: 0;
      border-bottom: 1px solid #dcdcdc;
      width: 100%;
      border-radius: 0;
      font-size: 0.85rem;
      padding: 8px 5px;
      font-weight: 300;
      outline: none;
    }
    textarea:focus {
      border-color: #faa13c;
    }
    textarea::placeholder {
      color: #c3c3c3;
    }
  }
}
</style>
