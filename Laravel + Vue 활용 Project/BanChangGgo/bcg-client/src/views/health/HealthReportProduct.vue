<template>
  <div v-if="info" id="hlthreport_prdt_page" class="page page--hd_default">
    <header-component hd-title="보조약품 정보" :hd-theme-ylw="true" @privButtonClick="privButtonClick" />
    <div class="page_container">
      <transition appear name="slide-fade">
        <div class="contents">
          <figure class="product_thumbnail">
            <img v-if="info.mdcn_thumb" :src="`/fdata/medicine_thumb/${info.mdcn_thumb}`" />
          </figure>
          <div class="product_info">
            <article class="product_price_article">
              <h2>{{info.mdcn_title}}</h2>
              <p>{{get(info.mdcn_extra, 'sub_title', '')}}</p>
              <span v-if="get(info.mdcn_extra, 'price', null)">
                {{get(info.mdcn_extra, 'price', '')}}
                <i>원</i>
              </span>
            </article>
            <article class="product_detail_article">
              <h2>상세정보</h2>
              <p>{{info.mdcn_desc}}</p>
            </article>
          </div>
          <input
            type="button"
            value="구매하러가기"
            class="wide_btn btn_clear_to_theme step_btn active"
            @click="open(info.mdcn_link)"
          />
        </div>
      </transition>
    </div>
  </div>
</template>

<script>
import get from 'lodash/get'

export default {
  name: 'HealthReportProduct',
  data () {
    return {
      info: null
    }
  },
  async created () {
    this.info = this.$route.params.item
    if (!this.info) {
      return this.$router.go(-1)
    }
  },
  methods: {
    ...{ get },
    privButtonClick () {
      window.history.back()
    },
    open (url) {
      window.open(url, '_blank', '')
    }
  }
}
</script>

<style lang="scss" scoped>
#hlthreport_prdt_page {
  .page_container {
    overflow-x: hidden;
  }
  .step_btn {
    @include btButton;
  }
  .contents {
    padding: 0 0 7rem;
    overflow-y: scroll;
  }
  .product_thumbnail {
    height: auto;
    background-color: #f8f8f8;
    text-align: center;
  }
  .product_thumbnail img {
    max-width: 100%;
  }
  .product_info {
    padding: 1.5rem 2rem;
  }
  .product_price_article {
    padding-bottom: 1rem;
    border-bottom: 1px solid #e6e6e6;
    & > h2 {
      @include remFont("20px", $weight: 500, $color: $gray-01);
    }
    & > p {
      @include remFont("14px", $weight: 400, $color: $gray-03);
    }
    & > span {
      @include remFont("20px", $weight: bold, $color: $theme-02);
      display: inline-block;
      text-align: right;
      width: 100%;
    }
    & > span i {
      @include remFont("15px", $weight: 500, $color: inherit);
    }
  }
  .product_detail_article {
    & > h2 {
      @include remFont("18px", $weight: bold, $color: $gray-01);
      padding: 1rem 0;
    }
    & > p {
      @include remFont("15px", $weight: 300, $color: $gray-01);
    }
  }
}
</style>
