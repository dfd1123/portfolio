<template>
  <div class="main-category-card-area">
    <i class="in-shadow-element"></i>
    <div class="main-category-card">
      <div class="card-thumbnail-wrap">
        <figure
          class="card-thumbnail"
          @click="ClickThumb"
        >
          <img
            :src="thumbImg.length > 0 ? '/'+thumbImg[0] : defaultImg"
            :alt="item.title + ' 이미지'"
            class="in-img"
          />
          <button
            type="button"
            class="hover-overview"
            :class="{ active : moreView && this.selectOption.title }"
          >
            <img
              src="assets/images/icon/icon_overview.svg"
              alt="미리보기 아이콘"
              class="in-icon-img"
            />
          </button>
        </figure>
      </div>
      <div class="card-infomation">
        <div class="card-default-info">
          <h3 class="card-title">{{ item.title }}</h3>
          <p
            class="card-desc"
            :class="{ active : this.active }"
          >{{ item.simple_intro }}</p>
        </div>
        <div class="card-price-info">
          <span
            class="cat-mini-word"
            v-if="item.title === '종합 광고 패키지'"
          >월</span>
          <span
            v-else
            class="card-sale-price"
          >{{ selectOption.option?NumberFormat(item.origin_price + selectOption.option.op_origin_price):NumberFormat(item.origin_price) }}</span>
          <strong class="card-real-price">{{ selectOption.option?NumberFormat(item.sale_price + selectOption.option.op_sale_price):NumberFormat(item.sale_price) }}</strong>
        </div>
        <input
          :id="'card_type0' + item.item_id"
          type="checkbox"
          v-model="selectShow"
          class="type-list-show none"
        />
        <div class="card-select-area">
          <label
            :for="'card_type0' + item.item_id"
            class="in-selected-type"
            @click="mobileOptionToggle"
          >
            <span
              v-if="selectOption.option"
              class="seleted-type"
            >
              <i>
                {{selectOption.option.op_type}}
                <i v-if="!IsPremium">{{'('+selectOption.option.op_concept+')'}}</i>
              </i>
              : {{selectOption.option.op_simple_intro}}
            </span>
            <span v-else>Type을 선택해주세요</span>
          </label>

          <ul class="in-select-list-group">
            <li
              v-for="option in item.options"
              :key="'option'+option.op_id"
              class="in-select-list"
            >
              <label class="select-type">
                <span class="type-upper-area">
                  <b class="type-title">
                    {{option.op_type}}
                    <span v-if="option.op_type === 'Premium' || option.title === '종합 광고 패키지' ? false : true">({{option.op_concept}})</span>
                  </b>
                  <small class="type-price">
                    <i v-if="option.op_type === 'Premium' ? false : true">+{{NumberFormat(option.op_sale_price || 0)}}원</i>
                    <i v-else>상담문의</i>
                  </small>
                </span>
                <span class="type-lower-area">
                  <i
                    v-for="(hash, index) in option.op_simple_intro.split(',')"
                    :key="'hash'+index"
                    class="type-hash"
                  >{{hash}}</i>
                </span>
                <input
                  type="checkbox"
                  @change="ChangeOption(option)"
                  class="none"
                />
              </label>
            </li>
          </ul>
        </div>
        <div class="card-button-area">
          <input
            type="button"
            class="rounded-button btn-bluegradi"
            value="장바구니 담기"
            @click="AddItem"
          />
        </div>
      </div>
    </div>

    <!-- 모바일 버전 옵션선택팝업 -->
    <div
      class="select-option-popup"
      v-show="mobileOptionShow"
    >
      <h2 class="option-popup-title">
        <button
          class="_back"
          type="button"
          @click="mobileOptionToggle"
        >뒤로가기</button>
        옵션 선택
      </h2>
      <ul class="select-list-wrap">
        <!-- .selec-list에 .active 되면 배경색이 변함 -->
        <li
          class="selec-list"
          v-for="option in item.options"
          :key="'option'+option.op_id"
        >
          <label class="select-type">
            <ul>
              <li class="_title">
                {{option.op_type}}
                <span v-if="option.op_type === 'Premium' ? false : true">({{option.op_concept}})</span>
              </li>
              <li class="_desc">{{option.op_simple_intro}}</li>
              <li class="_price">
                <b v-if="option.op_type === 'Premium' ? false : true">+{{NumberFormat(option.op_sale_price || 0)}}원</b>
                <i v-else>상담문의</i>
              </li>
            </ul>
            <input
              type="checkbox"
              @change="ChangeOption(option)"
              class="none"
            />
          </label>
        </li>
      </ul>
    </div>
    <!-- END 모바일 버전 옵션선택팝업 -->
  </div>
