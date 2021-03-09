/* eslint-disable no-await-in-loop */
/* eslint-disable no-restricted-syntax */
const Decimal = require('decimal.js');

const call = require('./call');
const tx = require('./tx');
const admin = require('./admin');

const etherForTokenDeposit = '0.005'; // 토큰 입금을 위해 전송과정에 보낼 이더 값
const enableTryState = false; // infura와 통신 중 에러 시 중지

module.exports = {
  async checkoutCurrentRequest() {
    // 진행중인 요청 가져오기
    try {
      const inProgressRequest = await call.getInProgressRequest();
      if (inProgressRequest === null) {
        // 진행중인 요청이 없으면 다음 요청 가져오기
        const nextRequest = await call.getNextRequest();
        if (nextRequest === null) {
          // 대기중인 요청 없음
          return null;
        }

        // 해당 요청을 진행중인 상태로 설정
        await call.updateRequestValue({
          id: nextRequest.id,
          inProgress: '1',
        });

        return nextRequest;
      }

      return inProgressRequest;
    } catch (err) {
      throw err;
    }
  },

  async processWithdrawEthRequest(withdrawRequest) {
    try {
      switch (withdrawRequest.request_status) {
        case 'withdraw_requested': {
          console.log(
            `출금요청 추가 -> 출금종류: ${
              withdrawRequest.coin_kind
            }, 출금유저: ${withdrawRequest.request_user_id}, 출금주소: ${
              withdrawRequest.request_address
            }, 출금액수: ${withdrawRequest.request_amount}`,
          );

          if (enableTryState) {
            console.info('이더 출금 요청 시작 처리');
            await call.updateRequestValue({
              id: withdrawRequest.id,
              requestStatus: 'withdraw_eth_request_try',
            });
          }

          console.info('관리자 주소에서 요청 주소로 출금 처리');
          const txHash = await tx.sendEthTransaction(
            admin.adminAddress,
            withdrawRequest.request_address,
            withdrawRequest.request_amount,
            admin.adminPrivate,
          );

          console.info('요청 데이터에 트랜잭션 해시 기록');
          console.info(txHash);
          await call.updateRequestValue({
            id: withdrawRequest.id,
            pendingTx: txHash,
            confirmTx: txHash, // 기록
            requestStatus: 'withdraw_eth_request_sent',
          });

          return true;
        }

        case 'withdraw_eth_request_sent': {
          // console.info('이더 출금 요청 처리 후 컨펌 대기');
          const result = await tx.getTransactionReceipt(
            withdrawRequest.pending_tx,
          );
          if (result === null) {
            // console.info('아직 채굴되지 않음');
            return false;
          }

          if (result.status === true) {
            console.info('성공적으로 채굴되고 실행됨');
            // console.log(result);
            await call.updateRequestValue({
              id: withdrawRequest.id,
              inProgress: '2',
              pendingTx: null,
              requestStatus: 'withdraw_completed',
            });
          }

          return true;
        }

        default:
          console.log(`잘못된 상태 진입: ${withdrawRequest.request_status}`);
          return false;
      }
    } catch (err) {
      throw err;
    }
  },

  async processWithdrawTokenRequest(withdrawRequest) {
    try {
      switch (withdrawRequest.request_status) {
        case 'withdraw_requested': {
          console.log(
            `출금요청 추가 -> 출금종류: ${
              withdrawRequest.coin_kind
            }, 출금유저: ${withdrawRequest.request_user_id}, 출금주소: ${
              withdrawRequest.request_address
            }, 출금액수: ${withdrawRequest.request_amount}`,
          );

          if (enableTryState) {
            console.info('토큰 출금 요청 시작 처리');
            await call.updateRequestValue({
              id: withdrawRequest.id,
              requestStatus: 'withdraw_token_request_try',
            });
          }

          console.info('관리자 주소에서 요청 주소로 출금 처리');
          const txHash = await tx.sendErcTransaction(
            admin.adminAddress,
            withdrawRequest.request_address,
            withdrawRequest.request_amount,
            admin.adminPrivate,
            withdrawRequest.contract_address,
            withdrawRequest.coin_decimals,
          );

          console.info('요청 데이터에 트랜잭션 해시 기록');
          console.info(txHash);
          await call.updateRequestValue({
            id: withdrawRequest.id,
            pendingTx: txHash,
            confirmTx: txHash, // 기록
            requestStatus: 'withdraw_token_request_sent',
          });

          return true;
        }

        case 'withdraw_token_request_sent': {
          // console.info('토큰 출금 요청 처리 후 컨펌 대기');
          const result = await tx.getTransactionReceipt(
            withdrawRequest.pending_tx,
          );
          if (result === null) {
            // console.info('아직 채굴되지 않음');
            return false;
          }

          if (result.status === true) {
            console.info('성공적으로 채굴되고 실행됨');
            // console.log(result);
            await call.updateRequestValue({
              id: withdrawRequest.id,
              inProgress: '2',
              pendingTx: null,
              requestStatus: 'withdraw_completed',
            });
          }

          return true;
        }

        default:
          console.log(`잘못된 상태 진입: ${withdrawRequest.request_status}`);
          return false;
      }
    } catch (err) {
      throw err;
    }
  },

  async processDepositEthRequest(depositRequest) {
    try {
      switch (depositRequest.request_status) {
        case 'deposit_requested': {
          // 요청 주소의 키 값 가져오기
          const depositAccount = await call.getAccountFromAddress({
            address: depositRequest.request_address,
          });

          if (depositAccount === null) {
            console.info('알 수 없는 주소에서 입금 처리 불가');
            await call.updateRequestValue({
              id: depositRequest.id,
              inProgress: '3',
              pendingTx: null,
              requestStatus: 'deposit_address_check_fail',
            });
            return false;
          }

          if (enableTryState) {
            console.info('이더 입금 요청 시작 처리');
            await call.updateRequestValue({
              id: depositRequest.id,
              requestStatus: 'deposit_eth_request_try',
            });
          }

          console.info('요청 주소에서 관리자 주소로 출금 처리 (수수료 포함)');
          const txHash = await tx.sendEthTransaction(
            depositRequest.request_address,
            admin.adminAddress,
            depositRequest.request_amount,
            depositAccount.private,
            true,
          );

          if (txHash === null) {
            console.info('이더 수수료 잔액 부족 (실패 처리)');
            await call.updateRequestValue({
              id: depositRequest.id,
              inProgress: '3',
              pendingTx: null,
              requestStatus: 'deposit_fee_not_enough',
            });
            return false;
          }

          console.info('요청 데이터에 트랜잭션 해시 기록');
          console.info(txHash);
          await call.updateRequestValue({
            id: depositRequest.id,
            pendingTx: txHash,
            confirmTx: txHash,
            requestStatus: 'deposit_eth_request_sent',
          });

          return true;
        }

        case 'deposit_eth_request_sent': {
          // console.info('이더 입금 요청 처리 후 컨펌 대기');
          const result = await tx.getTransactionReceipt(
            depositRequest.pending_tx,
          );
          if (result === null) {
            // console.info('아직 채굴되지 않음');
            return false;
          }

          if (result.status === true) {
            console.info('성공적으로 채굴되고 실행됨');
            // console.log(result);
            await call.updateRequestValue({
              id: depositRequest.id,
              inProgress: '2',
              pendingTx: null,
              requestStatus: 'deposit_completed',
            });
          }

          return true;
        }

        default:
          console.log(`잘못된 상태 진입: ${depositRequest.request_status}`);
          return false;
      }
    } catch (err) {
      throw err;
    }
  },

  async processDepositTokenRequest(depositRequest) {
    try {
      switch (depositRequest.request_status) {
        case 'deposit_requested': {
          // 요청 주소의 키 값 가져오기
          const depositAccount = await call.getAccountFromAddress({
            address: depositRequest.request_address,
          });

          if (depositAccount === null) {
            console.info('알 수 없는 주소에서 입금 처리 불가');
            await call.updateRequestValue({
              id: depositRequest.id,
              inProgress: '3',
              pendingTx: null,
              requestStatus: 'deposit_address_check_fail',
            });
            return false;
          }

          if (enableTryState) {
            console.info('토큰 입금 요청 시작 처리');
            await call.updateRequestValue({
              id: depositRequest.id,
              requestStatus: 'deposit_token_request_set_eth_try',
            });
          }

          console.info('토큰 수수료용 이더리움 입금');
          const txHash = await tx.sendEthTransaction(
            admin.adminAddress,
            depositRequest.request_address,
            etherForTokenDeposit,
            admin.adminPrivate,
          );

          console.info('요청 데이터에 트랜잭션 해시 기록');
          console.info(txHash);
          await call.updateRequestValue({
            id: depositRequest.id,
            pendingTx: txHash,
            requestStatus: 'deposit_token_request_set_eth_sent',
          });

          return true;
        }

        case 'deposit_token_request_set_eth_sent': {
          // console.info('토큰 수수료용 이더리움 입금 요청 처리 후 컨펌 대기');
          const result = await tx.getTransactionReceipt(
            depositRequest.pending_tx,
          );
          if (result === null) {
            // console.info('아직 채굴되지 않음');
            return false;
          }

          if (result.status === true) {
            console.info('성공적으로 채굴되고 실행됨');
            // console.log(result);
            await call.updateRequestValue({
              id: depositRequest.id,
              pendingTx: null,
              requestStatus: 'deposit_token_request_get_token',
            });
          }

          return true;
        }

        case 'deposit_token_request_get_token': {
          // 요청 주소의 키 값 가져오기
          const depositAccount = await call.getAccountFromAddress({
            address: depositRequest.request_address,
          });

          if (depositAccount === null) {
            console.info('알 수 없는 주소에서 입금 처리 불가');
            await call.updateRequestValue({
              id: depositRequest.id,
              inProgress: '3',
              pendingTx: null,
              requestStatus: 'deposit_address_check_fail2',
            });
            return false;
          }

          if (enableTryState) {
            console.info('토큰 수수료용 이더 입금 후 토큰 입금 요청 시작 처리');
            await call.updateRequestValue({
              id: depositRequest.id,
              requestStatus: 'deposit_token_request_get_token_try',
            });
          }

          console.info('요청 주소에서 관리자 주소로 토큰 입금 처리');
          const txHash = await tx.sendErcTransaction(
            depositRequest.request_address,
            admin.adminAddress,
            depositRequest.request_amount,
            depositAccount.private,
            depositRequest.contract_address,
            depositRequest.coin_decimals,
          );

          console.info('요청 데이터에 트랜잭션 해시 기록');
          console.info(txHash);
          await call.updateRequestValue({
            id: depositRequest.id,
            pendingTx: txHash,
            confirmTx: txHash,
            requestStatus: 'deposit_token_request_get_token_sent',
          });

          return true;
        }

        case 'deposit_token_request_get_token_sent': {
          // console.info('토큰 입금 요청 처리 후 컨펌 대기');
          const result = await tx.getTransactionReceipt(
            depositRequest.pending_tx,
          );
          if (result === null) {
            // console.info('아직 채굴되지 않음');
            return false;
          }

          if (result.status === true) {
            console.info(
              '성공적으로 채굴되고 실행됨. 다음 단계에서 사용하기 위해 트랜잭션 해쉬 값 유지',
            );
            // console.log(result);
            await call.updateRequestValue({
              id: depositRequest.id,
              requestStatus: 'deposit_token_request_get_eth',
            });
          }

          return true;
        }

        case 'deposit_token_request_get_eth': {
          // 요청 주소의 키 값 가져오기
          const depositAccount = await call.getAccountFromAddress({
            address: depositRequest.request_address,
          });

          if (depositAccount === null) {
            console.info('알 수 없는 주소에서 입금 처리 불가');
            await call.updateRequestValue({
              id: depositRequest.id,
              inProgress: '3',
              pendingTx: null,
              requestStatus: 'deposit_address_check_fail3',
            });
            return false;
          }

          // 사용하고 남은 이더 값 계산을 위해 트랜잭션 가져오기
          const tokenTransaction = await tx.getTransaction(
            depositRequest.pending_tx,
          );

          // 사용하고 남은 이더 값 계산을 위해 트랜잭션 영수증 가져오기
          const tokenTransactionReceipt = await tx.getTransactionReceipt(
            depositRequest.pending_tx,
          );

          // 사용하고 남은 이더 값 계산
          const { gasPrice } = tokenTransaction; // wei 단위
          const { gasUsed } = tokenTransactionReceipt;
          const decimal = 18; // ether 자리수
          const remainEtherAmount = new Decimal(etherForTokenDeposit)
            .minus(Decimal.mul(gasPrice, gasUsed).div(10 ** decimal))
            .toFixed(decimal);

          if (enableTryState) {
            console.info('토큰 입금 후 잔여 이더 회수 요청 시작 처리');
            await call.updateRequestValue({
              id: depositRequest.id,
              requestStatus: 'deposit_token_request_get_eth_try',
            });
          }

          console.info(
            '요청 주소에서 관리자 주소로 이더 입금 처리 (수수료 포함)',
          );
          const txHash = await tx.sendEthTransaction(
            depositRequest.request_address,
            admin.adminAddress,
            remainEtherAmount,
            depositAccount.private,
            true,
          );

          if (txHash === null) {
            console.info('이더 수수료 잔액 부족 (실패 처리)');
            await call.updateRequestValue({
              id: depositRequest.id,
              inProgress: '3',
              pendingTx: null,
              requestStatus: 'deposit_fee_not_enough',
            });
            return false;
          }

          console.info('요청 데이터에 트랜잭션 해시 기록');
          console.info(txHash);
          await call.updateRequestValue({
            id: depositRequest.id,
            pendingTx: txHash,
            requestStatus: 'deposit_token_request_get_eth_sent',
          });

          return true;
        }

        case 'deposit_token_request_get_eth_sent': {
          // console.info('잔여 이더 회수 요청 처리 후 컨펌 대기');
          const result = await tx.getTransactionReceipt(
            depositRequest.pending_tx,
          );
          if (result === null) {
            // console.info('아직 채굴되지 않음');
            return false;
          }

          if (result.status === true) {
            console.info('성공적으로 채굴되고 실행됨');
            // console.log(result);
            await call.updateRequestValue({
              id: depositRequest.id,
              inProgress: '2',
              pendingTx: null,
              requestStatus: 'deposit_completed',
            });
          }

          return true;
        }

        default:
          console.log(`잘못된 상태 진입: ${depositRequest.request_status}`);
          return false;
      }
    } catch (err) {
      throw err;
    }
  },
};
