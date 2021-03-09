const bankList = Object.freeze({
  '001': '한국은행',
  '002': '산업은행',
  '003': '기업은행',
  '004': '국민은행',
  '007': '수협은행',
  '008': '수출입은행',
  '011': '농협은행',
  '012': '지역농-축협',
  '020': '우리은행',
  '023': 'SC제일은행',
  '027': '한국씨티은행',
  '031': '대구은행',
  '032': '부산은행',
  '034': '광주은행',
  '035': '제주은행',
  '037': '전북은행',
  '039': '경남은행',
  '045': '새마을금고연합회',
  '048': '신협',
  '050': '저축은행',
  '052': '모건스탠리은행',
  '054': 'HSBC은행',
  '055': '도이치은행',
  '057': '제이피모간체이스은행',
  '058': '미즈호은행',
  '059': '엠유에프지은행',
  '060': 'BOA',
  '061': '비엔피파리바은행',
  '062': '중국공상은행',
  '063': '중국은행',
  '064': '산림조합중앙회',
  '065': '대화은행',
  '066': '교통은행',
  '067': '중국건설은행',
  '071': '우체국',
  '076': '신용보증기금',
  '077': '기술보증기금',
  '081': 'KEB하나은행',
  '088': '신한은행',
  '089': '케이뱅크',
  '090': '카카오뱅크',
  101: '한국신용정보원',
  102: '대신저축은행',
  103: '에스비아이저축은행',
  104: '에이치케이저축은행',
  105: '웰컴저축은행',
  106: '신한저축은행'
})

const settleCaseList = Object.freeze({
  CARD: '신용카드',
  BANK: '계좌이체',
  VBANK: '가상계좌',
  CELLPHONE: '휴대폰결제',
  DEPOSIT: '테스트'
})

const obStatusList = Object.freeze({
  deposit_wait: '입금대기', // 주문접수
  order_apply: '입금확인',
  order_cancel: '주문취소',
  refund_apply: '환불신청',
  refund_reject: '환불거절',
  refund_complete: '환불승인',
  delivery_wait: '배송대기',
  shipping: '배송중',
  delivery_complete: '배송완료',
  order_complete: '주문완료'
})

const constants = Object.freeze({
  bankList,
  obStatusList,
  settleCaseList
})

// import { property } from '@/constants'
export {
  bankList,
  obStatusList,
  settleCaseList
}

// import constants from '@/constants'
export default {
  constants
}
