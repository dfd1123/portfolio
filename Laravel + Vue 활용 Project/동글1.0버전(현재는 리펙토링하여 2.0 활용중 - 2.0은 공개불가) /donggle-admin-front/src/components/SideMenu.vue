<template>
  <!-- 메뉴 -->
  <div class="in-gnb-group">
    <a
      href="#"
      v-if="isConfirm"
      class="in-gnb-list"
      :class="{active: $route.path === '/check-order'}"
      @click.prevent="$router.push('/check-order')"
    >
      <img
        class="in-gnb-icon mode-off"
        src="/images/icon/icon_02.svg"
        alt="gnb icon"
      />
      <img
        class="in-gnb-icon mode-on"
        src="/images/icon/icon_02_on.svg"
        alt="gnb icon"
      />
      <span class="in-gnb-name">주문상품 확인</span>
    </a>
    <a
      href="#"
      v-if="isConfirm"
      class="in-gnb-list"
      :class="{active: $route.path === '/history-order-list'}"
      @click.prevent="$router.push('/history-order-list')"
    >
      <img
        class="in-gnb-icon mode-off"
        src="/images/icon/icon_03.svg"
        alt="gnb icon"
      />
      <img
        class="in-gnb-icon mode-on"
        src="/images/icon/icon_03_on.svg"
        alt="gnb icon"
      />
      <span class="in-gnb-name">주문내역</span>
    </a>
    <a
      href="#"
      v-if="isConfirm"
      class="in-gnb-list"
      :class="{active: $route.path === '/history-cancel-list'}"
      @click.prevent="$router.push('/history-cancel-list')"
    >
      <img
        class="in-gnb-icon mode-off"
        src="/images/icon/icon_03.svg"
        alt="gnb icon"
      />
      <img
        class="in-gnb-icon mode-on"
        src="/images/icon/icon_03_on.svg"
        alt="gnb icon"
      />
      <span class="in-gnb-name">취소/환불 내역</span>
    </a>
    <a
      href="#"
      v-if="isConfirm"
      class="in-gnb-list"
      :class="{active: $route.path === '/manage-product-list'}"
      @click.prevent="$router.push('/manage-product-list')"
    >
      <img
        class="in-gnb-icon mode-off"
        src="/images/icon/icon_05.svg"
        alt="gnb icon"
      />
      <img
        class="in-gnb-icon mode-on"
        src="/images/icon/icon_05_on.svg"
        alt="gnb icon"
      />
      <span class="in-gnb-name">상품관리</span>
    </a>
    <a
      href="#"
      v-if="isConfirm"
      class="in-gnb-list"
      :class="{active: $route.path === '/inquiry-product-list'}"
      @click.prevent="$router.push('/inquiry-product-list')"
    >
      <img
        class="in-gnb-icon mode-off"
        src="/images/icon/icon_06.svg"
        alt="gnb icon"
      />
      <img
        class="in-gnb-icon mode-on"
        src="/images/icon/icon_06_on.svg"
        alt="gnb icon"
      />
      <span class="in-gnb-name">상품문의</span>
    </a>
    <a
      href="#"
      v-if="isConfirm"
      class="in-gnb-list"
      :class="{active: $route.path === '/manage-review'}"
      @click.prevent="$router.push('/manage-review')"
    >
      <img
        class="in-gnb-icon mode-off"
        src="/images/icon/icon_07.svg"
        alt="gnb icon"
      />
      <img
        class="in-gnb-icon mode-on"
        src="/images/icon/icon_07_on.svg"
        alt="gnb icon"
      />
      <span class="in-gnb-name">구매후기</span>
    </a>
    <a
      href="#"
      v-if="isConfirm"
      class="in-gnb-list"
      :class="{active: $route.path === '/sales-status'}"
      @click.prevent="$router.push('/sales-status')"
    >
      <img
        class="in-gnb-icon mode-off"
        src="/images/icon/icon_08.svg"
        alt="gnb icon"
      />
      <img
        class="in-gnb-icon mode-on"
        src="/images/icon/icon_08_on.svg"
        alt="gnb icon"
      />
      <span class="in-gnb-name">매출현황</span>
    </a>
    <a
      href="#"
      class="in-gnb-list"
      :class="{active: $route.path === '/setting-store'}"
      @click.prevent="$router.push('/setting-store')"
    >
      <img
        class="in-gnb-icon mode-off"
        src="/images/icon/icon_01.svg"
        alt="gnb icon"
      />
      <img
        class="in-gnb-icon mode-on"
        src="/images/icon/icon_01_on.svg"
        alt="gnb icon"
      />
      <span class="in-gnb-name">스토어 설정</span>
    </a>
  </div>
  <!-- END 메뉴 -->
</template>

<script>
export default {
  name: 'SideMenu'
}
</script>

<style>
</style>
