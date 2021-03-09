<template>
  <div>
    <RegHeader page-name="구독(정기)결제 정보 등록" :only-logo="false" :step-view="false" />

    <!-- content -->
    <section id="subscribe-page" class="step_common">
      <h2 class="dg_blind">구독(정기)결제</h2>
      <div
        v-for="subscribe in subscribes"
        :key="subscribe.id"
        @click="SelectItem(subscribe.id)"
        :class="['dg-choice_box-btn dg-subscribe-btn dg-sub_buy-btn',{'active':subscribe.choice}]"
        :style="'background-image:url('+subscribe.symbolImg+')'"
      >
        <h3 class="_tit">{{ subscribe.title }}</h3>
        <p class="_desc" v-html="subscribe.body"></p>
        <p class="_price">
          <b>{{ NumberFormat(subscribe.price) }}원</b> / 월
        </p>
      </div>
      <form>
        <fieldset class="dg-reg_check_form">
          <div class="dg_write write_check">
            <!-- test, test1, test2, test3를 #으로 변경 -->
            <input
              type="checkbox"
              id="allAgree"
              class="dg-input-checkbox display_none"
              v-model="allAgree"
              @change="AllAgree"
            />
            <label for="allAgree" class="dg-input-checkbox_label dg_write_agree_all"></label>
            <label for="allAgree" class="dg-input-checkbox_text dg_write_agree_all">모두 동의합니다.</label>
            <div class="reg_check_peice">
              <div class="dg-reg_agree">
                <input
                  type="checkbox"
                  id="privacy"
                  class="dg-input-checkbox display_none"
                  v-model="buyAgree"
                  @change="AllAgreeCancel"
                />
                <label for="privacy" class="dg-input-checkbox_label dg_write_agree_use"></label>
                <label
                  for="privacy"
                  class="dg-input-checkbox_text dg_write_agree_use"
                >개인정보 제 3자 제공고지</label>
              </div>
              <div class="dg-reg_agree">
                <input
                  type="checkbox"
                  id="buyAgree"
                  class="dg-input-checkbox display_none"
                  v-model="buyAgree"
                  @change="AllAgreeCancel"
                />
                <label for="buyAgree" class="dg-input-checkbox_label dg_write_agree_pr"></label>
                <label for="buyAgree" class="dg-input-checkbox_text dg_write_agree_pr">구매진행에 동의합니다.</label>
                <a href="#" class="view_agree_desc display_none" target="_blank">보기</a>
              </div>
            </div>
          </div>
          <div class="btn_write_complete_wrap">
            <button
              type="button"
              class="dg-btn_line dg-dubble_btn btn_write_complete"
              @click="Submit('tranfer')"
            >계좌이체</button>
            <button
              type="button"
              class="dg-btn_gra dg-dubble_btn btn_write_complete"
              @click="Submit('card')"
            >카드결제</button>
          </div>
        </fieldset>
      </form>
    </section>
    <!-- content E -->
  </div>
</template>

<script>
import RegHeader from "@/components/common/reg-header.vue";

