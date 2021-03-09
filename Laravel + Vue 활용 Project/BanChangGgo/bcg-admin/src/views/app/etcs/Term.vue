<template>
  <div>
    <b-row>
      <b-colxx xxs="12">
        <piaf-breadcrumb :heading="$t('menu.term')" />
        <div class="separator mb-5"></div>
      </b-colxx>
    </b-row>
    <b-row>
      <b-colxx xxs="12">
        <b-card :title="$t('menu.term')">
          <b-form @submit.prevent="TermUpdate">
            <b-form-group label="버전">
              <b-form-input type="text" v-model="version" placeholder="버전" />
            </b-form-group>
            <b-form-group label="서비스 이용약관">
              <b-textarea v-model="terms_service" :rows="10" :max-rows="10" placeholder="서비스 이용약관" />
            </b-form-group>
            <b-form-group label="개인정보 처리방침">
              <b-textarea
                v-model="privacy_policy"
                :rows="10"
                :max-rows="10"
                placeholder="개인정보 처리방침"
              />
            </b-form-group>
            <b-form-group label="유료 서비스 이용약관">
              <b-textarea
                v-model="pay_terms_service"
                :rows="10"
                :max-rows="10"
                placeholder="유료 서비스 이용약관"
              />
            </b-form-group>
            <b-button type="submit" variant="primary" class="mt-4">수정</b-button>
          </b-form>
        </b-card>
      </b-colxx>
    </b-row>
  </div>
</template>
<script>
export default {
  name: 'Term',
  components: {
  },
  data () {
    return {
      version: '',
      terms_service: '',
      privacy_policy: '',
      pay_terms_service: ''
    }
  },
  created: function () {
    this.settingLoad()
  },
  methods: {
    settingLoad () {
      try {
        this.$axios.get('/terms').then((response) => {
          console.log(response)
          this.version = response.data[0].version
          this.terms_service = response.data[0].terms_service
          this.privacy_policy = response.data[0].privacy_policy
          this.pay_terms_service = response.data[0].pay_terms_service
        })
      } catch (e) {
        console.log(e)
      }
    },
    TermUpdate () {
      try {
        this.$axios.post('/terms', {
          version: this.version,
          terms_service: this.terms_service,
          privacy_policy: this.privacy_policy,
          pay_terms_service: this.pay_terms_service
        }).then((response) => {
          this.addNotification('primary')
        })
      } catch (e) {
        console.log(e)
      }
    },
    addNotification (
      type = 'success',
      title = '안내',
      message = '변경되었습니다'
    ) {
      this.$notify(type, title, message, { duration: 3000, permanent: false })
    }
  }
}
</script>
