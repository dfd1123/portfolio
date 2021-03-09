require('dotenv').config();

/*
  관리자 계정 주소, 관리자 계정 키
  이 값은 서버 최초 실행 전에 입력되어야 하며 서버 실행 후에 무단으로 변경하면 시스템 오작동의 위험이 있습니다
*/

module.exports = {
  adminAddress: process.env.ADMIN_ADDRESS,
  adminPrivate: process.env.ADMIN_PRIVATE,
};
