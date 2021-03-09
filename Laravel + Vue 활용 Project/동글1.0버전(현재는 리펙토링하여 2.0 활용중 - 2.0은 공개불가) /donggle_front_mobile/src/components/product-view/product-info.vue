<template>
  <div class="product-sell-info">
    <div class="product-store-name-wrap clear_both">
      <div
        class="symbol-circle"
        @click="$router.push('/store/'+item.store_id)"
      >
        <img
          v-if="item.company_profile_img === undefined"
          src="/images/img/thumbnail.png"
          alt="판매자"
          class="_imgtag"
        >
        <img
          v-else-if="ConvertImage(item.company_profile_img).length > 0"
          :src="storageUrl+ConvertImage(item.company_profile_img)[0]"
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
      <div
        class="product-store_name"
        @click="$router.push('/store/'+item.store_id)"
      >
        {{ item.company_name }}
        <router-link
          :to="'/store/'+item.store_id"
          class="_prevpage_btn"
        >
          스토어 홈
        </router-link>
      </div>
    </div>
    <div class="product-sell-desc">
      <h3>{{ item.title }}</h3>

      <!-- 별점 영역 -->
      <div class="product-sell-star_score">
        <div class="product-sell-star_wrap">
          <!-- ※ 별 점수에 따라 star-05, star-04, star-03, star-02, star-01 클래스 추가 -->
          <div :class="'in-star-group star-0'+(item.rating || 0).toFixed(0)">
            <i class="_img"></i>
            <span class="_rate"></span>
          </div>
          <!-- ※ 별 점수에 따라 star-05, star-04, star-03, star-02, star-01 클래스 추가 END -->
          <router-link
            :to="'/review/list/'+item.item_id"
            class="_more_review_view"
          >
            {{ NumberFormat(reviewCount) }}개 후기 보기
          </router-link>
        </div>
        <!-- heart -->
        <figure class="dg-clothes-thumbnail dg-clothes-thumbup">
          <input
            type="checkbox"
            class="heart-badge"
            id="zzim"
            v-model="zzim"
            @change="LikeBtnClick(item.zzim)"
          >
          <label
            class="heart-badge-label"
            for="zzim"
          >
            <svg viewBox="467 392 58 57">
              <defs>
                <linearGradient
                  id="heart-shape-gradient"
                  x2="0.35"
                  y2="1"
                >
                  <stop
                    offset="0%"
                    stop-color="var(--color-stop)"
                  />
                  <stop
                    offset="100%"
                    stop-color="var(--color-bot)"
                  />
                </linearGradient>
              </defs>

              <g
                class="heart-Group"
                fill="none"
                fill-rule="evenodd"
                transform="translate(467 392)"
              >
                <path
                  d="M29.144 20.773c-.063-.13-4.227-8.67-11.44-2.59C7.63 28.795 28.94 43.256 29.143 43.394c.204-.138 21.513-14.6 11.44-25.213-7.214-6.08-11.377 2.46-11.44 2.59z"
                  class="heart-path"
                  fill="#AAB8C2"
                />
                <circle
                  class="main-circ"
                  opacity="0"
                  cx="29.5"
                  cy="29.5"
                  r="1.5"
                />

                <g
                  class="grp7"
                  opacity="0"
                  transform="translate(7 6)"
                >
                  <circle
                    class="oval2"
                    fill="#a134e8"
                    cx="5"
                    cy="6"
                    r="2"
                  />
                  <circle
                    class="oval1"
                    fill="#fc4ead"
                    cx="2"
                    cy="2"
                    r="2"
                  />
                </g>

                <g
                  class="grp6"
                  opacity="0"
                  transform="translate(0 28)"
                >
                  <circle
                    class="oval1"
                    fill="#fc4ead"
                    cx="2"
                    cy="7"
                    r="2"
                  />
                  <circle
                    class="oval2"
                    fill="#ff62b9"
                    cx="3"
                    cy="2"
                    r="2"
                  />
                </g>

                <g
                  class="grp3"
                  opacity="0"
                  transform="translate(52 28)"
                >
                  <circle
                    class="oval2"
                    fill="#a134e8"
                    cx="2"
                    cy="7"
                    r="2"
                  />
                  <circle
                    class="oval1"
                    fill="#a134e8"
                    cx="4"
                    cy="2"
                    r="2"
                  />
                </g>

                <g
                  class="grp2"
                  opacity="0"
                  transform="translate(44 6)"
                >
                  <circle
                    class="oval2"
                    fill="#a134e8"
                    cx="5"
                    cy="6"
                    r="2"
                  />
                  <circle
                    class="oval1"
                    fill="#fc4ead"
                    cx="2"
                    cy="2"
                    r="2"
                  />
                </g>

                <g
                  class="grp5"
                  opacity="0"
                  transform="translate(14 50)"
                >
                  <circle
                    class="oval1"
                    fill="#fc4ead"
                    cx="6"
                    cy="5"
                    r="2"
                  />
                  <circle
                    class="oval2"
                    fill="#ff62b9"
                    cx="2"
                    cy="2"
                    r="2"
                  />
                </g>

                <g
                  class="grp4"
                  opacity="0"
                  transform="translate(35 50)"
                >
                  <circle
                    class="oval1"
                    fill="#fc4ead"
                    cx="2"
                    cy="7"
                    r="2"
                  />
                  <circle
                    class="oval2"
                    fill="#fc4ead"
                    cx="3"
                    cy="2"
                    r="2"
                  />
                </g>

                <g
                  class="grp1"
                  opacity="0"
                  transform="translate(24)"
                >
                  <circle
                    class="oval1"
                    fill="#a134e8"
                    cx="2.5"
                    cy="3"
                    r="2"
                  />
                  <circle
                    class="oval2"
                    fill="#a134e8"
                    cx="7.5"
                    cy="2"
                    r="2"
                  />
                </g>
              </g>
            </svg>
          </label>
        </figure>
        <!-- heart E -->
        <!-- <span class="dg-like_number">찜 10</span> -->
      </div>
      <!-- 별점 영역 E -->
      <div
        v-html="item.simple_intro"
        style="padding: 0 13px;"
      ></div>
    </div>

    <!-- 가격 -->
    <div class="dg-clothes-list-card dg-clothes-detail">
      <ul class="in-pricedetail">
        <!-- 1 품절시 -->
        <li
          v-if="(item.soldout_yn || 0) === 1 || (item.sell_yn || 1) !== 1 || (item.delete_yn || 0) === 1"
          class="_soldout"
        >
          품절상품입니다.
        </li>
        <!-- 1 품절시 E -->

        <!-- 2 판매중 -->
        <li
          v-if="item.soldout_yn === 0"
          class="_streetprice _sale"
        >
          <span>시중가</span><span>{{ NumberFormat(item.cust_price || 0) }}원</span>
        </li>
        <li
          v-if="item.soldout_yn === 0"
          class="_finalprice _sale"
        >
          <span class="_pricename">동글가</span><b>{{ NumberFormat(item.price || 0) }}</b><span>원</span>
        </li>
        <li
          v-if="item.soldout_yn === 0"
          class="_streetprice _sale _streetpriceblack _delivery display_none"
        >
          <span class="_pricename">배송비</span><span>{{ NumberFormat(item.sc_price || 0) }}원</span>
        </li>
        <!-- 2 판매중 E -->
      </ul>
    </div>
    <!-- 가격 E -->

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
      <div class="_btn_wrap clear_both">
        <router-link
          :to="'/store/'+item.store_id+'/qna'"
          class="rounded-btn-outline product-store_btn left-icon-btn left-icon-btn_store"
          v-ripple
        >
          스토어문의
        </router-link>
        <button
          class="rounded-btn-outline product-store_btn left-icon-btn left-icon-btn_share"
          v-ripple
          @click="$emit('sns-popup-open')"
        >
          공유하기
        </button>
      </div>
    </div>
    <!-- store info E -->
  </div>
