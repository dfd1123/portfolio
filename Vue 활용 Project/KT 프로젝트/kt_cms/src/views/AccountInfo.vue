<template>
  <div>
    <div class="default-cell big">
      <h2 class="page-title new-border">
        사용자 정보
      </h2>
      <fieldset>
        <legend>정보 입력 폼</legend>
        <div class="form-group border-none">
          <div class="form-group-list">
            <div class="form-group-cell grid-04">
              <label class="form-title">고객ID</label>
              <div class="form-input">
                <input
                  type="text"
                  name=""
                  readonly=""
                  v-model="userInfo.custId"
                >
              </div>
            </div>

            <div class="form-group-cell grid-04">
              <label class="form-title">계정구분</label>
              <div class="form-input">
                <select
                  type="text"
                  name=""
                  v-model="userInfo.autCd"
                >
                  <option value="01">
                    플랫폼관리자
                  </option>
                  <option value="02">
                    고객관리자
                  </option>
                  <option value="03">
                    서비스관리자
                  </option>
                  <option value="04">
                    일반사용자
                  </option>
                </select>
              </div>
            </div>

            <div class="form-group-cell grid-04">
              <label class="form-title">사용자ID</label>
              <div class="form-input">
                <input
                  type="text"
                  name=""
                  readonly=""
                  v-model="userInfo.userId"
                >
              </div>
            </div>

            <div class="form-group-cell grid-04">
              <label class="form-title">사용자명</label>
              <div class="form-input">
                <input
                  type="text"
                  name=""
                  v-model="userInfo.userNm"
                >
              </div>
            </div>

            <div class="form-group-cell grid-04">
              <label class="form-title">비밀번호</label>
              <div class="form-input">
                <input
                  type="password"
                  name=""
                  v-model="userInfo.userPwd"
                >
              </div>
            </div>

            <div class="form-group-cell grid-04">
              <label class="form-title">비밀번호 확인</label>
              <div class="form-input">
                <input
                  type="password"
                  name=""
                  v-model="userInfo.userPwdConfirm"
                >
              </div>
            </div>

            <div class="form-group-cell grid-04">
              <label class="form-title">직책</label>
              <div class="form-input">
                <input
                  type="text"
                  name=""
                  v-model="userInfo.pos"
                >
              </div>
            </div>

            <div class="form-group-cell grid-04">
              <label class="form-title">소속</label>
              <div class="form-input">
                <input
                  type="text"
                  name=""
                  v-model="userInfo.rspof"
                >
              </div>
            </div>

            <div class="form-group-cell grid-04">
              <label class="form-title">이메일</label>
              <div class="form-input">
                <input
                  type="email"
                  name=""
                  v-model="userInfo.userEmail"
                >
              </div>
            </div>

            <div class="form-group-cell grid-04">
              <label class="form-title">전화번호</label>
              <div class="form-input">
                <input
                  type="text"
                  name=""
                  v-model="userInfo.userTelNo"
                >
              </div>
            </div>

            <div class="form-group-cell grid-04">
              <label class="form-title">프로필 사진</label>
              <image-box
                :input-data="userInfo.userImg"
                @input="userInfo.userImgFile = $event"
              />
            </div>
          </div>
        </div>
      </fieldset>
    </div>
    <div class="default-cell big">
      <h2 class="page-title new-border">
        서비스 정보
      </h2>
      <div class="table-list-data">
        <table class="read-table">
          <caption>리스트 테이블 표</caption>
          <colgroup>
            <col class="width-auto">
            <col class="width-auto">
            <col class="width-auto">
            <col class="width-auto">
            <col class="width-auto">
            <col class="width-auto">
          </colgroup>
          <thead>
            <tr>
              <th>서비스ID</th>
              <th>서비스명</th>
              <th>서비스상태</th>
              <th>서비스버전</th>
              <th>서비스설명</th>
              <th>권한</th>
            </tr>
          </thead>
          <tbody>
            <tr
              v-for="(service, index) in serviceInfo"
              :key="index"
            >
              <td>{{ service.svcId }}</td>
              <td>{{ service.svcNm }}</td>
              <td>{{ {'01': '활성', '02': '비활성', '03': '삭제'}[service.svcSttusCd] }}</td>
              <td>{{ service.svcVer }}</td>
              <td>{{ service.svcDesc }}</td>
              <td>{{ service.unitSvcUserAutCd }}</td>
            </tr>
          </tbody>
        </table>
      </div>
      <div class="btn-page-wrap">
        <button
          type="button"
          class="btn-01 type-01"
          @click="edit"
        >
          <span>수정</span>
        </button>
      </div>
    </div>
  </div>
</template>

