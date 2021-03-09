<template>
  <div class="container invoice-container">
    <span class="container-bg"></span>
    <span class="container-bg-bottom"></span>

    <div class="invoice-wrapper">
      <section class="invoice-wrap">
        <div class="invoice_title clearfix">
          <div class="icon_box"></div>
          <h2 class="_title">
            견적서
            <small class="txt-en">invoice</small>
          </h2>
          <div class="_company">
            <div class="_name">주식회사 포켓컴퍼니</div>
            <div class="_sub">소중한 인연을 기대합니다</div>
          </div>
        </div>
        <div class="invoice-desc">
          <h3 class="_title">신청해주셔서 감사합니다</h3>
          <table class="invoice-prdt-table">
            <thead class="tbl-hd">
              <tr>
                <th class="_service">service name</th>
                <th class="_concept">concept</th>
                <th class="_price">price</th>
              </tr>
            </thead>
          </table>

          <!-- 테이블영역 -->
          <sub-invoice-table :items="items" :invoice="invoice"></sub-invoice-table>
          <!-- END 테이블영역 -->

          <!-- 정보영역 -->
          <sub-invoice-result :invoice="invoice"></sub-invoice-result>
          <!-- END 정보영역 -->

          <div class="in-button-wrap">
            <button
              type="button"
              class="rounded-button btn-gray"
              @click="MailSubmit"
            >위 내용을 E-mail로 보내기</button>
            <button
              type="button"
              class="rounded-button btn-bluegradi"
              @click="ConfirmInvoice"
            >확인했습니다</button>
          </div>
        </div>
      </section>
    </div>

    <mail-loading-component v-if="mailLoading"></mail-loading-component>
  </div>
</template>

<script>
export default {
  name: "check-invoice-page",
  data() {
    return {
      items: [],
      invoice: {},
      mailLoading: false
    };
  },
  async created() {
    const res = await this.InvoiceLoad();

    this.items = res.query.items;
    this.invoice = res.query.invoice;
  },
  methods: {
    async InvoiceLoad() {
      try {
        const params = {
          ca_id: this.caId
        };

        const res = (
          await this.$http.get("request_quote/" + this.$route.query.rq_id)
        ).data;

        if (res.state === 1) {
          return res;
        } else {
          alert(res.msg);
        }
      } catch (e) {
        console.log(e);
      }
    },
    async MailSubmit() {
      try {
        this.mailLoading = true;
        const params = {
          items: this.items,
          req_name: this.invoice.req_name,
          req_tel: this.invoice.req_tel,
          req_company: this.invoice.req_company,
          req_email: this.invoice.req_email,
          req_contents: this.invoice.req_contents,
          req_date: this.invoice.req_date,
          total_price: this.invoice.total_price,
          created_at: this.invoice.created_at
        };

        if(this.IsMobile()){
          params.device = 'mobile'
        }else{
          params.device = 'pc'
        }

        const res = (await this.$http.post("mail/invoce", params)).data;

        if (res.state === 1) {
          this.mailLoading = false;

          this.$swal({
            html: "메일이 전송되었습니다!<br>메일함을 확인해보세요~",
            imageUrl: "/assets/images/icon/mail-pop-icon.svg",
            imageWidth: "auto",
            imageHeight: 100,
            imageAlt: "Custom image",
            showCancelButton: true,
            confirmButtonText: "홈으로 이동",
            cancelButtonText: "닫기",
            customClass: {
              confirmButton: "mail-pop-btn mail-pop-btn-confirm",
              cancelButton: "mail-pop-btn mail-pop-btn-cancel",
              image: "mail-pop-image"
            }
          }).then(result => {
            if (result.value) {
              this.$store.commit("CartReset");
              this.$router.push("/home");
            } else {
              console.log("닫음");
            }
          });

          return res;
        } else {
          alert(res.msg);
        }
      } catch (e) {
        console.log(e);
      }
    },
    ConfirmInvoice() {
      this.$store.commit("CartReset");
      this.$router.push("/home");
    }
  }
};
</script>

<style scoped>
.invoice-container .in-button-wrap {
  text-align: right;
}

.invoice-container .in-button-wrap .rounded-button {
  min-width: 181px;
  height: 54px;
  width: auto;
  display: inline-block;
  font-size: 1.125em;
  padding: 0 30px;
}
</style>