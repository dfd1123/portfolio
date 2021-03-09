const MODE = process.env.NODE_ENV; // development or production

/*
  관리자 계정 주소, 관리자 계정 키
  이 값은 서버 최초 실행 전에 입력되어야 하며 서버 실행 후에 무단으로 변경하면 시스템 오작동의 위험이 있습니다

  'balanceContract'값은 https://www.npmjs.com/package/eth-balance-checker에서 사용하는 컨트랙트 값
*/
const devOption = {
  adminAddress: '0x0384c898569070009D45DF59E9942f7aaCc78370',
  adminPrivate:
    '0x38f4c0aabb173bd07f3f3d2d108334f2fe0e1d5fbd896b29f41c206c749c49cd',
  dbUser: '',
  dbPassword: '',
};

const prodOption = {
  adminAddress: '',
  adminPrivate: '',
  dbUser: '',
  dbPassword: '',
};

module.exports = MODE === 'development' ? devOption : prodOption;
