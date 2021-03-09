<template>
  <div class="ai_wrapper">
    <header-component
      leftButton="back"
      leftButtonRoute="/home"
      center="text"
      :centerText="__('wallet_buy.title')"
      rightButton="home"
    ></header-component>
    <div class="trst-container bgcolor scroll_area">
      <div class="price_info">
        <span>1 tru = <i>{{ $store.state.detail.tru_per_eth }}</i> eth</span>
      </div>
      <div class="sd_box">
        <div class="form_line buy-form-line">
          <label class="label_hr mb_3px">{{__('wallet_buy.buy_count')}}</label>
          <div class="in_form_line max_form">
            <input
              type="number"
              class="in_input"
              v-model="buyAmount"
              placeholder="0"
            />
            <span class="unit">tru</span>
            <div class="buy-control-btns">
              <input
                type="button"
                class="buy-control-btn buy-control-btn-plus"
                value="+"
                @click="buyAmountPlus()"
              >
              <input
                type="button"
                class="buy-control-btn buy-control-btn-minus"
                value="-"
                @click="buyAmountMinus()"
              >
            </div>
          </div>
          <div class="in_form_line keep_form info_line">
            <span class="keep_value_tt">{{__('wallet_send.own_amount')}}</span>
            <p
              class="keep_value_form"
              id="change_send_balance"
            >{{$store.state.selectedCoinInfo.balance}}</p>
            <span id="change_send_symbol1">{{$store.state.selectedCoinInfo.symbol}}</span>
          </div>
        </div>
      </div>
      <div class="sd_box">
        <div class="form_line">
          <label class="label_hr mb_3px">{{__('wallet_buy.buy_amount')}}</label>
          <div class="in_form_line max_form">
            <input
              type="number"
              class="in_input"
              placeholder="0"
              readonly="readonly"
              :value="BuyAmount"
            />
            <span class="unit">eth</span>
          </div>
          <div class="in_form_line keep_form info_line">
            <span class="keep_value_tt">{{__('wallet_send.own_amount')}}</span>
            <p
              class="keep_value_form"
              id="change_send_balance"
            >{{this.$store.state.ethInfo.balance}}</p>
            <span id="change_send_symbol1">{{this.$store.state.ethInfo.symbol}}</span>
          </div>
        </div>
      </div>
      <div class="ps_text">
        <label class="ps_title">{{__('wallet_buy.warning')}}</label>
        <p class="ps_p">{{__('wallet_buy.warning_1')}}</p>
      </div>
    </div>
    <footer-component
      :buttonText="__('wallet_buy.buy')"
      v-on:buttonClick="buyButtonClick"
    ></footer-component>

    <system-notice-component
      :message="systemNoticeMessage"
      :closeText="__('system.close')"
      :visible.sync="isPopupVisible"
      :closeButtonClick="popupCloseButtonClick"
    ></system-notice-component>
    <system-confirm-component
      iconSrc="/images/trst-images/icon/icon_cart.svg"
      :visible.sync="isConfirmVisible"
      v-on:yesButtonClick="showSecretKeyVerifyView"
    >
      <b>{{buyAmount}}</b>
      <u>{{__(`coin.${$store.state.selectedCoin}`)}}</u>
      <br />
      {{__('wallet_buy.confirm_buy')}}
    </system-confirm-component>

  </div>
</template>

