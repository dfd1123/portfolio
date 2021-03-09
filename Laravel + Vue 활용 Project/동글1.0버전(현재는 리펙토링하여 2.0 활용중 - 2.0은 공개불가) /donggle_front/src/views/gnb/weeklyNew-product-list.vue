<template>
  <!-- content -->
  <div id="dg-gnb-choice-wrapper" class="l-page-wrapper">
    <div class="dg-gnb-choice-group">
      <div class="dg-gnb-title-group">
        <!-- * 대메뉴 제목 -->
        <GnbTitle gnb-title="주간신상(Weekly new)" gnb-text="이번주 신상으로 올라온 상품을 소개드려요." />
        <!-- * 대메뉴 제목 E -->
        <ul class="in-breadcrumb type01">
          <li class="_list">
            <a href="/">홈</a>
            <img src="/images/icon/icon_breadcrumb_arrow.svg" alt="arrow" class="_next" />
          </li>
          <li class="_list active">
            <a href="/product/list/weeklyNew">주간신상(Weekly new)</a>
          </li>
        </ul>
      </div>
    </div>

    <!-- * 상품리스트 구역 -->
    <div class="l-con-area">
      <article class="l-con-article">
        <div class="l-con-title-group type01">
          <h2 class="in-subject">상품리스트</h2>
          <ul class="in-sorting">
            <input
              type="radio"
              id="popular"
              class="sorting-checkbox display_none"
              value="popular"
              v-model="form.orderBy"
              @change="Submit"
            />
            <li class="_type">
              <label for="popular" class="word">인기순</label>
            </li>
            <input
              type="radio"
              id="new"
              class="sorting-checkbox display_none"
              value="new"
              v-model="form.orderBy"
              @change="Submit"
            />
            <li class="_type">
              <label for="new" class="word">신상품순</label>
            </li>
            <input
              type="radio"
              id="lowPrice"
              class="sorting-checkbox display_none"
              value="lowPrice"
              v-model="form.orderBy"
              @change="Submit"
            />
            <li class="_type">
              <label for="lowPrice" class="word">낮은 가격순</label>
            </li>
            <input
              type="radio"
              id="highPrice"
              class="sorting-checkbox display_none"
              value="highPrice"
              v-model="form.orderBy"
              @change="Submit"
            />
            <li class="_type">
              <label for="highPrice" class="word">높은 가격순</label>
            </li>
            <input
              type="radio"
              id="review"
              class="sorting-checkbox display_none"
              value="review"
              v-model="form.orderBy"
              @change="Submit"
            />
            <li class="_type">
              <label for="rating" class="word">
                상품평 순
                <input
                  type="radio"
                  id="rating"
                  class="display_none"
                  value="rating"
                  v-model="form.orderBy"
                  @change="Submit"
                />
              </label>
            </li>
          </ul>
        </div>

        <div class="l-contents-group">
          <ul v-if="itemLists.length > 0" class="l-grid-group">
            <li
              v-for="itemList in itemLists"
              :key="'item'+itemList.item_id"
              class="l-grid-list l-col-5"
            >
              <!-- * 옷상품 카드 -->
              <ItemThumb :item-list="itemList" />
              <!-- * 옷상품 카드 E -->
            </li>
          </ul>
          <div v-else class="nothing-history">
            <img src="/images/icon/empty_recent.svg" alt="icon empty" class="in-empty-icon" />
            <span class="in-empty-ment">주간신상(Weekly new)에 등록된 상품이 없습니다.</span>
          </div>
          <div class="loading_wrap" v-show="bottomLoadingShow">
            <Loading />
          </div>
        </div>
      </article>
    </div>
    <!-- * 상품리스트 구역 E -->
  </div>
  <!-- content E -->
</template>

<script>
import GnbTitle from "@/components/gnb/gnb-title.vue";
import ItemThumb from "@/components/thumbnail/item-thumb.vue";
import Loading from "@/components/common/loading/loading.vue";

export default {
  components: {
    GnbTitle,
    ItemThumb,
    Loading
  },
  data: function() {
    return {
      created: false,
      form: {
        itemCount: this.$route.query.itemCount || 0,
        orderBy: this.$route.query.orderBy || "popular",
        offset: this.$route.query.offset || 0,
        limit: this.$route.query.limit || 30
      },
      itemLists: [],
      bottomLoadingShow: false,
      position: 0
    };
  },
  async created() {
    this.$store.commit("ProgressShow");

    const res = await this.ItemLoad();
    this.itemLists = res.query.items;
    this.form.itemCount = res.query.count;
    this.form.offset += res.query.items.length;

    window.addEventListener("scroll", this.InfiniteLoad);

    this.created = true;

    this.$nextTick(function() {
      if (
        this.$store.state.beforUrl.includes("/product/view/") &&
        this.$store.state.position !== 0
      ) {
        document.documentElement.scrollTop = this.$store.state.position;
        this.$store.commit("ResetPosition");
      }
    });

    this.$store.commit("ProgressHide");
  },
  beforeDestroy() {
    this.$store.commit("SavePosition", this.position);
  },
  destroyed() {
    window.removeEventListener("scroll", this.InfiniteLoad);
  },
  methods: {
    async ItemLoad() {
      try {
        const params = this.form;

        if (
          this.$store.state.beforUrl.includes("/product/view/") &&
          this.$store.state.offset &&
          this.$store.state.position !== 0
        ) {
          params.limit = this.$store.state.offset;
        }

        const res = (
          await this.$http.get(this.$APIURI + "items/week_list", {
            params: this.form
          })
        ).data;

        return res;
      } catch (e) {
        console.log(e);
      }
    },
    async Submit() {
      this.$store.commit("ProgressShow");

      const params = {
        orderBy: this.form.orderBy
      };
      this.$router.replace({ name: "weeklyNew-product-list", query: params });
      this.form.offset = 0;
      const res = await this.ItemLoad();
      this.itemLists = res.query.items;
      this.form.itemCount = res.query.count;
      this.form.offset += res.query.items.length;

      // this.$router.push({ name: 'best-product-list', query: this.form })

      this.$store.commit("ProgressHide");
    },
    async InfiniteLoad() {
      this.position = document.documentElement.scrollTop;
      let top =
        (document.documentElement && document.documentElement.scrollTop) ||
        document.body.scrollTop;
      let bottomOfWindow =
        top + window.innerHeight + 50 > document.documentElement.offsetHeight &&
        top + window.innerHeight - 50 < document.documentElement.offsetHeight;
      if (
        bottomOfWindow &&
        this.form.itemCount !== this.form.offset &&
        !this.bottomLoadingShow
      ) {
        this.bottomLoadingShow = true;
        const res = await this.ItemLoad();

        this.itemLists = this.itemLists.concat(res.query.items);
        this.form.itemCount = res.query.count;
        this.form.offset += res.query.items.length;
        this.$store.commit("ViewOffsetSet", this.form.offset);
        this.bottomLoadingShow = false;
      }
    }
  }
};
</script>

<style lang="scss" scoped>
.nothing-history {
  margin-top: 80px;
}
</style>
