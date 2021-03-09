<template>
  <div :class="['dg-coupon-card', {'type-download':kind==='download', 'active':couponList.get_coupon === 1}]">
    <div class="dg-coupon-card-inner">
      <div class="in-information">
        <h5 class="_tit">
          {{ couponList.cp_subject || couponList.cz_subject }}
        </h5>
        <dl :class="['_desc', {'download': kind==='download'}]">
          <dt v-if="couponList.cp_type === 0">
            할인금액
          </dt>
          <dt v-if="couponList.cp_type === 1">
            할인률
          </dt>
          <dd v-if="couponList.cp_type === 0">
            {{ NumberFormat(couponList.cp_price) }}원 할인
          </dd>
          <dd v-else-if="couponList.cp_type === 1">
            {{ couponList.cp_price }}% 할인
          </dd>
        </dl>
        <dl
          v-if="couponList.cp_minimum"
          :class="['_desc', {'download': kind==='download'}]"
        >
          <dt>
            주문금액
          </dt>
          <dd>
            최소 {{ NumberFormat(couponList.cp_minimum) }} 원
          </dd>
        </dl>
        <dl :class="['_desc', {'download': kind==='download'}]">
          <dt v-if="kind==='download'">
            다운로드 가능 기간
          </dt>
          <dt v-else>
            사용기간
          </dt>
          <dd v-if="kind==='download'">
            {{ $moment(couponList.cz_start).format('YYYY-MM-DD') }} ~ {{ $moment(couponList.cz_end).format('YYYY-MM-DD') }}
          </dd>
          <dd v-else>
            {{ $moment(couponList.cp_start).format('YYYY-MM-DD') }} ~ {{ $moment(couponList.cp_end).format('YYYY-MM-DD') }}
          </dd>
        </dl>
        <dl :class="['_desc', {'download': kind==='download'}]">
          <dt>적용범위</dt>
          <dd>
            <span v-if="couponList.cp_method === 0">개별상품 할인</span>
            <span v-if="couponList.cp_method === 1">카테고리 할인</span>
            <span v-if="couponList.cp_method === 2">주문금액 할인</span>
            <span v-if="couponList.cp_method === 3">배송비 할인</span>
            <button
              v-if="couponList.cp_method === 0 || couponList.cp_method === 1"
              type="button"
              class="rounded-btn-outline"
              @click="$emit('popup-event', couponList)"
            >
              적용상품 보기
            </button>
          </dd>
        </dl>
      </div>
      <!-- ※ 다운로드 버튼 -->
      <div
        v-if="kind === 'download'"
        class="in-download"
      >
        <button
          type="button"
          @click="CouponDownload"
        >
          <img
            :src="couponList.get_coupon === 1?'/images/btn/btn_down_off.svg':'/images/btn/btn_down_on.svg'"
            alt="download btn"
            :class="['_icon', {'_icon--on':couponList.get_coupon === 0, '_icon--off': couponList.get_coupon === 1}]"
          >
        </button>
      </div>
      <!-- ※ 다운로드 버튼 END -->
    </div>
  </div>
</template>

<script>
  export default {
    props: {
      kind: {
        type: String,
        default: ''
      },
      couponList: {
        type: Object,
        required: true
      }
    },
    methods: {
      async CouponDownload () {
        if (this.couponList.get_coupon === 0) {
          const result = await this.Confirm('정말 쿠폰을 다운로드 받으시겠습니까?')
          if (result) {
            const params = {
              cz_id: this.couponList.id
            }

            try {
              const res = (await this.$http.post(this.$APIURI + 'coupon', params)).data
              if (res.state === 1) {
                if (res.query) {
                  this.couponList.get_coupon = 1
                }
              } else {
                console.log(res.msg)
              }
            } catch (e) {
              console.log(e)
            }
          }
        } else {
          this.WarningAlert('이미 보유하고 계시거나 사용하신 쿠폰입니다.')
        }
      }
    }
  }
</script>

<style lang="scss" scoped>
  .dg-coupon-card ._desc.download {
    padding-left: 105px;
  }
</style>
