<template>
  <!-- side-view -->
  <section class="side-view">
    <div class="side-container">
      <div class="side-contents">
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
                    v-for="(viewData,index) in viewDatas"
                    :key="index"
                    :view-data="viewData"
                    :status="status"
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
                btn-type="type-01"
                btn-name="등록"
                @click-event="Create"
              />
            </p>
          </div>

          <!-- //ajax data -->
        </div>

        <button
          type="button"
          class="btn-side-close"
          @click="$store.commit('CreateSideComponentClose')"
        >
          사이드 닫기
        </button>
      </div>
      <div
        class="side-cover"
        @click="$store.commit('CreateSideComponentClose')"
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
        status: 'create'
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
      }
    },
    methods: {
      Create () {
        this.$emit('create-event')
      }
    }
  }
</script>

<style scoped>
</style>
