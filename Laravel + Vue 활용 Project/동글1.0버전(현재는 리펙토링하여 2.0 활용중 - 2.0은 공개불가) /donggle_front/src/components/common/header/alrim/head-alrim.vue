<template>
  <div
    class="login_menu top_menu top_menu_alrim"
    @click="SubToArlimePage"
  >
    <div class="dg-hd-alrim_label">
      알림
      <!-- top menu popup -->
      <div class="dg-hd-popup_wrap dg-hd-popup_alrim_wrap">
        <h2>알림</h2>
        <div v-if="arlims.length > 0">
          <div
            v-for="(arlim, index) in arlims"
            :key="'arlim'+index"
            :class="['dg-hd-popup dg-hd-popup_alrim clear_both', {'new':arlim.not_read_datetime === null}]"
            @click="ToNotUrl(arlim.not_id)"
          >
            <div
              class="dg-hd-popup-logo dg-hd-popup_alrim-logo"
              v-if="arlim.store_profile_img"
              :style="(ConvertImage(arlim.store_profile_img).length > 0)?'background-image:url('+storageUrl + ConvertImage(arlim.store_profile_img)[0]+')':'background-image:url(/images/img/thumbnail.png)'"
            >
              {{ arlim.from_storename }}
            </div>
            <div
              class="dg-hd-popup-logo dg-hd-popup_alrim-logo"
              v-else-if="arlim.profile_img"
              :style="'background-image:url('+ (arlim.profile_img.includes('http')?ConvertImage(arlim.profile_img)[0]:storageUrl + ConvertImage(arlim.profile_img)[0]) +')'"
            >
              {{ arlim.from_name }}
            </div>
            <div
              class="dg-hd-popup-logo dg-hd-popup_alrim-logo"
              v-else-if="!arlim.store_profile_img"
              style="background-image:url(/images/img/basic_profile/01.png)"
            >
              {{ arlim.from_name }}
            </div>
            <div
              class="dg-hd-popup-logo dg-hd-popup_alrim-logo"
              v-else
              style="background-image:url(/images/img/profile_admin.png)"
            >
              {{ arlim.from_storename }}
            </div>
            <div class="dg-hd-popup-desc dg-hd-popup_alrim-desc">
              <!-- <div class="alrim-desc_sort">쿠폰</div> -->
              <div
                class="alrim-desc_from"
                v-if="arlim.from_storename"
              >
                {{ arlim.from_storename }}
              </div>
              <div
                class="alrim-desc_from"
                v-else-if="arlim.from_name"
              >
                {{ arlim.from_name }}
              </div>
              <div
                class="alrim-desc_from"
                v-else
              >
                동글 고객센터
              </div>
              <div class="alrim-desc_date">
                {{ $moment(arlim.not_datetime).format('YYYY-MM-DD') }}
              </div>
              <div class="alrim-desc_text">
                {{ arlim.not_message }}
              </div>
            </div>
          </div>
        </div>
        <div
          v-else
          class="no-arlim"
        >
          도착한 알림이 없습니다.
        </div>
        <div class="dg-hd-popup-allview dg-hd-popup_alrim-allview">
          <div @click="ToArlimePage">
            + 전체보기
          </div>
        </div>
      </div>
      <!-- top menu popup E -->
    </div>
  </div>
</template>

<script>
  export default {
    data: function () {
      return {
        arlims: []
      }
    },
    props: {
      headSmall: {
        type: Boolean,
        default: false
      }
    },
    async created () {
      await this.NotifyLoad()
      setInterval(() => this.NotifyLoad(), 1000 * 60 * 3)
    },
    methods: {
      async NotifyLoad () {
        if (this.$cookies.isKey('access_token')) {
          const params = {
            limit: 5
          }

          try {
            const res = (await this.$http.get(this.$APIURI + 'notification/list', { params })).data
            if (res.state === 1) {
              this.arlims = res.query.notification
            } else {
              console.log(res.msg)
            }
          } catch (e) {
            console.log(e)
          }
        }
      },
      async NotifyConfirm (id) {
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
      ToArlimePage () {
        this.popupStatus = false
        this.$router.push('/arlims')
      },
      SubToArlimePage () {
        if (this.headSmall) {
          this.popupStatus = false
          this.$router.push('/arlims')
        }
      },
      async ToNotUrl (id) {
        this.popupStatus = false
        const arlim = this.arlims.filter(arlim => arlim.not_id === id)[0]
        if (arlim.not_url !== null) {
          window.open(arlim.not_url)
        }
        await this.NotifyConfirm(id)
        await this.NotifyLoad()
      },
      PopupStatus (e) {
        if (this.popupStatus === true) {
          this.NotifyLoad()
          this.popupStatus = false
        } else {
          this.popupStatus = true
        }
      }
    }
  }
</script>

<style lang="scss" scoped>
  .dg-hd-popup .dg-hd-popup-desc {
    width: calc(100% - 50px);
  }

  .dg-hd-popup_alrim-allview {
    cursor: pointer;
  }

  .no-arlim {
    padding: 50px 0;
    text-align: center;
    border-bottom: 1px solid #ececec;
  }

  .top_menu_alrim .dg-hd-alrim_label:hover > div {
    display: block !important;
  }

  ._scroll .top_menu_alrim .dg-hd-alrim_label:hover > div {
    display: none !important;
  }
</style>
