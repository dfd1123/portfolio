<template>
  <div
    id="dg-snb-alarm-wrapper"
    class="_popup_wrapper dg-cs-wrapper"
    v-infinite-scroll="NotifyLoad"
    :infinite-scroll-disabled="busy"
    :infinite-scroll-distance="limit"
  >
    <div class="_bg"></div>
    <div>
      <div class="_popup_wrap dg-alarm-card">
        <div class="_popup_title">
          <h2>
            알림
            <button
              type="button"
              class="_close_btn"
              @click="$emit('close-popup')"
            >
            </button>
          </h2>
        </div>
        <div
          class="_popup_content in-contents"
          style="padding:0"
        >
          <ul
            v-if="arlimLists.length > 0"
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
              v-for="arlimList in arlimLists"
              :key="'arlim'+arlimList.not_id"
              :class="['_list', {'new':arlimList.not_read_datetime?false:true}]"
              style="overflow: hidden;"
              @click="NativePopup(arlimList.not_url)"
            >
              <figure class="symbol-circle">
                <img
                  v-if="arlimList.store_profile_img"
                  :src="storageUrl + ConvertImage(arlimList.store_profile_img)"
                  alt="logo"
                  class="_imgtag"
                >
                <img
                  v-else-if="arlimList.profile_img"
                  :src="arlimList.profile_img.includes('http')?ConvertImage(arlimList.profile_img)[0]:storageUrl + ConvertImage(arlimList.profile_img)[0]"
                  alt="logo"
                  class="_imgtag"
                >
                <img
                  v-else-if="arlimList.mem_id === 0"
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
              <div class="_right_contnet">
                <h4
                  class="_store"
                  v-if="arlimList.from_storename"
                >
                  {{ arlimList.from_storename }}
                </h4>
                <h4
                  class="_store"
                  v-else-if="arlimList.from_name"
                >
                  {{ arlimList.from_name }}
                </h4>
                <h4
                  class="_store"
                  v-else
                >
                  동글 고객센터
                </h4>
                <span class="_date">{{ $moment(arlimList.not_datetime).format('YYYY-MM-DD') }}</span>
                <p class="_txt">
                  {{ arlimList.not_message }}
                </p>
              </div>
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
        offset: 0,
        count: 0,
        busy: false
      }
    },
    props: {
      arlimLists: {
        type: Array,
        required: true
      },
      limit: {
        type: Number,
        required: true
      }
    },
    methods: {
      NotifyLoad () {
        this.$emit('load-more')
      },
      async NotifyConfirm (id) {
        console.log(id)
        const params = {
          not_id: id,
          _method: 'put'
        }

        try {
          const res = (await this.$http.post(this.$APIURI + 'notification/read', params)).data

          if (res.state === 1) {

          } else {
            console.log(res.msg)
          }
        } catch (e) {
          console.log(e)
        }
      },
      async ToNotUrl (id) {
        console.log(id)
        this.popupStatus = false
        const arlim = this.arlimLists.filter(arlim => arlim.not_id === id)[0]
        if (arlim.not_url !== null) {
          this.NativePopup(arlim.not_url)
          await this.NotifyConfirm(id)
          this.$emit('reload')
        } else {
          await this.NotifyConfirm(id)
          this.$emit('reload')
        }
      }
    }
  }
</script>

<style lang="scss" scoped>
</style>
