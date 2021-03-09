<template>
  <div id="app">
    <layout-header
      title="스토어 설정"
      buttonRight="저장"
      @button-right-click="saveButtonClick"
    >
      <template
        v-if="store.company_name && store.cp_file"
        v-slot:title-message
      >
        <span
          v-if="store.confirm === 0"
          class="in-stat"
        >승인 대기중입니다</span>
        <span
          v-else-if="store.confirm === 2"
          class="in-stat"
        >
          승인이 거절되었습니다
          <small class="_reason">사유: {{store.reject_reason}}</small>
        </span>
      </template>
    </layout-header>

    <!-- contents -->
    <div id="admin-container">
      <div class="wrapper">
        <div id="page-setting-store-wrap">
          <div class="grid-col first">
            <div class="panel-default-container">
              <div class="panel-default-title">
                <h4 class="in-mainname">사업자 정보</h4>
                <span class="in-subtxt">※ 허위 정보를 등록할 시 강력히 처벌합니다.</span>
              </div>
              <div class="panel-default">
                <div
                  v-show="isStoreLoaded"
                  class="form-wrapper"
                >
                  <fieldset class="form-container type-01">
                    <div class="form-align">
                      <label
                        for="#"
                        class="form-align-tit"
                      >업체명</label>
                      <div class="form-align-input">
                        <input
                          v-model.trim="companyName"
                          type="text"
                          class="form-input-txt"
                          placeholder="동글스토어"
                        />
                      </div>
                    </div>

                    <div class="form-align">
                      <label
                        for="#"
                        class="form-align-tit"
                      >입점한 호수</label>
                      <div class="form-align-input">
                        <input
                          v-model="address"
                          type="text"
                          class="form-input-txt"
                        />
                      </div>
                    </div>

                    <div class="form-align">
                      <label
                        for="#"
                        class="form-align-tit"
                      >동글 브랜드네임</label>
                      <div class="form-align-input type-certi">
                        <div class="in-group">
                          <input
                            v-model.trim="brandname"
                            @keyup="brandNameCheck = false"
                            type="text"
                            class="form-input-txt"
                          />
                          <input
                            type="button"
                            class="rounded-square-btn btn-outline-navy none"
                            value="중복검사"
                            v-if="!brandNameCheck"
                            @click="DuplBrandName"
                          />
                          <input
                            type="button"
                            class="rounded-square-btn btn-outline-navy none"
                            value="사용가능"
                            v-else
                          />
                        </div>
                        <span class="form-caution">스토어에서는 업체가명으로 노출 됩니다.</span>
                      </div>
                    </div>

                    <div class="form-align">
                      <label
                        for="#"
                        class="form-align-tit"
                      >사업자등록번호</label>
                      <div class="form-align-input">
                        <input
                          v-model.trim="cpNumber"
                          type="text"
                          class="form-input-txt"
                          @change="AutoHypen(cpNumber)"
                        />
                      </div>
                    </div>

                    <div class="form-align">
                      <label
                        for="#"
                        class="form-align-tit"
                      >사업자등록증</label>
                      <div class="form-align-input type-certi">
                        <div class="in-group">
                          <input
                            v-model.trim="cpFileName"
                            type="text"
                            class="form-input-txt"
                            placeholder="동글스토어"
                          />
                          <input
                            v-uniq-id="'ex-certi-file'"
                            type="file"
                            class="rounded-square-btn btn-outline-navy none"
                            style="display: none"
                            @change="image3Change"
                          />
                          <label
                            v-uniq-for="'ex-certi-file'"
                            class="rounded-square-btn btn-outline-navy"
                          >업로드</label>
                        </div>
                      </div>
                    </div>

                    <div class="form-align">
                      <label
                        for="#"
                        class="form-align-tit"
                      >대표자명</label>
                      <div class="form-align-input">
                        <input
                          v-model.trim="ceoName"
                          type="text"
                          class="form-input-txt"
                        />
                      </div>
                    </div>

                    <div class="form-align">
                      <label
                        for="#"
                        class="form-align-tit"
                      >대표 전화번호</label>
                      <div class="form-align-input">
                        <input
                          v-model.trim="tel"
                          type="text"
                          class="form-input-txt"
                        />
                      </div>
                    </div>

                    <div class="form-align">
                      <label
                        for="#"
                        class="form-align-tit"
                      >매장 카카오톡 아이디</label>
                      <div class="form-align-input">
                        <input
                          v-model.trim="kakaoid"
                          type="text"
                          class="form-input-txt"
                        />
                      </div>
                    </div>
                  </fieldset>
                </div>
              </div>
            </div>
          </div>

          <div class="grid-col second">
            <div class="panel-default-container">
              <div class="panel-default-title">
                <h4 class="in-mainname">스토어 정보</h4>
              </div>
              <div class="panel-default">
                <div
                  v-show="isStoreLoaded"
                  class="form-wrapper"
                >
                  <fieldset class="form-container type-01">
                    <div class="form-align">
                      <label
                        for="#"
                        class="form-align-tit"
                      >프로필 이미지</label>
                      <div class="form-align-input type-img">
                        <input
                          v-uniq-id="'ex-file1'"
                          type="file"
                          class="none"
                          @change="image1Preview"
                        />
                        <label
                          v-uniq-for="'ex-file1'"
                          class="rounded-square-btn btn-outline-navy"
                        >이미지 업로드</label>
                        <span class="form-caution">
                          프로필 이미지는 80*80px 사이즈의 JPG, PNG 파일을
                          권장합니다.
                        </span>

                        <div class="form-upload-img-area type-circle">
                          <img
                            ref="image1"
                            alt="upload image"
                            style="display: none"
                          />
                        </div>
                      </div>
                    </div>

                    <div class="form-align">
                      <label
                        for="#"
                        class="form-align-tit"
                      >대표 이미지</label>
                      <div class="form-align-input type-img">
                        <input
                          v-uniq-id="'ex-file2'"
                          type="file"
                          class="none"
                          @change="image2Preview"
                        />
                        <label
                          v-uniq-for="'ex-file2'"
                          class="rounded-square-btn btn-outline-navy"
                        >이미지 업로드</label>
                        <span class="form-caution">
                          대표 이미지는 842*422px 사이즈의 JPG, PNG 파일을
                          권장합니다.
                        </span>

                        <div class="form-upload-img-area type-square">
                          <img
                            ref="image2"
                            alt="upload image"
                            style="display: none"
                          />
                        </div>
                      </div>
                    </div>

                    <div class="form-align">
                      <label
                        for="#"
                        class="form-align-tit"
                      >매장 인사글</label>
                      <div class="form-align-input">
                        <textarea
                          v-model="intro"
                          cols="30"
                          rows="10"
                          class="form-textarea"
                        ></textarea>
                      </div>
                    </div>
                  </fieldset>
                </div>
              </div>
            </div>
          </div>

          <div class="grid-col third">
            <div class="panel-default-container">
              <div class="panel-default-title">
                <h4 class="in-mainname">정산 받을 계좌 정보</h4>
              </div>
              <div class="panel-default">
                <div
                  v-show="isStoreLoaded"
                  class="form-wrapper"
                >
                  <fieldset class="form-container type-01">
                    <div class="form-align">
                      <label
                        for="#"
                        class="form-align-tit"
                      >은행명</label>
                      <div class="form-align-input">
                        <select
                          v-model="accountBank"
                          class="form-select"
                        >
                          <option :value="null">은행 선택</option>
                          <option
                            v-for="option in bankList"
                            :key="option.value"
                            :value="option.value+'/'+option.label"
                            @change="AccountBank($event.target.value)"
                          >{{option.label}}</option>
                        </select>
                      </div>
                    </div>

                    <div class="form-align">
                      <label
                        for="#"
                        class="form-align-tit"
                      >계좌번호</label>
                      <div class="form-align-input">
                        <input
                          v-model.trim="accountNumber"
                          type="text"
                          class="form-input-txt"
                        />
                      </div>
                    </div>

                    <div class="form-align">
                      <label
                        for="#"
                        class="form-align-tit"
                      >예금주</label>
                      <div class="form-align-input">
                        <div class="in-group">
                          <input
                            v-model.trim="accountName"
                            type="text"
                            class="form-input-txt"
                          />
                        </div>
                      </div>
                    </div>
                  </fieldset>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <layout-footer />
    </div>
    <!-- END contents -->
  </div>
