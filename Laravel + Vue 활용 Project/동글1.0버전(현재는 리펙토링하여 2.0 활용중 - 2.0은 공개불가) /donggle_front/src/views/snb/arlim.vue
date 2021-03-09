<template>
  <div
    id="dg-snb-alarm-wrapper"
    class="dg-cs-wrapper"
  >
    <div class="l-page-wrapper">
      <div class="dg-alarm-card">
        <h3 class="in-subject">
          알림
        </h3>
        <div class="in-contents">
          <ul
            v-if="arlims.length > 0"
            v-infinite-scroll="NotifyLoad"
            :infinite-scroll-disabled="busy"
            :infinite-scroll-distance="limit"
            class="_alarm_list_group"
          >
            <!-- 알림 리스트 없을 때 -->
            <!-- <li class="nothing-history">
              <img src="/images/icon/empty_alarm.svg" alt="icon empty" class="in-empty-icon" />
              <span class="in-empty-ment">알람이 없습니다.</span>
            </li> -->
            <!-- 알림 리스트 없을 때 E -->
            <!-- 알림 리스트 있을 때 -->
            <li
              v-for="arlim in arlims"
              :key="'arlim_'+arlim.not_id"
              :class="['_list', {'new':arlim.not_read_datetime === null}]"
              @click="ToNotUrl(arlim.not_id)"
            >
              <figure class="symbol-circle">
                <img
                  v-if="arlim.store_profile_img"
                  :src="storageUrl + ConvertImage(arlim.store_profile_img)"
                  alt="logo"
                  class="_imgtag"
                >
                <img
                  v-else-if="arlim.profile_img"
                  :src="arlim.profile_img.includes('http')?ConvertImage(arlim.profile_img)[0]:storageUrl + ConvertImage(arlim.profile_img)[0]"
                  alt="logo"
                  class="_imgtag"
                >
                <img
                  v-else-if="arlim.mem_id === 0"
                  src="/images/img/profile_admin.png"
                  alt="logo"
                  class="_imgtag"
                >
                <img
                  v-else
                  src="/images/img/basic_profile/01.png"
                  alt="logo"
                  class="_imgtag"
                >
              </figure>
              <h4
                class="_store"
                v-if="arlim.from_storename"
              >
                {{ arlim.from_storename }}
              </h4>
              <h4
                class="_store"
                v-else-if="arlim.from_name"
              >
                {{ arlim.from_name }}
              </h4>
              <h4
                class="_store"
                v-else
              >
                동글 고객센터
              </h4>
              <span class="_date">{{ $moment(arlim.not_datetime).format('YYYY-MM-DD') }}</span>
              <p class="_txt">
                {{ arlim.not_message }}
              </p>
            </li>
            <!-- 알림 리스트 있을 때 E-->
          </ul>
          <ul
            v-else
            class="_alarm_list_group"
          >
            <li class="nothing-history">
              <img
                src="/images/icon/empty_alarm.svg"
                alt="icon empty"
                class="in-empty-icon"
              >
              <span class="in-empty-ment">알람이 없습니다.</span>
            </li>
          </ul>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
  import infiniteScroll from 'vue-infinite-scroll'
  export default {
    directives: { infiniteScroll },
    data: function () {
      return {
        arlims: [],
        allCount: 0,
        limit: 10,
        offset: 0,
        busy: false
      }
    },
    async created () {
      this.$store.commit('ProgressShow')
      await this.NotifyLoad()
      this.$store.commit('ProgressHide')
    },
    methods: {
      async NotifyLoad () {
        const params = {
          limit: this.limit,
          offset: this.offset
        }

        try {
          const res = (await this.$http.get(this.$APIURI + 'notification/list', { params })).data
          if (res.state === 1) {
            this.arlims = res.query.notification
            this.allCount = res.query.count
            this.offset += res.query.notification.length

            if (this.allCount === this.offset) {
              this.busy = true
            }
          } else {
            console.log(res.msg)
          }
        } catch (e) {
          console.log(e)
        }

        this.busy = false
      },
      async NotifyConfirm (id) {
        const params = {
          not_id: id,
          _method: 'put'
        }

        try {
          const res = (await this.$http.post(this.$APIURI + 'notification/read', params)).data

          if (res.state === 1) {
            const res2 = (await this.$http.get(this.$APIURI + 'notification/list', { params: { offset: 0, limit: this.offset } })).data
            if (res.state === 1) {
              this.arlims = res2.query.notification
              this.allCount = res2.query.count
              this.offset = res2.query.notification.length

              if (this.allCount === this.offset) {
                this.busy = true
              }
            } else {
              console.log(res.msg)
            }
          } else {
            console.log(res.msg)
          }
        } catch (e) {
          console.log(e)
        }
      },
      async ToNotUrl (id) {
        this.popupStatus = false
        const arlim = this.arlims.filter(arlim => arlim.not_id === id)[0]
        if (arlim.not_url !== null) {
          window.open(arlim.not_url)
          this.NotifyConfirm(id)
        } else {
          await this.NotifyConfirm(id)
          this.offset = 0
          this.NotifyLoad()
        }
      }
    }
  }
</script>

<style lang="scss" scoped>
</style>
