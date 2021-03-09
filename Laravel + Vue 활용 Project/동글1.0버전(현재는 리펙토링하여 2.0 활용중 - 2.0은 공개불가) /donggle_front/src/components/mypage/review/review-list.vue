<template>
  <div class="dg-review-history-card">
    <div class="in-content">
      <div class="inner-box">
        <figure class="dg-clothes-thumbnail">
          <router-link
            v-if="ConvertImage(review.images).length > 0"
            class="in-image"
            to="#"
            :style="'background-image: url('+storageUrl+ConvertImage(review.images)[0]+')'"
          />
          <router-link
            v-else
            class="in-image"
            to="#"
            style="background-image: url('/images/img/thumbnail.png')"
          />
        </figure>
        <!-- ※ 수정, 삭제 버튼 추가 -->
        <div class="dg-my-review-btn-wrap">
          <button
            type="button"
            class="rounded-btn-outline btn--edit"
            @click="$emit('popup-event')"
          >
            수정
          </button>
          <button
            type="button"
            class="rounded-btn-outline btn--del"
            @click="$emit('delete-event')"
          >
            삭제
          </button>
        </div>
        <!-- ※ 수정, 삭제 버튼 추가 END -->
        <ul class="_informations">
          <li class="_sellername">
            <router-link :to="'/store/'+review.store_id">
              {{ review.company_name }}
            </router-link>
          </li>
          <li class="_prdtnum">
            <router-link :to="'/review/'+review.item_id+'/view/'+review.id">
              {{ review.item_id }}
            </router-link>
          </li>
        </ul>
        <h3 class="_name">
          <router-link :to="'/review/'+review.item_id+'/view/'+review.id">
            {{ review.title || '-' }}
          </router-link>
        </h3>
        <!-- ※ 리뷰 작성 날짜 추가 -->
        <span class="_date">{{ $moment(review.created_at).format('YYYY년 MM월 DD일') }}</span>
        <!-- ※ 리뷰 작성 날짜 추가 END -->

        <!-- ※ 별 점수에 따라 star-05, star-04, star-03, star-02, star-01 클래스 추가 -->
        <div :class="'in-star-group star-0'+review.rating.toFixed(0)">
          <i class="_img"></i>
          <span class="_rate"></span>
          <b class="_rate_txt"></b>
        </div>
        <!-- ※ 별 점수에 따라 star-05, star-04, star-03, star-02, star-01 클래스 추가 END -->

        <!-- ※ 리뷰 작성내용 -->
        <div
          class="_review_detail"
          @click="$router.push('/review/'+review.item_id+'/view/'+review.id)"
        >
          <p v-html="review.review_body">
          </p>
        </div>
        <!-- ※ 리뷰 작성내용 END -->
      </div>
    </div>
  </div>
</template>

<script>
  export default {
    props: {
      review: {
        type: Object,
        required: true
      }
    }
  }
</script>

<style lang="scss" scoped>
</style>