<script>
  import HeaderComponent from "../components/common/HeaderComponent";
  import FooterComponent from "../components/common/FooterComponent";
  import SystemNoticeComponent from "../components/common/SystemNoticeComponent";
  import SystemConfirmComponent from "../components/common/SystemConfirmComponent";

  export default {
    async beforeCreate() {
      await this.$store.commit("selectedCoinInfo", (await axios.get(`/api/wallet/info/tru`)).data);
      await this.$store.commit("ethInfo", (await axios.get(`/api/wallet/info/eth`)).data);
    },
    components: {
      "header-component": HeaderComponent,
      "footer-component": FooterComponent,
      "system-notice-component": SystemNoticeComponent,
      "system-confirm-component": SystemConfirmComponent
    },
    data() {
      return {
        buyAmount: '',
        isPopupVisible: false,
        isConfirmVisible: false,
      }
    },
    async created() {
      await this.fetchData();
    },
    computed: {
      BuyAmount() {
        if (this.buyAmount === '') {
          return undefined;
        }

        let result = new Decimal(parseFloat(this.buyAmount)).mul(parseFloat(this.$store.state.detail.tru_per_eth));

        return result;
      },
      isBuyZeroOrLessAmount() {
        return this.BuyAmount <= 0;
      },
      isBuyInsufficientAmount() {
        return this.BuyAmount > this.$store.state.ethInfo.balance;
      },
      systemNoticeMessage() {
        if (this.isBuyInsufficientAmount) {
          return this.__("wallet_buy.insufficient_amount");
        }
        if (this.isBuyZeroOrLessAmount || this.BuyAmount === '' || this.BuyAmount === undefined) {
          return this.__("wallet_buy.zero_or_less");
        }

        return "";
      }
    },
    methods: {
      async fetchData() {
        try {
          this.$store.commit("progressComponentShow");

          this.$store.commit("selectedCoinHistoryTotalDuration", 14);

          const res = await axios.get(`/api/detail`);

          this.eth_info = (await axios.get(`/api/wallet/info/eth`)).data;

          this.$store.commit("detail", res.data);
        } finally {
          this.$store.commit("progressComponentHide");
        }
      },
      buyButtonClick() {
        if (
          this.isBuyInsufficientAmount ||
          this.isBuyZeroOrLessAmount ||
          this.BuyAmount === '' || this.BuyAmount === undefined
        ) {
          this.isPopupVisible = true;
        } else {
          this.isConfirmVisible = true;
        }
      },
      buyAmountPlus() {
        this.buyAmount++;
      },
      buyAmountMinus() {
        this.buyAmount--;
        if (this.buyAmount < 0) {
          this.buyAmount = 0;
          return false
        }
      },
      async sendButtonClick() {
        if (
          this.isBuyInsufficientAmount ||
          this.isBuyZeroOrLessAmount
        ) {
          this.isPopupVisible = true;
        } else {
          this.isConfirmVisible = true;
        }
      },
      showSecretKeyVerifyView() {
        this.$store.commit("updateWalletBuyStatusViewData", {
          selectedCoin: 'tru',
          buyAmount: this.buyAmount
        });

        this.$router.replace({
          name: "user_secret_key_verify",
          params: { backName: "wallet_buy_new", proceedName: "wallet_buy_status" }
        });

      },
      popupCloseButtonClick() {
        /*
        if (this.isInvalidAddressScan) {
            this.isInvalidAddressScan = false;
        }
        */
      }
    }
  };
</script>

<style scoped>
  .ai_wrapper {
    position: relative;
    height: 100%;
    padding-top: 2.815rem;
    margin-bottom: -3.315rem;
    padding-bottom: 3.315rem;
  }

  .price_info {
    background: linear-gradient(to right, rgb(100, 225, 150), rgb(25, 180, 170));
    text-align: center;
    color: white;
    padding: 10px 0;
    font-size: 1.12rem;
    font-weight: 300;
    text-transform: uppercase;
    letter-spacing: 0;
    box-shadow: 0 6px 18px rgba(195, 215, 215, 0.8);
    margin-bottom: 45px;
  }

  .price_info i {
    font-style: normal;
  }

  .max_form {
    width: 100%;
    position: relative;
  }

  .buy-form-line .max_form .unit {
    right: 60px;
  }

  .buy-form-line .buy-control-btns {
    position: absolute;
    bottom: 8px;
    right: 0;
    width: 55px;
    height: 28px;
    border: 1px solid #dcdcdc;
  }

  .buy-form-line .buy-control-btn {
    width: 50%;
    height: 100%;
    border: 0;
    padding: 0;
    float: left;
    background-color: white;
    border-right: 1px solid #dcdcdc;
    color: transparent;
  }

  .buy-form-line .buy-control-btn:active {
    background-color: #f5f5f5;
  }

  .buy-form-line .buy-control-btn-plus {
    background-image: url(/images/trst-images/btn/btn_plus.svg);
    background-position: center;
    background-repeat: no-repeat;
  }

  .buy-form-line .buy-control-btn-minus {
    background-image: url(/images/trst-images/btn/btn_minus.svg);
    background-position: center;
    background-repeat: no-repeat;
  }

  .buy-form-line .buy-control-btn:last-child {
    border-right: 0;
  }

  .trst-container .sd_box .form_line .max_form .in_input {
    text-align: left;
    width: 100%;
    border: 0;
    font-size: 1.45rem;
    padding-right: 3.5rem;
    height: 3rem;
    border-bottom: 1px solid #dcdedc;
    color: #505050;
    border-radius: 0;
  }

  .trst-container .sd_box .buy-form-line .max_form .in_input {
    padding-right: calc(60px + 3.5rem);
  }

  .max_form .in_input::placeholder {
    color: #bec8c8;
  }

  .max_form .unit {
    text-transform: uppercase;
    color: #19b4aa;
    font-size: 1.45rem;
    font-weight: 400;
    height: 30px;
    line-height: 30px;
    position: absolute;
    right: 0;
    padding: 0 5px;
    top: 50%;
    transform: translateY(-50%);
  }

  .keep_form {
    width: 100%;
    margin: 0;
    font-size: 12.5px;
    color: #505050;
    font-weight: 300;
    text-align: left;
    padding: 8px 0;
  }

  .keep_form .keep_value_form {
    display: inline-block;
    letter-spacing: 0;
    padding: 0 2px 0 5px;
  }

  .keep_form > span:last-child {
    letter-spacing: 0;
  }
</style>