</template>

<script>
import { mapActions } from 'vuex'
import { bankList } from '@/constants'

export default {
  name: 'SettingStore',
  data () {
    return {
      isStoreLoaded: true,
      companyName: null,
      address: null,
      brandname: null,
      cpType: '도매업',
      cpSectors: '남녀용 겉옷 및 셔츠 도매업',
      cpNumber: null,
      ceoName: null,
      email: null,
      accountBankNumber: null,
      accountBank: null,
      accountBankNumberOptions: this.bankList,
      accountNumber: null,
      accountName: null,
      postNum: null,
      extraAddr: null,
      addrJibeon: null,
      tel: null,
      faxNum: null,
      intro: null,
      kakaoid: null,
      confirm: null,
      cpFile: [],
      cpFileName: null,
      image: [],
      imageName: null,
      profileImg: [],
      profileImgName: null,
      brandNameCheck: false
    }
  },
  mounted () {
    this.fetchData()
  },
  computed: {
    bankList () {
      return Object.entries(bankList).map(([key, value]) => ({
        label: value,
        value: key
      }))
    }
  },
  methods: {
    ...mapActions(['getStore']),
    async fetchData () {
      try {
        this.loading(true)

        await this.getStore()

        this.$cookies.set('isConfirm', this.store.confirm === 1)

        if (!this.isStore) {
          // return
        }

        if (this.store.brandname) {
          this.brandNameCheck = true
        }

        this.confirm = this.store.confirm
        this.companyName = this.store.company_name || null
        this.address = this.store.address || null
        this.brandname = this.store.brandname || null
        this.cp_type = this.store.cp_type || '도매업'
        this.cp_sectors = this.store.cp_sectors || '남녀용 겉옷 및 셔츠 도매업'
        this.cpNumber = this.store.cp_number || null
        this.ceoName = this.store.ceo_name || null
        this.tel = this.store.tel || null
        this.kakaoid = this.store.kakaoid || null
        this.accountBank = (this.store.account_bank_number || '') + '/' + (this.store.account_bank || '')
        this.accountBankNumberOptions = this.bankList
        this.accountBankNumber = this.store.account_bank_number
        this.accountNumber = this.store.account_number
        this.accountName = this.store.account_name
        this.intro = this.store.intro
        this.cpFileName = JSON.parse(this.store.cp_file) || []
        this.profileImgName = JSON.parse(this.store.profile_img) || []
        this.imageName = JSON.parse(this.store.image) || []

        this.$nextTick(() => {
          const profileImg = this._head(
            JSON.parse(this.store.profile_img) || []
          )
          if (profileImg) {
            try {
              this.$refs.image1.src = this.storagePath(profileImg)
              this.$refs.image1.style.display = ''
            } catch { }
          }

          const image = this._head(JSON.parse(this.store.image) || [])
          if (image) {
            try {
              this.$refs.image2.src = this.storagePath(image)
              this.$refs.image2.style.display = ''
            } catch { }
          }

          const cpFile = this._head(JSON.parse(this.store.cp_file) || [])
          if (cpFile) {
            try {
              this.$refs.image3.src = this.storagePath(cpFile)
              this.$refs.image3.style.display = ''
            } catch { }
          }
        })

        this.isStoreLoaded = true
      } catch (e) {
        console.log(e)
        alert(e.response ? e.response.data.msg || '에러발생' : e)
      } finally {
        this.loading(false)
      }
    },
    verifyCompanyNumber () {
      try {
        alert('verifyCompanyNumber')
      } catch (e) {
        console.log(e)
        alert(e.response ? e.response.data.msg || '에러발생' : e)
      }
    },
    AccountBank (bankName) {
      this.accountBank = bankName
    },
    saveButtonClick () {
      this.updateStoreSetting()
    },
    Validation () {
      if (!this.companyName) {
        alert('업체명을 입력해주세요.')

        return false
      }

      if (!this.address) {
        alert('입점한 호수를 입력하세요.')

        return false
      }

      if (!this.ceoName) {
        alert('대표자명을 입력하세요.')

        return false
      }

      if (!this.brandname) {
        alert('동글 브랜드네임을 입력해주세요.')

        return false
      }

      if (!this.accountBank || !this.accountName || !this.accountNumber) {
        alert('정산 받을 계좌 정보를 입력해주세요.')

        return false
      }

      if (!this.tel) {
        alert('대표 전화번호를 입력해주세요.')

        return false
      }

      if (!this.kakaoid) {
        alert('매장 카카오톡 아이디를 입력해주세요.')

        return false
      }

      if (!this.cpFile.length === 0) {
        alert('사업자등록증 사본을 업로드 해주세요.')

        return false
      }

      if (this.profileImg.length === 0 && !this.profileImgName) {
        alert('프로필 이미지를 업로드 해주세요.')

        return false
      }

      if (this.image.length === 0 && !this.imageName) {
        alert('업체 대표 이미지를 업로드 해주세요.')

        return false
      }

      if (!this.intro) {
        alert('매장 인사글을 입력해주세요.')

        return false
      }

      if (this.companyName === this.brandname) {
        alert('업체명과 동글 브랜드네임이 같으면 안됩니다.')

        return false
      }

      if (!this.brandNameCheck) {
        alert('동글 브랜드네임 중복검사를 하지 않으셨습니다.')

        return false
      }

      return true
    },
    async storeStoreSetting () {
      if (!this.Validation()) {
        return false
      }

      try {
        this.loading(true)

        const data = {
          company_name: this.companyName,
          cp_type: this.cpType,
          cp_sectors: this.cpSectors,
          cp_number: this.cpNumber,
          ceo_name: this.ceoName,
          address: this.address,
          brandname: this.brandname,
          email: this.email,
          account_bank_number: this.accountBank ? this.accountBank.split('/')[0] : null,
          account_bank: this.accountBank ? this.accountBank.split('/')[1] : null,
          account_number: this.accountNumber,
          account_name: this.accountName,
          post_num: this.postNum,
          extra_addr: this.extraAddr,
          addr_jibeon: this.addrJibeon,
          tel: this.tel,
          kakaoid: this.kakaoid,
          fax_num: this.faxNum,
          intro: this.intro,
          cp_file: this.cpFile,
          image: this.image,
          profile_img: this.profileImg
        }

        const { formData, headers } = this.formDatas(data)

        await this.$axios
          .post('/api/store/store', formData, { headers })
          .then(this.normalOrError)

        await this.fetchData()
      } catch (e) {
        console.log(e)
        alert(e.response ? e.response.data.msg || '에러발생' : e)
      } finally {
        this.loading(false)
      }
    },
    async updateStoreSetting () {
      if (!this.Validation()) {
        return false
      }

      try {
        this.loading(true)

        const data = {
          _method: 'put',
          address: this.address,
          brandname: this.brandname,
          company_name: this.companyName,
          cp_type: this.cpType,
          cp_sectors: this.cpSectors,
          cp_number: this.cpNumber,
          ceo_name: this.ceoName,
          email: this.email,
          account_bank_number: this.accountBank ? this.accountBank.split('/')[0] : null,
          account_bank: this.accountBank ? this.accountBank.split('/')[1] : null,
          account_number: this.accountNumber,
          account_name: this.accountName,
          post_num: this.postNum,
          extra_addr: this.extraAddr,
          addr_jibeon: this.addrJibeon,
          tel: this.tel,
          kakaoid: this.kakaoid,
          fax_num: this.faxNum,
          intro: this.intro,
          cp_file: this.cpFile,
          profile_img: this.profileImg,
          image: this.image
        }

        const { formData, headers } = this.formDatas(data)

        await this.$axios
          .post('/api/store/store/update', formData, { headers })
          .then(this.normalOrError)

        await this.fetchData()
      } catch (e) {
        console.log(e)
        alert(e.response ? e.response.data.msg || '에러발생' : e)
      } finally {
        this.loading(false)
      }
    },
    AutoHypen (companyNum) {
      companyNum = companyNum.replace(/[^0-9]/g, '')
      if (companyNum.length !== 10) {
        alert('유효한 사업자 번호를 입력해주세요')
        this.cpNumber = ''
      } else {
        var tempNum = ''

        tempNum += companyNum.substr(0, 3)

        tempNum += '-'

        tempNum += companyNum.substr(3, 2)

        tempNum += '-'

        tempNum += companyNum.substr(5)

        this.cpNumber = tempNum
      }
    },
    async image1Preview (e) {
      const { file, dataURL } = await this.imagefileChange(e)

      this.profileImg = [file]
      this.$refs.image1.src = dataURL
      this.$refs.image1.style.display = ''
    },
    async image2Preview (e) {
      const { file, dataURL } = await this.imagefileChange(e)

      this.image = [file]
      this.$refs.image2.src = dataURL
      this.$refs.image2.style.display = ''
    },
    async image3Change (e) {
      const { file } = await this.imagefileChange(e)

      this.cpFile = [file]

      this.cpFileName = this.cpFile[0].name
    },
    async DuplBrandName () {
      if (this.brandname.includes(this.companyName)) {
        alert('동글 브랜드네임에 업체명이 포함되어 있으면 안됩니다.')

        return false
      }
      console.log(this.brandname.length)
      if (this.brandname.length < 2) {
        alert('동글 브랜드네임이 너무 짧습니다.')

        return false
      }

      const params = {
        brand_name: this.brandname
      }

      try {
        this.loading(true)
        const res = (await this.$axios.get('/api/store/store/brandname_check', { params })).data

        if (res.state === 1) {
          this.brandNameCheck = res.query
          if (res.query) {
            alert('사용할 수 있는 브랜드네임 입니다!')
          } else {
            alert('이미 사용중인 브랜드네임 입니다!')
          }
        } else {
          console.log(res.msg)
        }
      } catch (e) {
        console.log(e)
      } finally {
        this.loading(false)
      }
    }
  }
}
</script>
