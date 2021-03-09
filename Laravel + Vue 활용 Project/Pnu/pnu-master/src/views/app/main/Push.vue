<template>
  <div>
    <b-row>
      <b-colxx xxs="12">
        <piaf-breadcrumb :heading="$t('menu.push')" />
        <div class="separator mb-5"></div>
      </b-colxx>
    </b-row>
    <b-row>
      <b-colxx xxs="12" lg="6">
        <b-card class="mb-4" :title="$t('menu.push')">
          <b-form>
            <b-form-group label="제목">
              <b-form-input v-model="title" />
            </b-form-group>
            <b-form-group label="내용">
              <b-form-input v-model="body" />
            </b-form-group>
          </b-form>
          <b-button variant="outline-danger" @click="push">전송</b-button>
        </b-card>
      </b-colxx>
      <b-colxx xxs="12" lg="6">
        <b-card class="mb-4" title="전송기록">
          <template v-if="msgs.length > 0">
            <b-form v-for="(msg, index) in msgs" :key="index">
              <b-form-group
                :label="`${$moment(msg.reg_dt).format('YYYY-MM-DD HH:mm:ss')} : ${msg.title}`"
              >
                <p>{{msg.body}}</p>
              </b-form-group>
            </b-form>
          </template>
          <template v-else>(기록이 없습니다)</template>
        </b-card>
      </b-colxx>
    </b-row>
  </div>
</template>

<script>
export default {
  name: 'User',
  data () {
    return {
      title: null,
      body: null,
      msgs: []
    }
  },
  mounted () {
    this.fetchData()
  },
  methods: {
    async fetchData () {
      try {
        this.$store.commit('setProcessing', true)
        const data = await this.$axios
          .get('/push_topic_history')
          .then(res => res.data)

        this.msgs = data
      } catch (e) {
        console.log(e)
      } finally {
        this.$store.commit('setProcessing', false)
      }
    },
    async push () {
      if (!confirm('정말로 전송하시겠습니까?')) {
        return
      }

      if (!this.title) {
        alert('제목을 입력해주세요')
        return
      }

      if (!this.body) {
        alert('제목을 입력해주세요')
        return
      }

      try {
        this.$store.commit('setProcessing', true)
        await this.$axios.post('/push_topic', {
          title: this.title,
          body: this.body
        })

        await this.fetchData()

        this.title = null
        this.body = null
        alert('전송이 완료되었습니다')
      } catch (e) {
        console.log(e)
        alert('전송 중 에러가 발생했습니다')
      } finally {
        this.$store.commit('setProcessing', false)
      }
    }
  }
}
</script>

<style scoped>
</style>
