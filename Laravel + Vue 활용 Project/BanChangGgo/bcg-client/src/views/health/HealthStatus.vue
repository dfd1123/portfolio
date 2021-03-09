<template>
  <div id="myhealth_page" class="page page--hd_default">
    <header-component hd-title="나의 건강상태" :priv-button="false" :hd-theme-boxshadow="true" />
    <div class="page_container pd_bt_nav">
      <div class="contents">
        <!-- 상단 유저 정보+자세히버튼 -->
        <transition appear name="slide-fadedown">
          <div class="user_intro">
            <div class="user_profile_panel">
              <label for="#" class="user_profile_picture">
                <figure class="pic_circle">
                  <img
                    v-img-orientation-changer
                    :src="`/fdata/user_thumb/${user.usr_thumb}`"
                    class="preview"
                    ref="img_file1"
                    v-if="user.usr_thumb"
                  />
                  <!-- 이미지 설정한게 없을 때 -->
                  <img
                    v-img-orientation-changer
                    ref="img_file1"
                    src="/assets/images/pics/profile_img_null.svg"
                    v-else
                  />
                  <!-- // end -->
                </figure>
              </label>
              <dl>
                <dt>{{ user.usr_name }}</dt>
                <dd>{{ userAge }}세</dd>
                <dd>{{ user.usr_extra.user_type }}</dd>
              </dl>
            </div>
            <div class="detail_button_area" @click="detailBtnClick">
              <span>자세히</span>
              <button type="button" :class="{active : isShowDetail}">
                <img src="/assets/images/btn/btn_arrow_light.svg" />
              </button>
            </div>
            <transition name="slide-fadedown">
              <div class="user_hlth_information" v-show="isShowDetail">
                <article class="user_hlth_article user_hlth_article--default">
                  <h2>기본정보</h2>
                  <dl>
                    <dt class="visually-hidden">기본정보</dt>
                    <dd>
                      <h6>성별</h6>
                      <span>{{ {'M': '남성', 'F': '여성'}[user.usr_extra.gender] }}</span>
                    </dd>
                    <dd>
                      <h6>연령</h6>
                      <span>{{ user.usr_extra.born_year }}년생 ({{ userAge }}세)</span>
                    </dd>
                    <dd>
                      <h6>키</h6>
                      <span>{{ user.usr_extra.height }}cm</span>
                    </dd>
                    <dd>
                      <h6>몸무게</h6>
                      <span>{{ user.usr_extra.weight }}kg</span>
                    </dd>
                    {{/* 필수 항목이면 회원가입 시 입력했어야 함. 생략 */}}
                    <!--
                    <dd>
                      <h6>주량</h6>
                      <span>주 1회 미만</span>
                    </dd>
                    <dd>
                      <h6>흡연 여부</h6>
                      <span>비흡연자</span>
                    </dd>
                    <dd>
                      <h6>음주 빈도</h6>
                      <span>주 1회 미만</span>
                    </dd>
                    -->
                  </dl>
                </article>
                <article class="user_hlth_article user_hlth_article--qna">
                  <h2>질문 입력 사항</h2>
                  <dl>
                    <dt class="visually-hidden">질문입력사항</dt>

                    <dd v-for="qna in this.qnaList" :key="qna.order">
                      <h6>
                        <b>{{qna.order}}.</b>
                        {{qna.title}}
                      </h6>
                      <!-- <p v-if="qna.type === 'text'">{{qna.value}}</p>
                      <p v-else-if="qna.type === 'select'">{{qna.value}}</p>
                      <p v-else-if="qna.type === 'single'">{{qna.value}}</p>
                      <p
                        v-else-if="qna.type === 'multi'"
                      >{{qna.option.filter(x => x.checked).map(x => x.name).join(', ')}}</p>-->
                      <p
                        v-if="qna.type === 'time'"
                      >{{qna.value ? $moment(qna.value, ['HH:mm']).format('a h시 m분') : ''}}</p>
                      <p v-else-if="qna.type === 'number'">{{qna.value}}{{qna.unit}}</p>
                      <p v-else>{{view(qna.value, qna.option)}}</p>
                    </dd>
                  </dl>
                </article>
              </div>
            </transition>
          </div>
        </transition>
        <!-- end -->
        <!-- 하단 건강리포트 관련 정보 -->
        <div v-show="!isShowDetail" class="hlth_rpt_process">
          <template v-if="get(user, 'usr_batch.ubt_no', null)">
            <transition appear name="slide-fadeup">
              <article
                v-if="(user.usr_batch.pt_day || 0) <= user.usr_batch.pt_term"
                class="rpt_result_article rpt_result_article--ing"
              >
                <template v-if="user.usr_batch.pt_day === null">
                  <p>
                    회원님의 건강리포트 제작이
                    <b>{{user.usr_batch.ubt_start}}</b>일 부터 시작될 예정입니다.
                  </p>
                </template>
                <template v-else>
                  <p>
                    회원님의 건강리포트를
                    <b>제작 중</b>입니다.
                  </p>
                  <h2>{{user.usr_batch.pt_day}}일차</h2>
                  <div>
                    <div class="progress_bar">
                      <span v-bind:style="{width: `${userBatchProgressPercent}%`}"></span>
                    </div>
                    <ul class="progress_caption">
                      <li>시작</li>
                      <li>{{Math.round(user.usr_batch.pt_term / 2)}}일</li>
                      <li>{{user.usr_batch.pt_term}}일</li>
                    </ul>
                  </div>
                </template>
              </article>
              <article
                v-else-if="healthReport !== null"
                class="rpt_result_article rpt_result_article--complt"
              >
                <template v-if="healthReport.state === 1">
                  <img src="/assets/images/icons/icon_report.svg" alt="레포트 아이콘" />
                  <h2>두근두근!</h2>
                  <p>
                    회원님의 건강리포트 제작이
                    <br />
                    <b>완료</b>되었습니다 :)
                  </p>
                  <input type="button" value="건강 리포트 확인하기" @click="rptBtnClick" />
                </template>
                <template v-else-if="healthReport.state === 0">
                  <img src="/assets/images/icons/icon_report.svg" alt="레포트 아이콘" />
                  <h2>작성중...</h2>
                  <p>
                    회원님의 건강리포트를
                    <b>작성 중</b>입니다.
                  </p>
                </template>
              </article>
            </transition>
          </template>
          <template v-else>
            <article class="rpt_result_article rpt_result_article--complt">
              <p>현재 진행중인 건강리포트 모집이 없습니다</p>
            </article>
          </template>
        </div>
        <!-- end -->
      </div>
    </div>
    <footer-navigation :report-on="true"></footer-navigation>
  </div>
