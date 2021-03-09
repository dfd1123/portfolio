import Vue from "vue";

const mixin = {
  data: function () {
    return {
      storageUrl:
        process.env.VUE_APP_STORAGE_SERVER /* eslint no-undef: "off" */,
      timeCounter: 180,
      mobile_certify: "",
      sms_certify_num: "",
      resTimeData: "",
      mobileVerfied: false,
      isRequestCertify: false,
      isResponseCertify: false,
    };
  },
  methods: {
    async NotifyStore(params) {
      this.$http.post(this.$APIURI + "notification", params);
    },
    RandomCode() {
      let result = Math.floor(Math.random() * 1000000) + 100000;
      if (result > 1000000) {
        result = result - 100000;
      }

      return result.toString();
    },
    TimerStart() {
      // 1초에 한번씩 start 호출
      this.polling = setInterval(() => {
        this.timeCounter--; // 1찍 감소
        this.resTimeData = this.PrettyTime();
        if (this.timeCounter <= 0) {
          this.TimeStop();
        }
      }, 1000);
    },
    // 시간 형식으로 변환 리턴
    PrettyTime() {
      let time = this.timeCounter / 60;
      let minutes = parseInt(time);
      let secondes = Math.round((time - minutes) * 60);
      return this.Pad(minutes, 2) + ":" + this.Pad(secondes, 2);
    },
    // 2자리수로 만들어줌 09,08...
    Pad(n, width) {
      n = n + "";
      return n.length >= width
        ? n
        : new Array(width - n.length + 1).join("0") + n;
    },
    TimeStop() {
      clearInterval(this.polling);
      this.isRequestCertify = false;
      this.sms_certify_num = "";
      this.timeCounter = 180;
    }, // 재발행
    SmsReset(mobileNumber) {
      if (this.mobileVerfied) {
        this.InfoAlert("이미 인증을 완료 하셨습니다");
        return false;
      }

      clearInterval(this.polling);
      this.timeCounter = 180;
      this.ReqMobileVerify(mobileNumber);
    },
    ReqMobileVerify(mobileNumber) {
      if (mobileNumber === "") {
        this.WarningAlert("휴대폰 번호를 입력하세요!");
        return false;
      }

      this.isRequestCertify = true;

      this.sms_certify_num = this.RandomCode();
      const params = {
        mobile_number: mobileNumber,
        txt: "(주)동글 SMS 인증번호 [" + this.sms_certify_num + "]",
      };
      this.$http.get(this.$APIURI + "sms/send", { params });
      this.TimerStart();
    },
    ResMobileVerify() {
      if (this.mobile_certify === "") {
        this.WarningAlert("인증번호를 입력하세요!");
        return false;
      }

      this.isResponseCertify = true;

      if (this.mobile_certify === this.sms_certify_num) {
        this.TimeStop();
        this.mobileVerfied = true;
        this.isRequestCertify = true;
        this.sms_certify_num = "";
      }
    },
    CategoryName(caName) {
      if (caName.indexOf(">") !== -1) {
        caName = caName.split(">");
        return caName[caName.length - 1];
      }

      return caName;
    },
    Gender(gender) {
      if (gender === "man") {
        return "남자";
      } else if (gender === "woman") {
        return "여자";
      }
    },
    LevelName(level) {
      if (level === 1) {
        return "게스트";
      } else if (level === 2) {
        return "정기구독회원";
      } else if (level === 3) {
        return "1달이용회원";
      }
    },
    ConvertImage(jsonImg) {
      let imgs = [];
      if (jsonImg && jsonImg.length > 0) {
        imgs = JSON.parse(jsonImg);
      }

      return imgs;
    },
    NumberFormat(number) {
      let parts = number.toString().split(".");
      return (
        parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",") +
        (parts[1] ? "." + parts[1] : "")
      );
    },
    ChkPwd(str) {
      const regPwd = /^.*(?=.{8,20})(?=.*[0-9])(?=.*[a-zA-Z]).*$/;

      if (!regPwd.test(str)) {
        return false;
      }

      return true;
    },
    async SuccessAlert(text) {
      let resultVal = false;
      await this.$swal({
        text: text,
        type: "success",
        icon: "success",
        customClass: {
          popup: "dg-sal_popup",
          icon: "dg-sal_icon",
          title: "dg-sal_title",
          content: "dg-sal_content",
          confirmButton: "dg-sal_confirm_button",
          cancelButton: "dg-sal_cancel_button",
        },
      }).then((result) => {
        resultVal = result.value;
      });

      return resultVal;
    },
    async InfoAlert(text) {
      let resultVal = false;
      await this.$swal({
        title: text,
        type: "info",
        icon: "info",
        customClass: {
          popup: "dg-sal_popup",
          icon: "dg-sal_icon dg-sal_info_icon",
          title: "dg-sal_title",
          content: "dg-sal_content",
          confirmButton: "dg-sal_confirm_button",
          cancelButton: "dg-sal_cancel_button",
        },
      }).then((result) => {
        resultVal = result.value;
      });

      return resultVal;
    },
    async ErrorAlert(text) {
      let resultVal = false;
      await this.$swal({
        title: text,
        type: "error",
        icon: "error",
        customClass: {
          popup: "dg-sal_popup",
          icon: "dg-sal_icon dg-sal_error_icon",
          title: "dg-sal_title",
          content: "dg-sal_content",
          confirmButton: "dg-sal_confirm_button",
          cancelButton: "dg-sal_cancel_button",
        },
      }).then((result) => {
        resultVal = result.value;
      });

      return resultVal;
    },
    async WarningAlert(text) {
      let resultVal = false;
      await this.$swal({
        title: text,
        type: "warning",
        icon: "warning",
        customClass: {
          popup: "dg-sal_popup",
          icon: "dg-sal_icon dg-sal_warning_icon",
          title: "dg-sal_title",
          content: "dg-sal_content",
          confirmButton: "dg-sal_confirm_button",
          cancelButton: "dg-sal_cancel_button",
        },
      }).then((result) => {
        resultVal = result.value;
      });

      return resultVal;
    },
    async Confirm(text) {
      let resultVal = false;
      await this.$swal({
        title: text,
        type: "question",
        icon: "question",
        showCancelButton: true,
        confirmButtonText: "예",
        cancelButtonText: "아니오",
        customClass: {
          popup: "dg-sal_popup",
          icon: "dg-sal_icon dg-sal_question_icon",
          title: "dg-sal_title",
          content: "dg-sal_content",
          confirmButton: "dg-sal_confirm_button",
          cancelButton: "dg-sal_cancel_button",
        },
      }).then((result) => {
        resultVal = result.value;
      });

      return resultVal;
    },
    ForcePush(path) {
      if (this.$route.path === path) {
        this.$store.dispatch("Refresh");
      } else {
        this.$router.push(path);
      }
    },
  },
};

Vue.mixin(mixin);