</template>

<script>

  export default {
    components: {

    },
    data: function () {
      return {
        itemList: this.item,
        wishCnt: this.item.wishes || 0,
        selectOption: this.$store.state.selectOption,
        selectArr: [],
        totalPrice: this.$store.state.totalPrice,
        zzim: this.item.zzim === 1
      }
    },
    watch: {
      item () {
        this.itemList = this.item
        this.zzim = (this.item.zzim === 1)
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
              optionArr.push(option)
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
      async LikeBtnClick () {
        const like = new Audio('/sound/decision22.mp3')
        const unlike = new Audio('/sound/cancel4.mp3')

        if (this.zzim) {
          like.currentTime = 0
          like.play()
        } else {
          unlike.currentTime = 0
          unlike.play()
        }

        if (this.$cookies.isKey('access_token')) {
          const params = {
            item_id: this.item.item_id
          }

          try {
            const res = (await this.$http.post(this.$APIURI + 'wish', params)).data

            if (res.state === 1) {
              if (this.zzim) {
                const notifyParams = {
                  target_mem_id: this.sellerId,
                  not_type: 'wish',
                  not_content_id: res.query,
                  not_message: this.$store.state.user.nickname + '님 께서 판매자님의 상품을 찜하셨습니다.',
                  not_url: window.location.protocol + '//' + window.location.host + '/product/view/' + res.query
                }

                this.NotifyStore(notifyParams)
              }
            }
          } catch (e) {
            console.log(e)
          }
        }
      }
    }
  }
</script>

<style lang="scss" scoped>
</style>