</template>

<script>
  export default {
    name: "main-category-card",
    data() {
      return {
        selectShow: false,
        selectOption: {},
        thumbImg: this.item.images
          ? this.ConvertImage(this.item.images || [])
          : [],
        mobileOptionShow: false,
        wordArray: ["구매 시 두루톡", "이번달 할인 이벤트"],
        active: false,
        moreView: null,
        defaultImg: "assets/images/default/default_Thumb.png"
      };
    },
    props: {
      item: {
        type: Object,
        default: {}
      },
      popup: {
        type: Boolean,
        default: false
      }
    },
    computed: {
      IsPremium() {
        return Boolean(this.selectOption.option.op_type === "Premium");
      }
    },
    created() {
      this.ActiveWord(this.item.simple_intro);
    },
    methods: {
      SelectHide() {
        this.selectShow = false;
      },
      ChangeOption(option) {
        const taxMny = this.item.tax_mny + option.op_tax_mny;
        const vatMny = this.item.vat_mny + option.op_vat_mny;
        const totalPrice = this.item.sale_price + option.op_sale_price;

        const params = {
          title: this.item.title,
          simple_intro: this.item.simple_intro,
          intro: this.item.intro,
          images: this.item.images,
          video_url: this.item.video_url,
          tax_mny: taxMny,
          vat_mny: vatMny,
          total_price: totalPrice,
          option: option
        };

        this.thumbImg = option.op_images
          ? this.ConvertImage(option.op_images || [])
          : [];

        this.selectOption = params;

        this.mobileOptionToggle();
        // 모바일이면 닫히고, 아니면 false

        this.moreView = false;
        setTimeout(() => {
          this.moreView = true;
        }, 200);
        // 타입선택할때 더보기 아이콘이 보이는 인터랙션
      },
      AddItem() {
        if (this.selectOption.title) {
          this.$store.commit("CartAdd", this.selectOption);

          if (
            this.selectOption &&
            !this.$store.state.carts.includes(this.selectOption)
          ) {
            let duple = false;
            this.$store.state.carts.forEach(cart => {
              if (cart.option.op_id === this.selectOption.option.op_id) {
                duple = true;
              }
            });
          } else {
            this.IsMobile() ? this.$emit("update:popup", true) : "";
          }

          this.selectOption = {};
          this.thumbImg = this.item.images
            ? this.ConvertImage(this.item.images || [])
            : [];
        } else {
          this.WarningAlert("Type을 선택해주세요.");
        }
      },
      ClickThumb() {
        if (this.selectOption.title) {
          this.$emit("OpenPopup", this.selectOption);
        } else {
          const params = {
            title: this.item.title,
            simple_intro: this.item.simple_intro,
            intro: this.item.intro,
            m_intro: this.item.m_intro,
            images: this.item.images,
            video_url: this.item.video_url
          };

          this.$emit("OpenPopup", params);
        }
      },
      OpenPopup() {
        console.log("팝업");
      },
      mobileOptionToggle() {
        this.IsMobile()
          ? (this.mobileOptionShow = !this.mobileOptionShow)
          : (this.mobileOptionShow = false);
      },
      ActiveWord(item) {
        this.wordArray.forEach(word => {
          const idx = item.indexOf(word);

          if (idx > -1) {
            this.active = true;
          }
        });
      }
    }
  };
</script>

<style scoped>
  .cat-mini-word {
    color: black;
    vertical-align: middle;
    display: inline-block;
    font-weight: 500;
    position: relative;
    top: 0px;
    margin-right: 3px;
    font-size: 0.85em;
  }

  .main-category-card .card-thumbnail {
    border: 1px solid #eff4fd;
    position: relative;
  }

  .hover-overview {
    cursor: pointer;
    width: 36px;
    height: 36px;
    border-radius: 50%;
    background-color: white;
    padding: 0;
    border: 0;
    outline: none;
    display: flex;
    position: absolute;
    bottom: 12px;
    right: 12px;
    align-items: center;
    justify-content: center;
    opacity: 0;
    box-shadow: 0 6px 6px rgba(0, 0, 0, 0.16);
    transform: scale(0);
    transition: 0.3s;
  }

  .hover-overview.active {
    transform: scale(1);
    opacity: 1;
    transition: 1s;
    transition-timing-function: cubic-bezier(0.51, 0.92, 0.24, 1.15);
  }

  .hover-overview .in-icon-img {
    position: relative;
    top: 1px;
    left: 1px;
  }
</style>