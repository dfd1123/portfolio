<template>
  <div class="product-sell-info">
    <div class="product-store-name-wrap">
      <div class="symbol-circle">
        <img
          v-if="ConvertImage(item.company_profile_img).length > 0"
          :src="storageUrl + ConvertImage(item.company_profile_img)[0]"
          alt="판매자"
          class="_imgtag"
        >
        <img
          v-else
          src="/images/img/thumbnail.png"
          alt="판매자"
          class="_imgtag"
        >
      </div>
      <div class="product-store_name">
        {{ item.company_name }}
      </div>
      <router-link
        :to="'/store/'+item.store_id"
        class="rounded-btn-outline product-store_btn"
      >
        스토어 홈
      </router-link>
    </div>
    <div class="product-sell-desc">
      <h3>{{ item.title }}</h3>
      <LikeButton
        :item-no="item.item_id"
        :seller-id="item.seller_id"
        :like="item.zzim"
        :wish-cnt="wishCnt"
        @click-listen="LikeBtnClick"
      />

      <!-- 별점 영역 -->
      <div
        v-if="item.rating !== undefined"
        class="product-sell-star_score"
      >
        <div :class="'in-star-group star-0'+item.rating.toFixed(0)">
          <i class="_img"></i>
          <b>{{ +item.rating.toFixed(1) }}</b>
        </div>
        <router-link :to="'/review/list/'+item.item_id">
          {{ NumberFormat(reviewCount) }}개 후기 보기
        </router-link>
      </div>
      <!-- 별점 영역 E -->
    </div>

    <div v-html="item.simple_intro">
    </div>

    <!-- 가격+해시태그# -->
    <div class="dg-clothes-list-card dg-clothes-detail">
      <ul
        v-if="(item.soldout_yn || 0) === 1 || (item.sell_yn || 1) !== 1 || (item.delete_yn || 0) === 1"
        class="in-pricedetail"
      >
        <!-- 1 품절시 -->
        <li class="_soldout">
          품절상품입니다.
        </li>
        <!-- 1 품절시 E -->

        <!-- 2 판매중 -->
        <li class="_streetprice _sale">
          <span>시중가</span><span>{{ NumberFormat(item.cust_price || 0) }}원</span>
        </li>
        <li class="_finalprice _sale">
          <span class="_pricename">동글가</span><b>{{ NumberFormat(item.price || 0) }}</b><span>원</span>
        </li>
        <li class="_streetprice _sale _streetpriceblack _delivery display_none">
          <span>배송비</span><span>{{ NumberFormat(item.sc_price || 0) }}원</span>
        </li>
        <!-- 2 판매중 E -->

        <li class="_hashlist">
          <div class="_inner">
            <router-link
              v-for="(hashTag, index) in HashTag"
              :key="'hashTag' + index"
              :to="'/total/search?searchKeyword='+hashTag"
              class="rounded-list rounded-list-s"
            >
              <span>{{ hashTag }}</span>
            </router-link>
          </div>
        </li>
      </ul>
      <ul
        v-else
        class="in-pricedetail"
      >
        <!-- 2 판매중 -->
        <li class="_streetprice _sale">
          <span>시중가</span><span>{{ NumberFormat(item.cust_price || 0) }}원</span>
        </li>
        <li class="_finalprice _sale">
          <span class="_pricename">동글가</span><b>{{ NumberFormat(item.price || 0) }}</b><span>원</span>
        </li>
        <li class="_streetprice _sale _streetpriceblack _delivery display_none">
          <span>배송비</span><span>{{ NumberFormat(item.sc_price || 0) }}원</span>
        </li>
        <!-- 2 판매중 E -->

        <li class="_hashlist">
          <div class="_inner">
            <router-link
              v-for="(hashTag, index) in HashTag"
              :key="'hashTag' + index"
              :to="'/total/search?searchKeyword='+hashTag"
              class="rounded-list rounded-list-s"
            >
              <span>{{ hashTag }}</span>
            </router-link>
          </div>
        </li>
      </ul>
    </div>
    <!-- 가격+해시태그# E -->

    <!-- store info -->
    <div class="store-info">
      <ul class="_text">
        <li>
          <span class="_title">스토어/품번</span><span>{{ item.company_name }}/{{ item.item_id }}</span>
        </li>
        <li>
          <span class="_title">성별</span><span>{{ Gender(item.gender) }}</span>
        </li>
        <li>
          <span class="_title">제조국(원산지)</span><span>{{ item.orgin_range }}</span>
        </li>
        <li>
          <span class="_title">누적구매수</span><span>{{ item.ordering }}</span>
        </li>
      </ul>
      <div>
        <router-link
          :to="'/store/'+item.store_id+'/qna'"
          class="rounded-btn-outline product-store_btn left-icon-btn left-icon-btn_store"
        >
          스토어문의
        </router-link>
        <div class="left-icon-btn_share-wrap">
          <button class="rounded-btn-outline product-store_btn left-icon-btn left-icon-btn_share">
            공유하기
          </button>
          <!-- sns share 영역 추가 -->
          <div class="sns-share-wrapper">
            <ul class="sns-icon-btn-wrap clear_both">
              <li
                v-for="(sns, index) in snsLists"
                :key="'sns'+index"
                @click="sendSns(sns.name, url, txt)"
                class="sns-icon-btn"
              >
                <div class="_img_box">
                  <img
                    :src="sns.image"
                    :alt="sns.name"
                  >
                </div>
              </li>
            </ul>
          </div>
        </div>
      </div>
    </div>
    <!-- store info E -->

    <!-- 품절상태에서 보이지 않기 -->
    <!-- 옵션선택 -->
    <div class="choice_product">
      <h4>옵션선택</h4>
      <OptionSelect
        v-for="(optionNm, index) in optionsName"
        :key="'optionNm'+ index"
        :option-nm="optionNm"
        :option-subject="optionSubject"
        :index="index"
        @option-change="OptionChange"
      />
      <SelectOptionList
        v-for="(option, index) in $store.state.selectOption"
        :key="'selectOption'+index"
        :option="option"
        :option-subject="optionSubject"
        :item-list="itemList"
        :index="Number(index)"
        :price="option.qty * (option.price + itemList.price)"
        :qty="option.qty"
        @input="ChangeQty(index, $event)"
        @delete-option="DeleteOption(index)"
        @total-price="TotalPrice"
      />
    </div>
    <!-- 옵션선택 E -->
    <div class="dg-reg-end_btn_wrap clear_both">
      <button
        type="button"
        class="dg-btn_line dg-dubble_btn"
        @click="$emit('submit', 'cart')"
      >
        장바구니 담기
      </button>
      <button
        v-if="item.soldout_yn === 0"
        type="button"
        class="dg-btn_gra dg-dubble_btn"
        @click="$emit('submit', 'direct')"
      >
        구매하기
      </button>
    </div>
    <!-- 품절상태에서 보이지 않기 E -->
  </div>
