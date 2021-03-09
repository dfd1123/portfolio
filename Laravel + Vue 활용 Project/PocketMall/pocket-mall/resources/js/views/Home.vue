<template>
  <div class="container main-container">
    <div class="main-wrapper">
      <section class="main-hd-section">
        <h2 class="hide-text">카테고리 안내 섹션</h2>
        <span class="main-hd-section-bg"></span>
        <div class="main-deco-element main-deco-element-left">
          <span class="in-deco-ele in-deco-ele-first"></span>
          <span class="in-deco-ele in-deco-ele-second"></span>
          <span class="in-deco-ele in-deco-ele-third"></span>
        </div>
        <div class="main-deco-element main-deco-element-right">
          <span class="in-deco-ele in-deco-ele-first"></span>
          <span class="in-deco-ele in-deco-ele-second"></span>
          <span class="in-deco-ele in-deco-ele-third"></span>
        </div>

        <div class="main-common-inner">
          <div class="main-symbol-text-group">
            <figure class="in-symbol-area">
              <img
                src="assets/images/icon/category-symbol.svg"
                alt="카테고리 심볼 아이콘"
                class="in-symbol-img"
              />
            </figure>

            <article class="in-text-area">
              <h3 class="in-text-group">
                <span class="m-in-text">
                  <img
                    src="assets/images/icon/pocket_mall.svg"
                    alt="pocket mall"
                  />
                </span>
                <span
                  class="in-text"
                  v-html="categoryInfo.ca_intro"
                ></span>
                <!-- <button type="button" @click="DeviceCheck();">디바이스체크</button> -->
              </h3>
            </article>
          </div>

          <main-category-group :caId="categoryInfo.ca_id"></main-category-group>
        </div>
      </section>

      <section class="main-content-section">
        <h2 class="hide-text">카테고리별 컨텐츠 섹션</h2>

        <div class="main-content-group">
          <!-- 로딩 -->
          <card-loading-component v-if="productLoadingShow"></card-loading-component>
          <!-- 로딩 -->
          <main-category-card
            v-bind:popup.sync="popup"
            v-for="item in items"
            :key="'item'+item.item_id"
            :item="item"
            ref="itemCard"
            @OpenPopup="thumbClick"
          ></main-category-card>
        </div>
      </section>
    </div>

    <bottom-check-component :class="{ active : $store.state.carts.length > 0 }"></bottom-check-component>
    <rolling-banner />
    <main-popup-component
      v-bind:popup.sync="popup"
      :item="this.popupItems"
      v-if="popupShow"
      @ClosePopup="closePopup"
    ></main-popup-component>

    <mobile-check-component
      v-bind:popup.sync="popup"
      v-if="popup && $store.state.carts.length > 0 && this.IsMobile()"
    ></mobile-check-component>
    <div class="question_mark">
      <div class="text-box">대표님이 궁금하신 것!<br>미리 준비했습니다</div>
      <button type="button" @click="$store.commit('QuestionCompTrue')">
        <img src="/assets/images/icon/question_icon_text.svg" />
      </button>
    </div>
  </div>
</template>

<script>
  export default {
    name: "home",
    data() {
      return {
        caId: null,
        items: [],
        categoryInfo: {},
        popupShow: false,
        popupItems: {},
        opItems: {},
        popup: false
      };
    },
    beforeCreate() {
      Vue.$toast.clear();
    },
    async created() {
      document.body.addEventListener("click", this.SelectHandler, true);

      if (!this.$route.query.ca_id) {
        this.caId = 11;
        this.$router.replace({ name: "home", query: { ca_id: this.caId } });
      } else {
        this.caId = Number(this.$route.query.ca_id);
      }

      this.$store.state.categorys.forEach(category => {
        if (category.ca_id === this.caId) {
          this.categoryInfo = category;
        }
      });

      const res = await this.ItemLoad();

      this.items = res.query.items;
    },
    destroyed() {
      document.body.removeEventListener("click", this.SelectHandler, true);
    },
    watch: {
      async "$route.query.ca_id"() {
        this.caId = Number(this.$route.query.ca_id);

        this.items = [];

        this.$store.state.categorys.forEach(category => {
          if (category.ca_id === this.caId) {
            this.categoryInfo = category;
          }
        });

        const res = await this.ItemLoad();

        this.items = res.query.items;
      }
    },
    methods: {
      async ItemLoad() {
        // 아이템 불러오기
        try {
          this.ProductLoading(true);
          const params = {
            ca_id: this.caId
          };

          const res = (await this.$http.get("items", { params })).data;

          if (res.state === 1) {
            return res;
          } else {
            alert(res.msg);
          }
        } catch (e) {
          console.log(e);
        } finally {
          this.ProductLoading(false);
        }
      },
      SelectHandler(e) {
        if (e.target.className !== "in-selected-type") {
          document.getElementsByClassName("type-list-show").checked = false;
          if (this.$refs.itemCard) {
            this.$refs.itemCard.forEach(element => {
              element.SelectHide();
            });
          }
        }
      },
      thumbClick(item) {
        this.popupItems = item;

        if (this.popupItems) {
          this.popupShow = true;
        } else {
          this.popupShow = false;
          alert("Type을 선택해주세요.");
        }
      },
      closePopup() {
        this.popupItems = {};
        this.popupShow = false;
      }
    }
  };
</script>

<style lang="scss" scoped>
.question_mark{
  position: fixed;
  top: 59%;
  right: 10%;
  z-index: 2;
  .text-box{
    opacity: 0;
    position: absolute;
    top: -84px;
    left: 50%;
    transform: translateX(-50%);
    width: 174px;
    background: #3B3B3B;
    padding: 17px 0px;
    text-align: center;
    color: #fff;
    margin-bottom: 10px;
    border-radius: 10px;
    transition:top ease 0.6s, opacity ease 0.6s;
    &::after{
      content: "";
      position: absolute;
      bottom: -15px;
      left: 50%;
      transform: translateX(-50%);
      border-width: 20px 10px 0;
      border-style: solid;
      border-color: #3B3B3B transparent;
      display: block;
      width: 0;
    }
  }
  &:hover{
    .text-box{
      opacity: 1;
      top:-94px;
    }
  }
}
.question_mark button{
  width: 80px;
  height: 80px;
  border-radius: 50%;
  border:none;
  background: -webkit-linear-gradient(-45deg, rgba(29,178,255,1) 0%,rgba(29,113,255,1) 100%);
  background: linear-gradient(135deg, rgba(29,178,255,1) 0%,rgba(29,113,255,1) 100%);
  box-shadow: 12px 33px 27px rgba(73,143,206,0.23);
  cursor: pointer;
  outline: none;
}
.question_mark button img{
  height: 80%;
  border-radius: 50%;
}

@media all and (max-width: 991px) {
  .question_mark{
    position: fixed;
    top: 66%;
    right: 10px;
    z-index: 2;
  }
  .question_mark button{
    width: 80px;
    height: 80px;
  }
  .question_mark .text-box{
    display:none;
  }
  .question_mark button img{
    width: 100%;
  }
}

</style>