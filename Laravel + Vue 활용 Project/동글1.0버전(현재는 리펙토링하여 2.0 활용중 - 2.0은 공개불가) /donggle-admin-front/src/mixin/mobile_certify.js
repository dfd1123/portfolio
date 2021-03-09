/*
timeCounter: 180,
mobile_certify: '',
sms_certify_num: '',
resTimeData: '',
mobileVerfied: false,
isRequestCertify: false,
isResponseCertify: false
*/
const mobile = {
  RandomCode () {
    let result = Math.floor(Math.random() * 1000000) + 100000
    if (result > 1000000) {
      result = result - 100000
    }

    return result.toString()
  },
  TimerStart () { // 1초에 한번씩 start 호출
    this.polling = setInterval(() => {
      this.timeCounter-- // 1찍 감소
      this.resTimeData = this.PrettyTime()
      if (this.timeCounter <= 0) {
        this.TimeStop()
      }
    }, 1000)
  },
  // 시간 형식으로 변환 리턴
  PrettyTime () {
    const time = this.timeCounter / 60
    const minutes = parseInt(time)
    const secondes = Math.round((time - minutes) * 60)
    return this.Pad(minutes, 2) + ':' + this.Pad(secondes, 2)
  },
  // 2자리수로 만들어줌 09,08...
  Pad (n, width) {
    n = n + ''
    return n.length >= width ? n : new Array(width - n.length + 1).join('0') + n
  },
  TimeStop () {
    clearInterval(this.polling)
    this.isRequestCertify = false
    this.sms_certify_num = ''
    this.timeCounter = 180
  }, // 재발행
  SmsReset (mobileNumber) {
    if (this.mobileVerfied) {
      alert('이미 인증을 완료 하셨습니다')
      return false
    }

    clearInterval(this.polling)
    this.timeCounter = 180
    this.ReqMobileVerify(mobileNumber)
  },
  ReqMobileVerify (mobileNumber) {
    if (mobileNumber === '') {
      alert('휴대폰 번호를 입력하세요!')
      return false
    }

    this.isRequestCertify = true

    this.sms_certify_num = this.RandomCode()
    const params = {
      mobile_number: mobileNumber,
      txt: '(주)동글 SMS 인증번호 [' + this.sms_certify_num + ']'
    }
    this.$axios.get('/api/sms/send', { params })
    this.TimerStart()
  },
  ResMobileVerify () {
    if (this.mobile_certify === '') {
      alert('인증번호를 입력하세요!')
      return false
    }

    this.isResponseCertify = true

    if (this.mobile_certify === this.sms_certify_num) {
      this.TimeStop()
      this.mobileVerfied = true
      this.isRequestCertify = true
      this.sms_certify_num = ''
    }
  },
  ChkPwd (str) {
    const regPwd = /^.*(?=.{8,20})(?=.*[0-9])(?=.*[a-zA-Z]).*$/

    if (!regPwd.test(str)) {
      return false
    }

    return true
  }
}

export default mobile