</template>
<script>
import { mapGetters } from 'vuex'
import Vue from 'vue'
import get from 'lodash/get'
import VueImgOrientationChanger from 'vue-img-orientation-changer'
Vue.use(VueImgOrientationChanger)

export default {
  name: 'MyHealth',
  data () {
    return {
      isShowDetail: false,
      healthReport: null,
      qnaList: []
    }
  },
  async created () {
    try {
      const userBatchNo = get(this.user, 'usr_batch.ubt_no', null)
      if (userBatchNo === null) {
        return
      }

      const userBatch = await this.$axios.get(`/user_batches/${userBatchNo}`).then(response => response.data)

      if ((userBatch.pt_day || 0) >= userBatch.pt_term) {
        if (userBatch.hr_no !== null) {
          this.healthReport = await this.$axios.get(`/health_reports/${userBatch.hr_no}`).then(response => response.data)
        }
      }

      this.qnaList = userBatch.ubt_qna_list.map((page, index1) => {
        const pageIndex = String(index1 + 1).padStart(2, '0')
        return page.map((qna, index2) => ({
          ...qna,
          ...{ order: pageIndex + (page.length > 1 ? `-${index2 + 1}` : '') }
        }))
      }).flat(1)
    } catch (e) {
      console.log(e)
    }
  },
  computed: {
    ...mapGetters([
      'user',
      'userBatchProgressPercent'
    ]),
    userAge () {
      const time = this.$moment().format('YYYY')
      const userBirthInfo = this.user.usr_extra.born_year

      return time - userBirthInfo + 1
    }
  },
  methods: {
    ...{ get },
    detailBtnClick () {
      this.isShowDetail = !this.isShowDetail
    },
    rptBtnClick () {
      this.$router.push(`/health/report/${this.healthReport.hr_no}`)
    },
    view (value, option) {
      if (value !== null && value !== undefined) {
        return value
      } else {
        console.log(option)
        // eslint-disable-next-line no-unused-vars
        var optvalue = ''
        if (option !== undefined) {
          for (var i = 0; i < option.length; i++) {
            if (option[i].checked) {
              if (option[i].value !== undefined) {
                optvalue += i !== option.length - 1 ? option[i].value + ',' : option[i].value
              } else {
                optvalue += i !== option.length - 1 ? option[i].name + ',' : option[i].name
              }
            }
          }
        }
        return optvalue
      }
    }
  }
}
</script>