</template>

<script>
  import LikeButton from '@/components/product/like-button.vue'
  import OptionSelect from '@/components/product/option-select.vue'
  import SelectOptionList from '@/components/product/option-select-list.vue'

  export default {
    components: {
      LikeButton,
      OptionSelect,
      SelectOptionList
    },
    data: function () {
      return {
        itemList: this.item,
        wishCnt: this.item.wishes || 0,
        selectOption: this.$store.state.selectOption,
        selectArr: [],
        totalPrice: this.$store.state.totalPrice,
        url: window.location.href,
        snsLists: [
          {
            name: 'facebook',
            image: '/images/icon/icon_share_facebook.svg'
          },
          {
            name: 'blog',
            image: '/images/icon/icon_share_blog.svg'
          },
          {
            name: 'twitter',
            image: '/images/icon/icon_share_twitter.svg'
          }
        ]
      }
    },
    props: {
      item: {
        type: Object,
        required: true
      },
      options: {
        type: Array,
        required: true
      },
      optionSubject: {
        type: Array,
        required: true
      },
      optionsName: {
        type: Array,
        required: true
      },
      reviewCount: {
        type: Number,
        required: true
      }
    },
    computed: {
      HashTag () {
        if (this.item.hash_tag) {
          return this.item.hash_tag.split(',')
        } else {
          return []
        }
      }
    },
    updated () {
      this.itemList = this.item
      this.wishCnt = this.item.wishes || 0
    },
    methods: {
      FirstOptionSet () {
        const selectTags = document.getElementsByClassName('select-option')
        const optionsName = []

        for (let i = 0; i < selectTags.length; i++) {
          selectTags[i].selectedIndex = 0
        }

        for (let i = 0; i < this.optionSubject.length; i++) {
          this.$set(this.optionsName, i, [])
        }

        this.options.forEach(option => {
          const optionArr = option.name.split(',')
          if (optionsName.indexOf(optionArr[0]) === -1) {
            optionsName.push(optionArr[0])
          }
        })

        this.$set(this.optionsName, 0, optionsName)
      },
      OptionChange (index, value) {
        this.selectArr.splice(index, 1, value)
        let optionArr = []
        let optionsName = []

        if (index !== 0) {
          const temp = this.selectArr.join(',')

          this.options.forEach(option => {
            if (option.name.indexOf(temp) !== -1) {
              optionArr.push(option)
            }
          })
        } else {
          this.options.forEach(option => {
            if (option.name.indexOf(this.selectArr + ',') !== -1) {
              let tArr = option.name.split(',')
              tArr.forEach(arr => {
                arr = arr.replace(/\s/g, '')
                if (arr === String(this.selectArr)) {
                  optionArr.push(option)
                }
              })
            }
          })
        }

        if (this.optionSubject.length !== index + 1) {
          if (this.optionSubject.length !== index + 2) {
            optionArr.forEach(option => {
              const tempArr = option.name.split(',')
              if (this.optionsName[index + 1].indexOf(tempArr[index + 1]) === -1) {
                optionsName.push(tempArr[index + 1])
              }
            })
            this.$set(this.optionsName, index + 1, optionsName)
          } else {
            this.optionsName[index + 1] = []
            optionArr.forEach(option => {
              const tempArr = option.name.split(',')
              if (this.optionsName[index + 1].indexOf(tempArr[index + 1]) === -1) {
                optionsName.push(tempArr[index + 1] + '( +' + option.price + '원 )')
              }
            })
            this.$set(this.optionsName, index + 1, optionsName)
          }
        } else {
          const otName = this.selectArr.join(',')
          this.options.forEach(option => {
            if (option.name + '( +' + option.price + '원 )' === otName) {
              if (this.selectOption.indexOf(option) === -1) {
                option.stock_qty--
                option.qty = 1
                // this.selectOption = []
                this.selectOption.push(option)
              }
            }
          })

          this.FirstOptionSet()

          this.$set(this, 'selectArr', [])

          this.$store.commit('SelectOptionStore', this.selectOption)

          this.TotalPrice()
        }
      },
      ChangeQty (index, value) {
        this.selectOption[index] = value
        this.$store.commit('SelectOptionStore', { ...this.selectOption })

        this.TotalPrice()
      },
      DeleteOption (index) {
        this.selectOption.splice(index, 1)
        this.$store.commit('SelectOptionStore', this.selectOption)

        this.TotalPrice()
      },
      TotalPrice () {
        let price = 0
        if (this.selectOption.length > 0) {
          this.selectOption.forEach(option => {
            price = price + option.qty * (this.item.price + option.price)
          })
        }
        this.totalPrice = Number(price)

        this.$store.commit('TotalPriceChange', this.totalPrice)
      },
      LikeBtnClick (status) {
        if (status) {
          this.wishCnt = this.wishCnt + 1
          console.log(this.wishCnt)
        } else {
          this.wishCnt = this.wishCnt - 1
        }
      },
      sendSns (sns, url, txt) {
        txt = '[동글]' + this.item.title
        var o
        var _url = encodeURIComponent(url)
        var _txt = encodeURIComponent(txt)
        var _br = encodeURIComponent('\r\n')

        this.$emit('sns-popup-close')

        switch (sns) {
          case 'facebook':
            o = {
              method: 'popup',
              url: 'http://www.facebook.com/sharer/sharer.php?u=' + _url
            }
            break

          case 'twitter':
            o = {
              method: 'popup',
              url: 'http://twitter.com/intent/tweet?text=' + _txt + '&url=' + _url
            }
            break

          case 'me2day':
            o = {
              method: 'popup',
              url: 'http://me2day.net/posts/new?new_post[body]=' + _txt + _br + _url + '&new_post[tags]=epiloum'
            }
            break

          case 'blog':
            o = {
              method: 'popup',
              url: 'http://blog.naver.com/openapi/share?url=' + _url + '&title=' + _txt
            }
            break

          case 'kakaotalk':
            o = {
              method: 'web2app',
              param: 'sendurl?msg=' + _txt + '&url=' + _url + '&type=link&apiver=2.0.1&appver=2.0&appid=dev.epiloum.net&appname=' + encodeURIComponent('Epiloum 개발노트'),
              a_store: 'itms-apps://itunes.apple.com/app/id362057947?mt=8',
              g_store: 'market://details?id=com.kakao.talk',
              a_proto: 'kakaolink://',
              g_proto: 'scheme=kakaolink;package=com.kakao.talk'
            }
            break

          case 'kakaostory':
            o = {
              method: 'web2app',
              param: 'posting?post=' + _txt + _br + _url + '&apiver=1.0&appver=2.0&appid=dev.epiloum.net&appname=' + encodeURIComponent('Epiloum 개발노트'),
              a_store: 'itms-apps://itunes.apple.com/app/id486244601?mt=8',
              g_store: 'market://details?id=com.kakao.story',
              a_proto: 'storylink://',
              g_proto: 'scheme=kakaolink;package=com.kakao.story'
            }
            break

          case 'band':
            o = {
              method: 'web2app',
              param: 'create/post?text=' + _txt + _br + _url,
              a_store: 'itms-apps://itunes.apple.com/app/id542613198?mt=8',
              g_store: 'market://details?id=com.nhn.android.band',
              a_proto: 'bandapp://',
              g_proto: 'scheme=bandapp;package=com.nhn.android.band'
            }
            break

          default:
            this.WarningAlert('지원하지 않는 SNS입니다.')
            return false
        }

        switch (o.method) {
          case 'popup':
            window.open(o.url)
            break

          case 'web2app':
            if (navigator.userAgent.match(/android/i)) {
              // Android
              setTimeout(function () { location.href = 'intent://' + o.param + '#Intent;' + o.g_proto + ';end' }, 100)
            } else if (navigator.userAgent.match(/(iphone)|(ipod)|(ipad)/i)) {
              // Apple
              setTimeout(function () { location.href = o.a_store }, 200)
              setTimeout(function () { location.href = o.a_proto + o.param }, 100)
            } else {
              this.WarningAlert('이 기능은 모바일에서만 사용할 수 있습니다.')
            }
            break
        }
      }
    }
  }
</script>

<style lang="scss" scoped>
</style>
