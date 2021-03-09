<template>
  <div>
    <h2 class="page-title">
      서비스 변경
    </h2>
    <div class="service-list-wrap">
      <ul class="service-list">
        <li
          v-for="svc in svcList"
          :key="svc.svcId"
          class="service-list-cell"
        >
          <div
            class="service-list-item"
            :class="{selected: svc.svcId === selectedSvcId}"
            @click="select(svc)"
            style="word-break: keep-all;"
          >
            <div class="d-t">
              <div class="d-c">
                <div class="service-item-name">
                  {{ svc.svcNm }}
                </div>
                <div class="service-item-id">
                  {{ svc.svcId }}
                </div>
                <div class="service-item-date">
                  ({{ $moment(svc.recntLoginDt).format('YYYY-MM-DD HH:mm:ss') }})
                </div>
              </div>
            </div>
          </div>
        </li>
      </ul>
    </div>
  </div>
</template>

<script>
  import { mapMutations } from 'vuex'
  export default {
    name: 'Select',
    data () {
      return {
        svcList: [],
        selectedSvcId: null
      }
    },
    async created () {
      const svcId = this.FirstData(this.user.svc_id_list)
      if (!svcId) {
        this.svcList = []
        return
      }

      const res1 = await this.$http
        .get(this.$BASEURL + '/user/info', {
          params: {
            userUuid: this.user.user_uuid
          },
          headers: {
            'X-Svc-Id': svcId
          }
        })
        .then(this.NormalOrError)
        .then(res => res.data.data)
      
      const list = []
      for (const svc in this.user.svc_id_list) {
        const res2 = await this.$http
          .get(this.$BASEURL + '/service/ids', {
            headers: {
              'X-Svc-Id': this.user.svc_id_list[svc]
            }
          })
          .then(this.NormalOrError)
          .then(this.FirstOrError)

        const found = res1.find(x => x.svcId === res2.svcId)
        if (found) {
          list.push({ ...found, ...res2 })
        }
      }

      this.svcList = list
      const found = list.find(x => x.recntLoginSvcYn === 'Y')
      if (found) {
        this.selectedSvcId = found.svcId
        this.setSvcId(this.selectedSvcId)
      }
    },
    methods: {
      ...mapMutations(['setSvcId']),
      async select (svcInfo) {
        try {
          this.$store.commit('progressComponentShow')

          const params = {
            userUuid: this.user.user_uuid,
            recntLoginSvcYn: 'Y'
          }
          const headers = {}

          if (['03', '04'].includes(this.auth)) {
            params.svcId = svcInfo.svcId
          }

          if (params.svcId) {
            headers['X-Svc-Id'] = params.svcId
            delete params.svcId
          }

          await this.$http
            .put(this.$BASEURL + '/user/service', params, { headers })
            .then(this.NormalOrError)

          this.selectedSvcId = svcInfo.svcId
          this.setSvcId(this.selectedSvcId)
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
