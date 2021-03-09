<template>
  <div class="container cart-container">
    <span class="container-bg"></span>

    <div class="cart-wrapper">
      <section class="cart-wrap clearfix">
        <div class="order-wrap">
          <h2 class="order-title">register</h2>
          <div class="order-desc">
            최종 접수 내용을 확인한 후,
            <br />필수 정보를 입력해주세요
          </div>
          <sub-cart-history :carts="carts"></sub-cart-history>
        </div>
        <!-- order wrap E -->

        <!-- contect wrap -->
        <sub-cart-form :form-data="form" @submit="Submit"></sub-cart-form>
        <!-- contect wrap E -->
      </section>
    </div>

    <mobile-confirm-component v-if="popup && this.IsMobile()"></mobile-confirm-component>
  </div>
</template>

<script>
export default {
  name: "check-cart-page",
  beforeCreate() {
    if (this.$store.state.carts.length === 0) {
      this.$swal({
        html: "장바구니가 비어있습니다.<br>메인 페이지로 이동합니다.",
        type: "warning",
        icon: "warning",
        confirmButtonText: "확인",
        customClass: {
          icon: "custom-warning-icon"
        }
      }).then(result => {
        this.$router.push("/home");
      });
    }

    Vue.$toast.clear();
  },
  data() {
    return {
      carts: this.$store.state.carts,
      form: {
        items: [],
        req_name: null,
        req_tel: null,
        req_email: null,
        req_company: null,
        req_contents: null,
        req_date: null
      },
      popup: false
    };
  },
  methods: {
    Validation() {
      if (!this.form.req_name) {
        alert("견적을 요청하시는 분의 이름을 입력해주세요.");
        return false;
      }

      if (!this.form.req_tel) {
        alert("견적을 요청하시는 분의 연락처를 입력해주세요.");
        return false;
      }

      if (!this.form.req_company) {
        alert("견적을 요청하시는 회사명을 입력해주세요.");
        return false;
      }

      if (!this.form.req_date) {
        alert("미팅이나 연락이 가능한 날짜를 입력해주세요.");
        return false;
      }

      if (!this.form.req_contents) {
        alert("기타 문의 사항을 입력해주세요.");
        return false;
      }

      return true;
    },
    async Submit(form) {
      if (this.Validation()) {
        this.form = form;
        this.form.items = JSON.stringify(this.carts);

        //axios 요청
        console.log(this.form);

        try {
          const res = (await this.$http.post("request_quote", this.form)).data;

          if (res.state === 1) {
            if (this.IsMobile()) {
              this.popup = true;
              const params = {
                items: this.carts,
                req_name: this.form.req_name,
                req_tel: this.form.req_tel,
                req_company: this.form.req_company,
                req_email: this.form.req_email,
                req_contents: this.form.req_contents,
                req_date: this.form.req_date,
                total_price: this.$store.state.totalPrice,
                created_at: null
              };

              params.device = 'mobile';

              this.$http.post("mail/invoce", params);
            } else {
              this.popup = false;
              this.$router.push({
                name: "check-invoice",
                query: { rq_id: res.query.reqQuoteId }
              });
            }
          } else {
            alert(res.msg);
          }
        } catch (e) {
          console.log(e);
        }
      }
    }
  }
};
</script>

<style scoped>
</style>