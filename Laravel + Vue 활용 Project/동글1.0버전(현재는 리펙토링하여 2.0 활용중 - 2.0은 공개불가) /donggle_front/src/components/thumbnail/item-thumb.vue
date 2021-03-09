<template>
  <div class="dg-clothes-list-card">
    <figure class="dg-clothes-thumbnail">
      <LikeButton
        v-if="kind !== 'review' && kind !== 'recent'"
        :item-no="itemList.item_id"
        :seller-id="itemList.seller_id"
        :like="itemList.zzim"
      />
      <router-link
        v-if="ConvertImage(itemList.images).length === 0"
        class="in-image"
        :to="'/product/view/' + itemList.item_id"
        style="background-image: url('/images/img/thumbnail.png');"
      />
      <router-link
        v-else
        class="in-image"
        :to="'/product/view/' + itemList.item_id"
        :style="'background-image: url('+storageUrl+ConvertImage(itemList.images)[0]+');'"
      />
    </figure>

    <ul class="in-information">
      <li class="_category">
        {{ itemList.ca_name || '-' }}
      </li>
      <li class="_sellername">
        <router-link :to="'/store/'+itemList.store_id">
          {{ itemList.company_name || '-' }}
        </router-link>
      </li>
      <li class="_name">
        <router-link :to="'/product/view/' + itemList.item_id">
          {{ itemList.title || '-' }}
        </router-link>
      </li>
      <li class="_options">
        <dl class="_op_category">
          <dt class="_op_tit">
            색상
          </dt>
          <dd class="_op_list">
            <span
              v-for="(color, index) in Colors"
              :key="'color'+index"
            >{{ color }}</span>
          </dd>
        </dl>
        <dl class="_op_category">
          <dt class="_op_tit">
            사이즈
          </dt>
          <dd class="_op_list">
            <span
              v-for="(size, index) in Sizes"
              :key="'size'+index"
            >{{ size }}</span>
          </dd>
        </dl>
      </li>
    </ul>

    <ul
      v-if="kind === 'default'"
      class="in-pricedetail"
    >
      <li class="_streetprice">
        <span>시중가:</span><span>{{ NumberFormat(itemList.cust_price) }}원</span>
      </li>
      <li class="_finalprice">
        <b>{{ NumberFormat(itemList.price) }}</b><span>원</span>
        <img
          src="/images/icon/icon_review.svg"
          alt="star icon"
        ><span class="_rate">{{ (itemList.rating || 0).toFixed(1) }}</span>
      </li>
      <li class="_hashlist">
        <div class="_inner">
          <button
            v-for="(hashTag, index) in HashTags"
            :key="index"
            class="rounded-list rounded-list-s"
            type="button"
            @click="HashTagSubmit(hashTag)"
          >
            <span>{{ hashTag }}</span>
          </button>
        </div>
        <div class="_hashlist-more">
          <input
            type="button"
            value="+"
            class="in-morebtn"
          >
          <div class="_hashlist-con">
            <button
              v-for="(hashTag, index) in HashTags"
              :key="index"
              class="rounded-list rounded-list-s"
              type="button"
              @click="HashTagSubmit(hashTag)"
            >
              <span>{{ hashTag }}</span>
            </button>
          </div>
        </div>
      </li>
    </ul>

    <ul
      v-if="kind === 'review'"
      class="in-star-review"
    >
      <li class="_star">
        <span class="_star_tit">내 별점</span>
        <!-- 1. 별점 있을 때
                          ※ 별 점수에 따라 star-05, star-04, star-03, star-02, star-01 클래스 추가 -->
        <div
          v-if="itemList.my_rating"
          :class="'in-star-group star-0'+(itemList.my_rating || 0).toFixed(0)"
        >
          <i class="_img"></i>
          <span class="_rate"></span>
          <b class="_rate_txt"></b>
        </div>
        <!-- 1. 별점 있을 때 E -->
        <!-- 2. 별점 없을 때 -->
        <div
          v-else
          class="_star_con_text"
        >
          후기를 작성해주세요.
        </div>
        <!-- 2. 별점 없을 때 E -->
      </li>
      <li class="_button">
        <!-- 별점 있을 때 active 클래스 추가-->
        <button
          type="button"
          v-if="itemList.my_rating"
          class="rounded-btn-outline active"
        >
          후기 작성완료
        </button>
        <!-- E -->
        <!-- 별점 없을 때 -->
        <button
          type="button"
          v-else
          class="rounded-btn-outline active"
          @click="$emit('popup-event', itemList)"
        >
          후기 작성하기
        </button>
        <!-- E -->
      </li>
    </ul>

    <ul
      v-if="kind === 'like' || kind === 'recent'"
      class="in-star-review"
    >
      <li class="_star">
        <span class="_star_tit">별점</span>
        <!-- ※ 별 점수에 따라 star-05, star-04, star-03, star-02, star-01 클래스 추가 -->
        <div :class="'in-star-group star-0' + (itemList.rating || 0.0).toFixed(0)">
          <i class="_img"></i>
          <span class="_rate"></span>
          <b class="_rate_txt"></b>
        </div>
        <!-- ※ 별 점수에 따라 star-05, star-04, star-03, star-02, star-01 클래스 추가 END -->
      </li>
      <!-- ※ 제일 최근 리뷰 1개 -->
      <li class="_reviews">
        <p v-if="itemList.last_review">
          {{ itemList.last_review }}
        </p>
        <p v-else>
          작성된 리뷰가 없습니다.
        </p>
      </li>
      <!-- ※ 제일 최근 리뷰 1개 END -->
    </ul>
  </div>
</template>

<script>
  import LikeButton from '@/components/thumbnail/like-button.vue'
  export default {
    components: {
      LikeButton
    },
    props: {
      itemList: {
        type: Object,
        required: true
      },
      kind: {
        type: String,
        default: 'default'
      },
      url: {
        type: String,
        default: '/total/search'
      },
      searchYn: {
        type: Boolean,
        default: false
      }
    },
    computed: {
      HashTags () {
        if (this.itemList.hash_tag) {
          return this.itemList.hash_tag.split(',')
        }

        return []
      },
      Colors () {
        if (this.itemList.color) {
          return this.itemList.color.split(',')
        }

        return []
      },
      Sizes () {
        if (this.itemList.size) {
          return this.itemList.size.split(',')
        }

        return []
      }
    },
    methods: {
      HashTagSubmit (hashTag) {
        console.log('awdad')
        if (this.searchYn) {
          this.$emit('hash-tag', hashTag)
        } else {
          this.$router.push(this.url + '?hash_tag=' + hashTag)
        }
      }
    }
  }
</script>

<style lang="scss" scoped>
  .dg-clothes-list-card .in-pricedetail .rounded-list-s {
    border: none;
    outline: none;
    cursor: pointer;
  }
</style>
