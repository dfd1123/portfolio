<template>
  <!-- 모바일 메인 메뉴 -->
  <div class="show-mobile">
    <div class="main-gnb-area">
      <div class="inner">
        <a
          href="#"
          v-if="isConfirm"
          class="in-list"
          @click.prevent="$router.push('/check-order')"
        >
          <img
            src="/images/icon/icon_02_wh.svg"
            alt="icon"
          />
          <span class="in-word">주문상품 확인</span>
        </a>
        <a
          href="#"
          v-if="isConfirm"
          class="in-list"
          @click.prevent="$router.push('/history-order-list')"
        >
          <img
            src="/images/icon/icon_03_wh.svg"
            alt="icon"
          />
          <span class="in-word">주문내역</span>
        </a>
        <a
          href="#"
          v-if="isConfirm"
          class="in-list"
          @click.prevent="$router.push('/history-cancel-list')"
        >
          <img
            src="/images/icon/icon_03_wh.svg"
            alt="icon"
          />
          <span class="in-word">취소/환불 내역</span>
        </a>
        <a
          href="#"
          v-if="isConfirm"
          class="in-list"
          @click.prevent="$router.push('/manage-product-list')"
        >
          <img
            src="/images/icon/icon_05_wh.svg"
            alt="icon"
          />
          <span class="in-word">상품관리</span>
        </a>
        <a
          href="#"
          v-if="isConfirm"
          class="in-list"
          @click.prevent="$router.push('/inquiry-product-list')"
        >
          <img
            src="/images/icon/icon_06_wh.svg"
            alt="icon"
          />
          <span class="in-word">상품문의</span>
        </a>
        <a
          href="#"
          v-if="isConfirm"
          class="in-list"
          @click.prevent="$router.push('/manage-review')"
        >
          <img
            src="/images/icon/icon_07_wh.svg"
            alt="icon"
          />
          <span class="in-word">구매후기</span>
        </a>
        <a
          href="#"
          v-if="isConfirm"
          class="in-list"
          @click.prevent="$router.push('/sales-status')"
        >
          <img
            src="/images/icon/icon_08_wh.svg"
            alt="icon"
          />
          <span class="in-word">매출현황</span>
        </a>
        <a
          href="#"
          class="in-list"
          @click.prevent="$router.push('/setting-store')"
        >
          <img
            src="/images/icon/icon_01_wh.svg"
            alt="icon"
          />
          <span class="in-word">스토어 설정</span>
        </a>
      </div>
    </div>
  </div>
  <!-- END 모바일 메인 메뉴 -->
</template>

<script>
export default {
  name: 'MainMenuMobile'
}
</script>

<style>
</style>
