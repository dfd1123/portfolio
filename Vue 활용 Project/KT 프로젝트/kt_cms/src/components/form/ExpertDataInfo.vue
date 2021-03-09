<template>
  <div>
    <h3 class="con-title">
      전문가별 자료
      <a
        style="float: right; margin-top: -9px;"
        class="btn btn-01 type-03"
        @click.prevent="downloadAll"
      >전체자료 다운로드</a>
    </h3>
    <div
      v-if="dataList.length > 0"
      class="service-form"
      :style="{'height': setting.height ? setting.height : 'initial'}"
    >
      <div
        v-for="(data, index) in dataList"
        :key="index"
        class="form-group"
      >
        <div class="form-group-list">
          <div class="table-list-data">
            <div class="form-group-cell grid-02">
              <label class="form-title">전문가ID</label>
              <Input
                :id="index + '_' + data.videTlkDataUuid + '_' + status"
                type="text"
                :readonly="true"
                :value="data.userInfo && data.userInfo.userId || null"
                class="form-input"
                style="width:100%;"
                width="100%;"
              />
            </div>
            <div class="form-group-cell grid-02">
              <label class="form-title">녹화영상</label>
              <button
                class="btn btn-01"
                style="background-color: gray; width: 100%"
                :style="{visibility: get(data,'parsedDataUuid.data', null) ? 'visible' : 'hidden'}"
                @click.prevent="OnDownloadFile(data.parsedDataUuid.data)"
              >
                녹화영상 다운로드
              </button>
            </div>
            <div class="form-group-cell grid-02">
              <label class="form-title">통화시작일시</label>
              <Input
                :id="index + '_' + data.xpertVideTlkCretDt + '_' + status"
                type="text"
                :readonly="true"
                :value="data.xpertVideTlkCretDt || null"
                class="form-input"
                style="width:100%;"
                width="100%;"
              />
            </div>
            <div class="form-group-cell grid-02">
              <label class="form-title">통화종료일시</label>
              <Input
                :id="index + '_' + data.xpertVideTlkFnsDt + '_' + status"
                type="text"
                :readonly="true"
                :value="data.xpertVideTlkFnsDt || null"
                class="form-input"
                style="width:100%;"
                width="100%;"
              />
            </div>
          </div>
          <div class="form-group-cell grid-01">
            <div style="background-color: lightgray; border: 1px solid gray; overflow: hidden; clear: both">
              메모 보기
              <a
                style="float: right;"
                class="btn btn-01 type-03"
                @click.prevent="data.videTlkMemo ? data.folded = !data.folded : false"
              >토글</a>
            </div>
            <div
              v-show="data.videTlkMemo && !data.folded"
              style="border: 1px solid gray;"
            >
              <pre>{{ data.videTlkMemo }}</pre>
            </div>
          </div>
          <div class="form-group-cell grid-01 ">
            <div style="width: 100%; text-align: center; border: 1px solid lightgray;">
              <div>
                <span style="display: inline-block; width:60%; background-color: lightgray">자료명</span>
                <span style="display: inline-block; width:40%; background-color: lightgray">다운로드</span>
              </div>
            </div>
            <template v-if="!isEmpty(data.parsedSharedData)">
              <div
                v-for="share in data.parsedSharedData"
                style="width: 100%; border: 1px solid lightgray;"
                :key="share.id"
              >
                <div style="text-align: center; color: black; line-height: 30px;">
                  <span style="display: inline-block; width:60%; box-sizing: border-box;">{{ isEmpty(share.data) ? '-' : share.data.contsNm }}</span>
                  <span style="display: inline-block; width:40%; border-left: 1px solid lightgray; box-sizing: border-box;">
                    <a
                      class="btn btn-01"
                      style="background-color: gray; width: 60%; height: 30px; line-height: 30px;"
                      :style="{visibility: isEmpty(share.data) ? 'hidden' : 'visible'}"
                      @click.prevent="OnDownloadFile(share.data)"
                    >다운로드</a>
                  </span>
                </div>
              </div>
            </template>
            <template v-else>
              <div style="width: 100%; border: 1px solid lightgray;">
                <div style="text-align: center; color: black; line-height: 30px;">
                  <span style="display: inline-block; width:100%; box-sizing: border-box;">등록된 자료가 없습니다.</span>
                </div>
              </div>
            </template>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
  import Input from '@/components/form/input.vue'
  import { saveAs } from 'file-saver'
  import mime from 'mime-types'

  export default {
    components: {
      Input
    },
    data () {
      return {
        dataList: []
      }
    },
    props: {
      setting: {
        type: Object,
        required: true,
        default: () => ({})
      },
      status: {
        type: String,
        required: true
      }
    },
    watch: {
      setting () {
        try {
          this.dataList = this.setting.list.map(x => ({ ...x, folded: false }))
        } catch (e) {
          console.log(e)
        }
      }
    },
    computed: {
    },
    methods: {
      async OnDownloadFile (data) {
        try {
          this.$store.commit('progressComponentShow')

          const params = {
            svcId: data.svcId,
            contsId: data.contsId
          }
          const headers = {}

          if (['03', '04'].includes(this.auth)) {
            params.svcId = this.user.svc_id
          }

          if (params.svcId) {
            headers['X-Svc-Id'] = params.svcId
            delete params.svcId
          }

          await this.$http
            .get(this.$BASEURL + `/content/${headers['X-Svc-Id']}/${params.contsId}`, {
              responseType: 'blob',
              headers
            })
            .catch(e => e.response)
            .then(async res => {
              const extension = mime.extension(res.headers['content-type'])
              const tempName = `${data.contsNm}.${extension}`
              if (extension === 'json') {
                const result = JSON.parse(await new Response(new Blob([res.data])).text())
                throw this.set(result, 'response.data.message', result.message)
              } else {
                saveAs(new Blob([res.data]), tempName)
              }
            })
        } catch (e) {
          console.log(e)
          alert(e.response ? e.response.data.message : e)
        } finally {
          this.$store.commit('progressComponentHide')
        }
      },
      downloadAll () {
        this.dataList
          .map(data => [(data.parsedDataUuid || null), ...(data.parsedSharedData || [])])
          .flat()
          .map(x => x.data)
          .filter(x => x)
          .forEach(x => this.OnDownloadFile(x))
      }
    }
  }
</script>

<style scoped>
  .btn-service-add {
    float: right;
    margin-top: -9px;
    min-width: initial;
  }

  .form-group {
    padding: 2px;
    padding-top: 10px;
    padding-bottom: 0px;
    border: 1px solid #666;
    margin: 10px;
  }

  .form-group-list {
    margin: 0;
  }

  .form-title {
    margin-bottom: 0px;
  }

  .service-form {
    overflow-y: auto;
  }
</style>