<style lang="scss" scoped>
#myhealth_page {
  .contents {
    overflow-y: scroll;
    padding: 0;
    background-color: #fff9ef;
    padding-bottom: 3rem;
  }
  .user_intro {
    padding: 0 1.5rem;
    text-align: right;
    background-color: white;
    padding-bottom: 20px;
    border-bottom-right-radius: 40px;
    box-shadow: 4px 4px 30px rgba(211, 150, 27, 0.07);
    .detail_button_area {
      display: inline-block;
    }
    .detail_button_area span {
      @include setFont(13px, $color: #bfbfbf);
      display: inline-block;
      vertical-align: middle;
      padding-right: 8px;
    }
    .detail_button_area button {
      @include initButton;
      width: 40px;
      height: 40px;
      background: linear-gradient(45deg, #fcd658, #fabb3c);
      display: inline-block;
      vertical-align: middle;
      border-radius: 50%;
      box-shadow: 0 3px 6px #ffe5b1;
      transform: scale(1);
      transition: all 0.2s;
      padding: 0;
    }
    .detail_button_area button:active {
      transform: scale(1.1);
      transition: all 0.3s;
    }
    .detail_button_area button img {
      width: 13px;
      position: relative;
      top: -3px;
      transform: rotate(-180deg);
      transition: all 0.2s;
    }
    .detail_button_area button.active img {
      top: -2px;
      transform: rotate(-0deg);
      transition: all 0.2s;
    }
  }
  .user_profile_panel {
    text-align: left;
    & > dl dt {
      padding-bottom: 0.5rem;
    }
    .user_profile_picture {
      @include bgStyle(50%, 50%, 65%);
      border-radius: 50%;
      font-size: 12px;
      background-image: url(/assets/images/logos/logo_bcg_character_dark.svg);
      background-color: #ebeef0;
    }
    .user_profile_picture .pic_circle {
      background-color: transparent;
    }
  }
  .user_hlth_information {
    text-align: left;
    h2 {
      @include remFont("20px", $color: $theme-03, $weight: bold);
      padding: 1rem 7px;
    }
  }
  .user_hlth_article {
    border-bottom: 1px solid #e6e6e6;
    padding-bottom: 2rem;
    h6 {
      font-weight: bold;
    }
    span {
      font-weight: 400;
    }
  }
  .user_hlth_article:last-child {
    border-bottom: 0;
    padding-bottom: 0;
  }
  .user_hlth_article--default {
    h6 {
      display: inline-block;
      width: 90px;
    }
    dd {
      padding: 4px 7px;
    }
    dd:nth-child(odd) {
      background-color: #f8f8f8;
    }
  }
  .user_hlth_article--qna {
    h6 {
      padding: 0.5rem 0;
    }
    h6 b {
      @include setFont(inherit, $color: $theme-03, $weight: bold);
      padding-right: 5px;
    }
    dl {
      padding: 0 7px;
    }
    dd {
      border-bottom: 1px solid #e6e6e6;
      padding-bottom: 1rem;
      margin-bottom: 1rem;
    }
    dd:last-child {
      border-bottom: 0;
      margin-bottom: 0;
    }
    p {
      @include remFont("14px", $color: rgba($gray-01, 0.6));
      padding: 0.3rem 0;
    }
  }
  .hlth_rpt_process {
    padding: 1.4rem;
  }
  .rpt_result_article {
    background-color: white;
    border-radius: 10px;
    box-shadow: 5px 6px 20px rgba(211, 150, 27, 0.12);
    padding: 1rem 1.25rem;
    position: relative;
    h2 {
      @include setFont(inherit, $weight: bold, $color: $theme-02);
    }
    p {
      @include remFont("14px", $weight: 300);
    }
    p b {
      @include setFont(inherit, $weight: bold, $color: $theme-02);
    }
  }
  .rpt_result_article--ing {
    h2 {
      @include position($t: 1rem, $r: 1.25rem);
      @include remFont("16px", $weight: bold);
      display: inline-block;
    }
    .progress_caption {
      display: flex;
      justify-content: space-between;
    }
    .progress_bar {
      width: 100%;
      height: 10px;
      border-radius: 50px;
      background-color: #e6e6e6;
      display: inline-block;
      position: relative;
      margin: 15px 0 5px;
    }
    .progress_bar span {
      @include position($t: 0, $l: 0);
      border-radius: 50px;
      width: 20%;
      height: 100%;
      display: inline-block;
      background: linear-gradient(to right, #fff09b, #fabb3c);
    }
    .progress_caption li {
      @include setFont(12px, $color: $theme-02, $weight: 300);
    }
  }
  .rpt_result_article--complt {
    text-align: center;
    h2 {
      font-size: 22px;
    }
    p {
      padding: 0.25rem 0;
    }
    input {
      @include initInput(15px);
      @include remFont("14px", $color: $theme-02, $weight: 400);
      border: 1px solid $theme-02;
      padding: 4px 15px;
      margin: 0.5rem 0;
      letter-spacing: -0.2px;
    }
    input:active {
      background-color: #fff7ee;
    }
  }
}
</style>
