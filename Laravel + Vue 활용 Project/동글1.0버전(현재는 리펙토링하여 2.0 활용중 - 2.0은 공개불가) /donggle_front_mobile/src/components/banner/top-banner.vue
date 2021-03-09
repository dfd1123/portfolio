<template>
  <!-- main top popup -->
  <div
    class="dg-main_popup"
    v-if="mainPopup && banners.length > 0"
  >
    <a
      v-if="mainBanner.bn_new_win !== 0"
      :href="banners[0].bn_url"
    >
      <div
        class="dg-main_popup_img"
        :style="'background-image:url('+storageUrl+ConvertImage(banners[0].bn_img_mobile)[0]+');background-size: auto 100%;'"
      >
      </div>
      <button
        type="button"
        class="dg-main_popup-close"
        @click="CloseMainTopBannerPop"
      >
        팝업닫기
      </button>
      <div class="dg-main_popup_all"></div>
    </a>
    <a
      v-else
      href="#"
      @click.prevent="NativePopup(banners[0].bn_url)"
    >
      <div
        class="dg-main_popup_img"
        :style="'background-image:url('+storageUrl+ConvertImage(banners[0].bn_img_mobile)[0]+');background-size: auto 100%;'"
      >
      </div>
      <button
        type="button"
        class="dg-main_popup-close"
        @click="CloseMainTopBannerPop"
      >
        팝업닫기
      </button>
      <div class="dg-main_popup_all"></div>
    </a>
  </div>
  <!-- main top popup E -->
</template>

<script>
  export default {
    data: function () {
      return {
        banners: [],
        mainPopup: false
      }
    },
    created () {
      if (!this.CheckPopupCookie('mainTopBannerClose')) {
        this.FetchData()
        this.mainPopup = true
      }
    },
    methods: {
      async FetchData () {
        try {
          const res = (await this.$http.get(this.$APIURI + 'banner/top')).data

          if (res.state === 1) {
            let arr = []
            res.query.forEach(el => {
              if (this.$moment().isBetween(el.bn_begin_time, el.bn_end_time)) {
                if (el.bn_img) {
                  arr.push(el)
                }
              }
            })
            this.banners = arr
          } else {
            console.log(res.msg)
          }
        } catch (e) {
          console.log(e)
        }
      },
      CheckPopupCookie (cookieName) {
        const cookie = document.cookie

        if (cookie.length > 0) { // 현재 쿠키가 존재할 경우
          // 자식창에서 set해준 쿠키명이 존재하는지 검색

          const startIndex = cookie.indexOf(cookieName)

          if (startIndex !== -1) { // 존재 한다면
            return true
          } else {
            // 쿠키 내에 해당 쿠키가 존재하지 않을 경우
            return false
          }
        } else {
          // 쿠키 자체가 없을 경우
          return false
        }
      },
      SetCookie (name, value, expiredays) {
        let d = new Date()

        d.setDate(d.getDate() + expiredays)

        document.cookie = name + '=' + escape(value) + '; path=/; expires=' + d.toGMTString() + ';'
      },
      CloseMainTopBannerPop () {
        // 하루 동안 mainTopBannerClose 라는 쿠키 유지

        this.SetCookie('mainTopBannerClose', 'mainTopBannerClose', 1)

        this.mainPopup = false
      }
    }
  }
</script>

<style lang="scss" scoped>
  .dg-main_popup-close {
    padding: 0;

    &:after,
    &:before {
      top: 12px;
      padding: 0px;
    }
  }
</style>
