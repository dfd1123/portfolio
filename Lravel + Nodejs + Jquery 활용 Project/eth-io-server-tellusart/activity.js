/* eslint-disable no-await-in-loop */
/* eslint-disable no-restricted-syntax */
const Decimal = require('decimal.js');

const call = require('./call');
const tx = require('./tx');
const admin = require('./admin');

const minimumEthAmountToDeposit = '0.01'; // 이더 입금 최소액수, 소수점 8자리 이하는 DB에 값 입력 시 무시됨. 값이 너무 작으면 수수료 에러
const minimumTokenAmountToDeposit = '0.00000001'; // 토큰 입금 최소액수, 소수점 8자리 이하는 DB에 값 입력 시 무시됨
const etherForTokenDeposit = '0.005'; // 토큰 입금을 위해 전송과정에 보낼 이더 값
const enableTryState = false; // infura와 통신 중 에러 시 중지

async function processWithdrawEthRequest(withdrawRequest) {
  try {
    switch (withdrawRequest.request_status) {
      case 'withdraw_requested': {
        console.log(
          `출금요청 추가 -> 출금종류: ${withdrawRequest.coin_kind}, 출금유저: ${
            withdrawRequest.request_user_id
          }, 출금주소: ${withdrawRequest.request_address}, 출금액수: ${
            withdrawRequest.request_amount
          }`,
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
}

async function processWithdrawTokenRequest(withdrawRequest) {
  try {
    switch (withdrawRequest.request_status) {
      case 'withdraw_requested': {
        console.log(
          `출금요청 추가 -> 출금종류: ${withdrawRequest.coin_kind}, 출금유저: ${
            withdrawRequest.request_user_id
          }, 출금주소: ${withdrawRequest.request_address}, 출금액수: ${
            withdrawRequest.request_amount
          }`,
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
}

async function processDepositEthRequest(depositRequest) {
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
}

async function processDepositTokenRequest(depositRequest) {
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
}

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

  async processWithdrawRequest(withdrawRequest) {
    try {
      if (
        withdrawRequest.coin_type === 'coin'
        && withdrawRequest.coin_kind === 'eth'
      ) {
        // 이더 출금
        return await processWithdrawEthRequest(withdrawRequest);
      }

      // 토큰 출금
      return await processWithdrawTokenRequest(withdrawRequest);
    } catch (err) {
      throw err;
    }
  },

  async processDepositRequest(depositRequest) {
    try {
      if (
        depositRequest.coin_type === 'coin'
        && depositRequest.coin_kind === 'eth'
      ) {
        // 이더 입금
        return await processDepositEthRequest(depositRequest);
      }

      // 토큰 출금
      return await processDepositTokenRequest(depositRequest);
    } catch (err) {
      throw err;
    }
  },

  async scanNewDeposit() {
    try {
      // 대기중이나 진행중인 작업이 없는 주소만 조회
      const addressInfos = await call.getNotInProgressOrFailAddressInfos();
      if (addressInfos.length === 0) {
        return;
      }

      // 토큰 정보 조회
      const tokenInfos = await call.getTokenInfos();
      if (tokenInfos.length === 0) {
        return;
      }

      // 토큰 컨트랙트 주소
      const tokens = tokenInfos.map(tokenInfo => tokenInfo.contract_address);

      for (const addressInfo of addressInfos) {
        // 해당 주소의 이더 잔고 조회
        const ethBalance = await tx.getAddressEthBalance(addressInfo.address);
        const ethDecimal = 18;
        const ethAmount = new Decimal(ethBalance).div(10 ** ethDecimal);

        if (
          !ethAmount.isZero()
          && ethAmount.comparedTo(minimumEthAmountToDeposit) !== -1
        ) {
          // 입금가능한 이더가 있으면 입금처리
          const coinKind = 'eth';
          const requestUserId = addressInfo.user_id;
          const requestAddress = addressInfo.address;
          const requestAmount = ethAmount.toFixed(8); // 소수점 8자리 까지만 DB에 저장

          await call.setNewDepositRequest({
            coinKind,
            requestUserId,
            requestAddress,
            requestAmount,
          });

          console.log(
            `입금요청 추가 -> 입금종류: ${coinKind}, 입금유저: ${requestUserId}, 입금주소: ${requestAddress}, 입금액수: ${requestAmount}`,
          );
        } else {
          // 해당 주소에 입금 가능한 이더가 없으면 알려진 토큰 잔고 모두 조회
          const tokenBalances = await tx.getAddressTokenBalances(
            addressInfo.address,
            tokens,
          );

          // 입금가능한 토큰이 있으면 가장 먼저 발견된 토큰부터 입금처리
          for (const [contract, value] of Object.entries(tokenBalances)) {
            const token = tokenInfos.find(
              tokenInfo => tokenInfo.contract_address === contract,
            );
            const decimal = token.coin_decimals;
            const amount = new Decimal(value).div(10 ** decimal);

            if (
              !amount.isZero()
              && amount.comparedTo(minimumTokenAmountToDeposit) !== -1
            ) {
              // 입금 처리 목록에 추가
              const coinKind = token.coin_kind;
              const requestUserId = addressInfo.user_id;
              const requestAddress = addressInfo.address;
              const requestAmount = amount.toFixed(8); // 소수점 8자리 까지만 DB에 저장

              await call.setNewDepositRequest({
                coinKind,
                requestUserId,
                requestAddress,
                requestAmount,
              });

              console.log(
                `입금요청 추가 -> 입금종류: ${coinKind}, 입금유저: ${requestUserId}, 입금주소: ${requestAddress}, 입금액수: ${requestAmount}`,
              );

              // 한 주소에 여러 입금 처리 동시처리 불가
              break;
            }
          }
        }
      }
    } catch (err) {
      throw err;
    }
  },
};
