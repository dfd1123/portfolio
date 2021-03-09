<!-- eslint-disable vue/max-attributes-per-line -->
<!-- eslint-disable vue/html-self-closing -->
<template>
  <div class="sns-share-wrapper">
    <div class="sns-share-bg"></div>
    <ul class="sns-icon-btn-wrap clear_both">
      <li
        v-for="(sns, index) in snsLists"
        :key="'sns' + index"
        class="sns-icon-btn"
      >
        <template v-if="sns.name !== 'kakaotalk'">
          <div
            class="_img_box"
            @click="sendSns(sns.name, url, txt)"
          >
            <img :src="sns.image" :alt="sns.name" />
          </div>
          <span class="sns-name" @click="sendSns(sns.name, url, txt)">{{ sns.name }}</span>
        </template>
        <template v-else-if="sns.name === 'kakaotalk'">
          <div
            :id="'kakao-link-btn' + '-' + _uid"
            class="_img_box"
          >
            <img :src="sns.image" :alt="sns.name" />
          </div>
          <span class="sns-name" @click="document.getElementById('kakao-link-btn' + '-' + _uid).click()">{{ sns.name }}</span>
        </template>
      </li>
    </ul>
    <button class="_btn_close" v-ripple @click="$emit('sns-popup-close')">
      닫기
    </button>
  </div>
</template>

<script>
/* eslint-disable */
export default {
  data: function() {
    return {
      url: window.location.href,
      snsLists: [
        {
          name: "facebook",
          image: "/images/icon/icon_share_facebook.svg"
        },
        {
          name: "kakaotalk",
          image: "/images/icon/icon_share_kakao.svg"
        },
        {
          name: "kakaostory",
          image: "/images/icon/icon_share_kakaostory.svg"
        },
        {
          name: "band",
          image: "/images/icon/icon_share_band.svg"
        },
        {
          name: "blog",
          image: "/images/icon/icon_share_blog.svg"
        },
        {
          name: "twitter",
          image: "/images/icon/icon_share_twitter.svg"
        }
      ],
      kakaoCdn: null
    };
  },
  props: {
    txt: {
      type: String,
      required: true
    },
    item: {
      type: Object,
      default: null
    }
  },
  created () {
    this.document = window.document
  },
  watch: {
    item() {
      if(!this.item) {
        return
      }

      const images = JSON.parse(this.item.images)
      const image = images.length > 0 ? images[0] : ""

      this.$nextTick(() => {
        window.Kakao.Link.createDefaultButton({
          container: "#kakao-link-btn" + '-' + this._uid,
          objectType: "commerce",
          content: {
            title: this.txt,
            description: this.item.simple_intro,
            imageUrl: this.storageUrl + image,
            link: {
              mobileWebUrl: window.location.href
            }
          },
          commerce: {
            regularPrice: this.item.price
          },
          buttons: [
            {
              title: "웹으로 이동",
              link: {
                mobileWebUrl: window.location.href
              }
            }
          ],
          success: function(response) {
            console.log(response);
          },
          fail: function(error) {
            console.log(error);
          },
          installTalk: true
        });
      })
    }
  },
  methods: {
    sendSns(sns, url, txt) {
      var o;
      var _url = encodeURIComponent(url);
      var _txt = encodeURIComponent(txt);
      var _br = encodeURIComponent("\r\n");

      this.$emit("sns-popup-close");

      switch (sns) {
        case "facebook":
          o = {
            method: "popup",
            url: "http://www.facebook.com/sharer/sharer.php?u=" + _url
          };
          break;

        case "twitter":
          o = {
            method: "popup",
            url: "http://twitter.com/intent/tweet?text=" + _txt + "&url=" + _url
          };
          break;

        case "me2day":
          o = {
            method: "popup",
            url:
              "http://me2day.net/posts/new?new_post[body]=" +
              _txt +
              _br +
              _url +
              "&new_post[tags]=epiloum"
          };
          break;

        case "blog":
          o = {
            method: "popup",
            url:
              "http://blog.naver.com/openapi/share?url=" +
              _url +
              "&title=" +
              _txt
          };
          break;

        case "kakaotalk":
          break;

        case "kakaostory":
          o = {
            method: "web2app",
            param:
              "posting?post=" +
              _txt +
              _br +
              _url +
              "&apiver=1.0&appver=2.0&appid=dev.epiloum.net&appname=" +
              encodeURIComponent("Epiloum 개발노트"),
            a_store: "itms-apps://itunes.apple.com/app/id486244601?mt=8",
            g_store: "market://details?id=com.kakao.story",
            a_proto: "storylink://",
            g_proto: "scheme=kakaolink;package=com.kakao.story"
          };
          break;

        case "band":
          o = {
            method: "web2app",
            param: "create/post?text=" + _txt + _br + _url,
            a_store: "itms-apps://itunes.apple.com/app/id542613198?mt=8",
            g_store: "market://details?id=com.nhn.android.band",
            a_proto: "bandapp://",
            g_proto: "scheme=bandapp;package=com.nhn.android.band"
          };
          break;

        default:
          this.WarningAlert("지원하지 않는 SNS입니다.");
          return false;
      }

      switch (o.method) {
        case "popup":
          this.NativePopup(o.url);
          break;

        case "web2app":
          if (navigator.userAgent.match(/android/i)) {
            // Android
            setTimeout(function() {
              location.href =
                "intent://" + o.param + "#Intent;" + o.g_proto + ";end";
            }, 100);
          } else if (navigator.userAgent.match(/(iphone)|(ipod)|(ipad)/i)) {
            // Apple
            setTimeout(function() {
              location.href = o.a_store;
            }, 200);
            setTimeout(function() {
              location.href = o.a_proto + o.param;
            }, 100);
          } else {
            this.WarningAlert("이 기능은 모바일에서만 사용할 수 있습니다.");
          }
          break;
      }
    }
  }
};
</script>

<style lang="scss" scoped>
</style>
