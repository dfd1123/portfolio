<template>
  <!-- side-view -->
  <section class="side-view">
    <div class="side-container">
      <div class="side-contents">
        <div
          v-if="restoreCount > 0"
          style="float: right; color: red; vertical-align: middle;"
        >
          <div style="display: inline-block; margin-top: 10px; margin-right: 5px;">
            작성하던 내용이 삭제되었습니다.
          </div>
          <a
            href="#"
            class="btn-01 type-03 squre-round"
            @click.prevent="$emit('restoreButtonClick')"
          >{{ `되돌리기 ${restoreCount}초` }}</a>
        </div>

        <div id="sideAjaxContents">
          <!-- ajax data -->
          <h3 class="side-title">
            {{ title }}
          </h3>

          <!-- 정보 입력 영역 -->
          <div class="side-content-cell">
            <fieldset>
              <legend>정보 입력 폼</legend>
              <div class="form-group">
                <div class="form-group-list">
                  <ViewInfor
                    v-for="(vData,index) in vDatas"
                    :key="index"
                    :view-data="vData"
                    :status="status"
                    @download="$emit('download', $event)"
                    @inputButtonClick="$emit('inputButtonClick', $event)"
                  />
                </div>
              </div>
            </fieldset>
          </div>
          <!-- //정보 입력 영역 -->

          <div class="btn-page-wrap big">
            <p class="btn-pos-right">
              <BtagButton
                v-if="useDelete"
                btn-type="type-03"
                btn-name="삭제"
                @click-event="Delete"
              />
              <BtagButton
                v-if="useEdit"
                btn-type="type-01"
                btn-name="수정"
                @click-event="Edit"
              />
            </p>
          </div>

          <!-- //ajax data -->
        </div>

        <button
          type="button"
          class="btn-side-close"
          @click="$store.commit('EditSideComponentClose')"
        >
          사이드 닫기
        </button>
      </div>
      <div
        class="side-cover"
        @click="$store.commit('EditSideComponentClose')"
      ></div>
    </div>
  </section>
  <!-- //side-view -->
</template>

<script>
  import ViewInfor from '@/components/side_component/ViewInfor.vue'
  import BtagButton from '@/components/button/BtagButton.vue'

  export default {
    components: {
      ViewInfor,
      BtagButton
    },
    data () {
      return {
        status: 'edit',
        vDatas: []
      }
    },
    props: {
      title: {
        type: String,
        required: true
      },
      viewDatas: {
        type: Array,
        required: true,
        default: () => {
          return {}
        }
      },
      id: {
        type: String,
        default: ''
      },
      useEdit: {
        type: Boolean,
        default: true
      },
      useDelete: {
        type: Boolean,
        default: true
      },
      restoreCount: {
        type: Number,
        default: 0
      }
    },
    created () {
      this.vDatas = this.viewDatas
    },
    watch: {
      viewDatas () {
        this.vDatas = this.viewDatas
      }
    },
    methods: {
      Edit () {
        this.$emit('edit-event')
      },
      Delete () {
        this.$emit('delete-event')
      }
    }
  }
</script>

<style scoped>
</style>