<script>
  import mime from 'mime-types'
  import ImageBox from '@/components/form/imagebox'

  export default {
    name: 'Select',
    components: {
      ImageBox
    },
    data () {
      return {
        userInfo: {
          custId: null,
          aut_type_cd: null,
          userId: null,
          userNm: null,
          password: null,
          passwordConfirm: null,
          pos: null,
          rspof: null,
          userEmail: null,
          userTelNo: null,
          userImg: {},
          userImgFile: null
        },
        serviceInfo: []
      }
    },
    async created () {
      await this.FetchData()
    },
    methods: {
      async FetchData () {
        try {
          this.$store.commit('progressComponentShow')

          const params = {
            userUuid: this.user.user_uuid
          }
          const headers = {}

          if (['03', '04'].includes(this.auth)) {
            params.svcId = this.user.svc_id
          }

          if (params.svcId) {
            headers['X-Svc-Id'] = params.svcId
            delete params.svcId
          }

          const res = await this.$http
            .get(this.$BASEURL + '/user', { params, headers })
            .then(this.NormalOrError)
            .then(this.FirstOrError)

          const res1 = await this.$http
            .get(this.$BASEURL + `/user/${this.user.user_uuid}`, {
              responseType: 'blob',
              headers: {
                'X-Svc-Id': this.user.svc_id
              }
            })
            .then(res => {
              const extension = mime.extension(res.headers['content-type'])
              return (extension !== 'json') ? URL.createObjectURL(new Blob([res.data])) : null
            })
            .catch(() => null)

          const res2 = await this.$http
            .get(this.$BASEURL + '/user/service/info', { params, headers })
            .then(this.NormalOrError)
            .then(res => res.data.data)
            .then(res => res.map(x => {
              x.svcId = this.user.svc_id
              return x
            }))

          this.userInfo.userImg = {
            value: res1 || null
          }

          this.userInfo.custId = res.custId || null
          this.userInfo.autCd = res.autCd || null
          this.userInfo.userId = res.userId || null
          this.userInfo.userNm = res.userNm || ''
          this.userInfo.userPwd = null
          this.userInfo.userPwdComfirm = null
          this.userInfo.pos = res.pos || '' // 소속
          this.userInfo.rspof = res.rspof || '' // 직책
          this.userInfo.userEmail = res.userEmail || ''
          this.userInfo.userTelNo = res.userTelNo || ''

          this.serviceInfo = res2
        } catch (e) {
          console.log(e)
          alert(e.response ? e.response.data.message : e)
        } finally {
          this.$store.commit('progressComponentHide')
        }
      },
      async edit () {
        if (!confirm('정말로 수정하시겠습니까?')) {
          return
        }

        try {
          this.$store.commit('progressComponentShow')

          const params = {}
          params.userUuid = this.user.user_uuid
          params.amdrUuid = this.user.user_uuid

          params.custId = this.userInfo.custId
          params.autCd = this.userInfo.autCd
          params.userId = this.userInfo.userId
          params.userNm = this.userInfo.userNm
          params.userPwd = this.userInfo.userPwd
          params.userPwdComfirm = this.userInfo.userPwdComfirm
          params.pos = this.userInfo.pos // 소속
          params.rspof = this.userInfo.rspof // 직책
          params.userEmail = this.userInfo.userEmail
          params.userTelNo = this.userInfo.userTelNo

          params.userImgFile = this.userInfo.userImgFile

          if (!params.custId) {
            // 해당 값은 빈값으로 호출하면 에러
            delete params.custId
          }

          if (params.userPwd && params.userPwdComfirm) {
            if (params.userPwd !== params.userPwdComfirm) {
              alert('비밀번호가 일치하지 않습니다. 다시 확인해주세요')
              return
            }
          }
          delete params.userPwd
          delete params.userPwdComfirm

          if (!(params.userImgFile instanceof File)) {
            delete params.userImgFile
          }

          const headers = {
            'X-Svc-Id': this.user.svc_id,
            'Content-Type': 'multipart/form-data'
          }
          const formData = new FormData()
          for (const key in params) {
            formData.append(key, params[key])
          }

          const res = await this.$http
            .put(this.$BASEURL + '/user', formData, { headers })
            .then(this.NormalOrError)
            .then(this.FirstOrError)
            .catch(e => null)

          if (!res) {
            this.FetchData()
            alert('수정 완료')
            return
          }
        } catch (e) {
          console.log(e)
          alert(e.response ? e.response.data.message : e)
        } finally {
          this.$store.commit('progressComponentHide')
        }
      }
    }
  }
</script>

<style scoped>
  .service-list-item.selected {
    background-color: #1c254b;
  }

  .service-list-item.selected .service-item-name {
    color: #fff;
    opacity: 1;
  }

  .service-list-item.selected .service-item-date {
    color: #fff;
    opacity: 0.8;
  }
</style>
