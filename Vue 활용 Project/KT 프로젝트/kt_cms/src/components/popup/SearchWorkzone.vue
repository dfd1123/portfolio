<template>
  <div class="layer-pop layer-pop-parents wrkzone-search">
    <div class="list-search-area">
      <div class="">
        <ul class="grid-layout">
          <li class="row-1">
            <div class="title-input-cell">
              <em>작업구역명 조회</em>
              <div class="search-input">
                <input
                  type="text"
                  class="auto-width-full"
                  v-model="keyword"
                  @keyup.enter="Search"
                >
                <button
                  type="button"
                  @click="Search"
                >
                  <span>검색</span>
                </button>
              </div>
            </div>
          </li>
        </ul>
      </div>
    </div>

    <!-- 작업구역조회 검색 결과 영역 -->
    <div
      class="overflow-y-scroll"
      style="max-height:209px"
    >
      <table class="list">
        <caption>리스트 테이블 표</caption>
        <colgroup>
          <col style="width:60px;">
          <col style="width:auto;">
          <col style="width:auto;">
          <col style="width:auto;">
        </colgroup>
        <thead>
          <tr>
            <th scope="col">
            </th>
            <th scope="col">
              작업구역ID
            </th>
            <th scope="col">
              구역명
            </th>
            <th scope="col">
              상위작업영역ID
            </th>
          </tr>
        </thead>
        <tbody v-if="list.length > 0">
          <tr
            v-for="item in list"
            :key="item.svcId + item.wrkZoneId"
          >
            <td>
              <p class="check-type01 single">
                <input
                  type="radio"
                  :id="status + 'check_' + item.svcId + '_' + item.wrkZoneId"
                  :name="status + 'check_' + item.svcId + '_' + item.wrkZoneId"
                  :value="item.wrkZoneId"
                  v-model="radioValue"
                  class="single_expert_check"
                ><label :for="status + 'check_' + item.svcId + '_' + item.wrkZoneId"><span></span></label>
              </p>
            </td>
            <td>{{ item.wrkZoneId }}</td>
            <td>{{ item.wrkZoneNm }}</td>
            <td>{{ item.upWrkZoneId || '-' }}</td>
          </tr>
        </tbody>
        <tbody v-else>
          <tr>
            <td
              colspan="4"
              style="height:185px;"
            >
              데이터가 존재하지 않습니다.
            </td>
          </tr>
        </tbody>
      </table>
    </div>
    <!-- //작업구역 검색 결과 영역 -->
    <div class="btn-page-wrap big">
      <button
        type="button"
        @click="layerPopClose()"
        class="btn-01 type-02"
      >
        닫기
      </button>
      <button
        type="button"
        class="btn-01 type-01"
        @click="AddSubmit"
      >
        추가
      </button>
    </div>
  </div>
</template>

<script>
  export default {
    data: function () {
      return {
        keyword: '',
        list: [],
        radioValue: ''
      }
    },
    props: {
      wrkZoneId: {
        type: String,
        default: null
      },
      svcId: {
        type: String,
        default: null
      },
      status: {
        type: String,
        required: true
      },
      preLoading: {
        type: Boolean,
        default: false
      }
    },
    computed: {
      selectedItem () {
        return this.list.find((x) => x.wrkZoneId === this.radioValue)
      }
    },
    created () {
      setTimeout(() => {
        if (this.preLoading) {
          this.GetData()
        }
      }, 0)
    },
    watch: {
      async wrkZoneId (newValue, oldValue) {
        if (this.status === 'edit') {
          this.radioValue = newValue
        }
      },
      async svcId (newValue, oldValue) {
        if (this.status === 'edit') {
          if (newValue) {
            await this.GetData()
            if (this.selectedItem) {
              this.$emit('value-found', { ...this.selectedItem })
            }
          } else {
            this.list = []
            this.$emit('value-found', null)
          }
        } else if (this.status === 'create') {
          if (newValue) {
            await this.GetData()
            this.radioValue = null
            this.$emit('value-found', null)
            this.$emit('value-set', null)
          } else {
            this.list = []
            this.radioValue = null
            this.$emit('value-found', null)
            this.$emit('value-set', null)
          }
        }
      }
    },
    methods: {
      async GetData () {
        try {
          if (!this.svcId) {
            this.list = []
            return
          }

          const params = {
            svcId: this.svcId,
            wrkZoneNm: this.keyword || null
          }
          const headers = {}

          if (['03', '04'].includes(this.auth)) {
            params.svcId = this.user.svc_id
          }

          if (params.svcId) {
            headers['X-Svc-Id'] = params.svcId
            delete params.svcId
          }

          this.list = await this.$http
            .get(this.$BASEURL + '/zone', { params, headers })
            .then(this.NormalOrError)
            .then(res => res.data.data)
        } catch (e) {
          console.log(e)
          alert(e.response ? e.response.data.message : e)
        }
      },
      Search () {
        this.GetData()
      },
      AddSubmit () {
        if (this.selectedItem) {
          this.$emit('value-set', { ...this.selectedItem })
        }

        this.keyword = ''
        this.layerPopClose()
      },
      layerPopClose () {
        window.$('.wrkzone-search').stop(true).fadeOut(350)
      }
    }
  }
</script>

<style scoped>
  .check-type01 input[type="radio"] {
    position: absolute;
    top: 0;
    left: 0;
    width: 0;
    height: 0;
    opacity: 0;
    filter: alpha(opacity=0);
  }
  .check-type01.single input[type="radio"] + label {
    height: 20px;
    padding-left: 20px;
  }
  .check-type01 input[type="radio"]:disabled + label {
    opacity: 0.5;
  }
  .check-type01 input[type="radio"] + label {
    position: relative;
    display: inline-block;
    padding-left: 32px;
    color: #666;
    font-size: 14px;
    line-height: 20px;
    word-break: keep-all;
    cursor: pointer;
  }
  .check-type01.single input[type="radio"] + label span {
    margin-right: 0;
  }
  .check-type01 input[type="radio"] + label span {
    position: absolute;
    top: 0;
    left: 0;
    display: inline-block;
    width: 20px;
    height: 20px;
    border: 1px solid #a5a5a5;
    border-radius: 3px;
    background-color: #fff;
    background-image: url(/images/icon/icon_checkbox.png);
    background-repeat: no-repeat;
    background-position: 0 0;
    box-sizing: border-box;
  }
  .check-type01 input[type="radio"]:checked + label span {
    background-position: 0 -18px;
  }

  .layer-pop.left {
    left: 0;
  }
</style>