export default {
  components: {
    RegHeader
  },
  data: function() {
    return {
      allAgree: false,
      privacy: false,
      buyAgree: false,
      subscribes: [
        {
          id: "bio",
          symbolImg: "/images/img/grade/level_02_buyer_medal.png",
          title: "정기구독회원",
          body:
            "월 자동결제로 정기구독회원 회원이 되시면<br />상시결제가 가능합니다.",
          price: 5500,
          choice: false
        },
        {
          id: "special",
          symbolImg: "/images/img/grade/level_03_special_medal.png",
          title: "1달이용회원",
          body:
            "1달이용회원 회원이 되시면 결제일로부터 1개월간 구매 가능합니다.",
          price: 11000,
          choice: false
        }
      ],
      paypleCdn: null,
      paying: false
    };
  },
  beforeCreate() {
    if (this.$store.state.user.payple_billingkey) {
      this.$router.go(-1);
    }
  },
  created() {
    this.paypleCdn = document.createElement("script");
    // this.paypleCdn.setAttribute('src', 'https://testcpay.payple.kr/js/cpay.payple.1.0.1.js')
    this.paypleCdn.setAttribute(
      "src",
      "https://cpay.payple.kr/js/cpay.payple.1.0.1.js"
    );
    document.head.appendChild(this.paypleCdn);

    this.$store.commit("ProgressHide");
  },
  destroyed() {
    this.paypleCdn.parentNode.removeChild(this.paypleCdn);
  },
  methods: {
    AllAgree() {
      if (this.allAgree) {
        this.privacy = true;
        this.buyAgree = true;
      } else {
        this.privacy = false;
        this.buyAgree = false;
      }
    },
    AllAgreeCancel() {
      if (!this.privacy || !this.buyAgree) {
        this.allAgree = false;
      } else {
        this.allAgree = true;
      }
    },
    SelectItem(id) {
      this.subscribes.forEach((subscribe, index) => {
        if (subscribe.id === id) {
          this.subscribes[index].choice = true;
        } else {
          this.subscribes[index].choice = false;
        }
      });
    },
    async Validation() {
      if (!this.privacy) {
        this.WarningAlert("개인정보 제3자 제공고지를 동의하지 않으셨습니다.");
        return false;
      }

      if (!this.buyAgree) {
        this.WarningAlert("구매 진행에 동의하지 않으셨습니다.");
        return false;
      }

      return true;
    },
    async Submit(type) {
      let result = await this.Validation();

      if (result) {
        result = await this.Confirm("구매를 진행하시겠습니까?");
        if (result) {
          const beforeDate = this.$moment().subtract(1, "M");
          const isOldUser = this.$moment(beforeDate).isAfter(
            this.$store.state.user.created_at
          );

          this.subscribes.forEach(subscribe => {
            if (subscribe.choice) {
              if (subscribe.id === "bio") {
                const params = {
                  PCD_CPAY_VER: "1.0.1",
                  PCD_PAY_TYPE: type,
                  PCD_PAYER_AUTHTYPE: "pwd",
                  payple_auth_file:
                    this.$APIURI +
                    "payple/auth?referer=" +
                    window.location.protocol +
                    "//" +
                    window.location.host,
                  callbackFunction: this.GetResult,
                  PCD_PAYER_NO: this.$store.state.user.id,
                  PCD_PAYER_EMAIL: this.$store.state.user.email,
                  PCD_PAY_GOODS: subscribe.title + " 구독 결제 상품",
                  PCD_PAY_TOTAL: subscribe.price,
                  PCD_PAY_OID: this.OrderIdCreate(),
                  PCD_PAY_YEAR: this.$moment().format("YYYY"),
                  PCD_PAY_MONTH: this.$moment().format("MM"),
                  PCD_TAXSAVE_FLAG: "Y",
                  PCD_SIMPLE_FLAG: "Y"
                };

                if (type === "card") {
                  params.PCD_CARD_VER = "01";
                }

                if (isOldUser) {
                  params.PCD_PAY_WORK = "CERT";
                } else {
                  params.PCD_PAY_WORK = "AUTH";
                }

                window.PaypleCpayAuthCheck(params);
              } else {
                const params = {
                  PCD_CPAY_VER: "1.0.1",
                  PCD_PAY_TYPE: type,
                  PCD_PAYER_AUTHTYPE: "pwd",
                  payple_auth_file:
                    this.$APIURI +
                    "payple/auth?referer=" +
                    window.location.protocol +
                    "//" +
                    window.location.host +
                    "&time=" +
                    new Date().getTime(),
                  callbackFunction: this.GetResult,
                  PCD_PAYER_NO: this.$store.state.user.id,
                  PCD_PAYER_EMAIL: this.$store.state.user.email,
                  PCD_PAY_GOODS: subscribe.title + " 구독 결제 상품",
                  PCD_PAY_TOTAL: subscribe.price,
                  PCD_PAY_OID: this.OrderIdCreate(),
                  PCD_PAY_YEAR: this.$moment().format("YYYY"),
                  PCD_PAY_MONTH: this.$moment().format("MM"),
                  PCD_TAXSAVE_FLAG: "Y",
                  PCD_SIMPLE_FLAG: "Y"
                };

                if (type === "card") {
                  params.PCD_CARD_VER = "01";
                }

                if (isOldUser) {
                  params.PCD_PAY_WORK = "CERT";
                } else {
                  params.PCD_PAY_WORK = "AUTH";
                }

                window.PaypleCpayAuthCheck(params);
              }
            }
          });
        }
      }
    },
    async GetResult(response) {
      if (!this.paying) {
        console.log(response);
        this.paying = true;
        const params = response;

        this.subscribes.forEach(subscribe => {
          if (subscribe.choice) {
            params.item_name = subscribe.id;
            params.item_price = subscribe.price;
          }
        });

        try {
          this.$store.commit("ProgressShow");

          const res = (
            await this.$http.post(this.$APIURI + "payple/pay", params)
          ).data;

          if (res.state === 1) {
            const user = (
              await this.$http.get(this.$APIURI + "users/user_info")
            ).data.query;
            if (user) {
              await this.$store.commit("UserStoreInfor", user);
            }
          } else if (res.state === 24) {
            this.WarningAlert(res.msg);
          } else {
            alert(res.msg);
          }
        } catch (e) {
          console.log(e);
        } finally {
          this.paying = false;
          this.$store.commit("ProgressHide");

          if (this.$store.state.beforUrl === "/register") {
            this.$router.push({
              name: "register-style",
              query: {
                id: this.$store.state.user.id,
                name: this.$store.state.user.name
              }
            });
          } else {
            this.$router.go(-1);
          }
        }
      }
    },
    OrderIdCreate() {
      const min = Math.ceil(1000);
      const max = Math.floor(9999);
      const randomNum = String(Math.floor(Math.random() * (max - min)) + min);
      const timestamp = String(Math.floor(+new Date() / 1000));

      return randomNum + timestamp;
    }
  }
};
</script>

<style lang="scss" scoped>
.btn_write_complete_wrap {
  .btn_write_complete {
    width: 48.5%;
  }
  .dg-btn_line {
    border: 1px solid #787878;
  }
}
</style>
