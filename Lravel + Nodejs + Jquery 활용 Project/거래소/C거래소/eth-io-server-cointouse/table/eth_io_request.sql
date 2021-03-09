-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- 생성 시간: 19-05-18 17:52
-- 서버 버전: 10.1.38-MariaDB
-- PHP 버전: 7.3.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- 데이터베이스: `eth-io-server`
--

-- --------------------------------------------------------

--
-- 테이블 구조 `eth_io_request`
--

CREATE TABLE `eth_io_request` (
  `id` int(11) NOT NULL,
  `request_id` int(11) DEFAULT NULL COMMENT '출금 요청 id',
  `in_progress` tinyint(1) DEFAULT '0' COMMENT '현재 처리중인지 여부(0: 대기중, 1: 처리중, 2: 처리완료, 3: 처리불가능, 4: 컨펌처리됨)',
  `request_type` varchar(255) DEFAULT NULL COMMENT '요청 타입',
  `coin_kind` varchar(255) DEFAULT NULL COMMENT '코인 종류',
  `request_user_id` varchar(255) DEFAULT NULL COMMENT '요청 유저',
  `request_address` varchar(255) DEFAULT NULL COMMENT '요청 주소',
  `request_amount` decimal(21,8) DEFAULT NULL COMMENT '요청 수량',
  `request_status` varchar(255) DEFAULT NULL COMMENT '요청 상태',
  `pending_tx` varchar(255) DEFAULT NULL COMMENT '처리중인 트랜잭션',
  `confirm_tx` varchar(255) DEFAULT NULL COMMENT '컨펌 대상 트랜잭션',
  `created` datetime COMMENT '생성 시간',
  `updated` datetime COMMENT '변경 시간'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 테이블의 덤프 데이터 `eth_io_request`
--

INSERT INTO `eth_io_request` (`id`, `request_id`, `in_progress`, `request_type`, `coin_kind`, `request_user_id`, `request_address`, `request_amount`, `request_status`, `pending_tx`, `confirm_tx`, `created`, `updated`) VALUES
(60, 436, 2, 'withdraw', 'tst', 'smkim2866', '0x7d16df32607A6921B1aE85488fFF7bAf66a4F0D7', '0.01000000', 'withdraw_confirmed', NULL, '0x169506b1c3ef2112fbe193b37c7eba4a4322dd2ec7bc31ad5c76d1e770f5477a', '2019-05-18 22:37:38', '2019-05-18 22:38:08'),
(61, NULL, 2, 'deposit', 'tst', 'smkim2866', '0x7d16df32607A6921B1aE85488fFF7bAf66a4F0D7', '0.01000000', 'deposit_confirmed', NULL, '0xe527c573a71dec707806295f6bc57028705019f0ac989da1731b674710d48de1', '2019-05-18 22:38:18', '2019-05-18 22:40:09'),
(62, 437, 2, 'withdraw', 'tst', 'smkim2866', '0x7d16df32607A6921B1aE85488fFF7bAf66a4F0D7', '0.01000000', 'withdraw_confirmed', NULL, '0x6d50b1e6f02d2e49b5d2ac40b8cc6be560d476b4f2eecf7d4d361814e99fde58', '2019-05-18 22:38:23', '2019-05-18 22:40:10'),
(63, NULL, 2, 'deposit', 'tst', 'smkim2866', '0x7d16df32607A6921B1aE85488fFF7bAf66a4F0D7', '0.01000000', 'deposit_confirmed', NULL, '0xb5d551491b8eba0ea8f6c04593140d5a248dbdb5313a68817a1af4250217b5ee', '2019-05-18 22:39:51', '2019-05-18 22:42:11'),
(64, 438, 2, 'withdraw', 'tst', 'smkim2866', '0x7d16df32607A6921B1aE85488fFF7bAf66a4F0D7', '0.01000000', 'withdraw_confirmed', NULL, '0x42972c5f39eb5f9e705a0f5252bd9827813149218ca786f064e72597c50886c6', '2019-05-18 22:40:23', '2019-05-18 22:42:12'),
(65, NULL, 2, 'deposit', 'tst', 'smkim2866', '0x7d16df32607A6921B1aE85488fFF7bAf66a4F0D7', '0.01000000', 'deposit_confirmed', NULL, '0x10e9da0fdd690d14029858c802f721c544c02daf8e46ba9d2e5ad00cd335a3dd', '2019-05-18 22:40:53', '2019-05-18 22:44:13'),
(66, 436, 2, 'withdraw', 'tst', 'smkim2866', '0xA0d515260F7C20Bba17E947d7d5E4aBDC033fa18', '0.01000000', 'withdraw_confirmed', NULL, '0x3d3c05bf66d5bf70bb8fe7e5608b1b7e3ee768fceeb65a168e96be4af6cdcfcd', '2019-05-18 22:43:38', '2019-05-18 22:44:13'),
(67, 437, 2, 'withdraw', 'tst', 'smkim2866', '0xA0d515260F7C20Bba17E947d7d5E4aBDC033fa18', '0.01000000', 'withdraw_confirmed', NULL, '0xc0fd0ab19a07f3de49fa65094d97ddd342d35098c24d6efaf02393fa32401adc', '2019-05-18 22:44:23', '2019-05-18 22:45:14'),
(68, 438, 2, 'withdraw', 'tst', 'smkim2866', '0xA0d515260F7C20Bba17E947d7d5E4aBDC033fa18', '0.01000000', 'withdraw_confirmed', NULL, '0x5658a9628b58896f36dfb75119abf05933a908de899649ba70542a8f74098a47', '2019-05-18 22:45:23', '2019-05-18 22:46:14'),
(69, NULL, 2, 'deposit', 'tst', 'smkim', '0x42488a0396033D62Ed0cbf36A5ea4A33d51409DF', '0.01000000', 'deposit_confirmed', NULL, '0x1fa164df4eb6e2b418e54a8ca24b8b4738c66b4dabf307ecf749a27a6de18bb3', '2019-05-18 23:10:14', '2019-05-18 23:12:15'),
(70, NULL, 2, 'deposit', 'tst', 'test', '0x98942b9628e87128B8E484f1948006CB913B6294', '0.01000000', 'deposit_confirmed', NULL, '0x1497a0f544ef540f16afba1bfbb633266a9fcfbf7cfe497f46e863e078f06dde', '2019-05-18 23:10:46', '2019-05-18 23:15:17'),
(71, NULL, 2, 'deposit', 'tst', 'smkim2866', '0x7d16df32607A6921B1aE85488fFF7bAf66a4F0D7', '0.01000000', 'deposit_confirmed', NULL, '0xa3459b79bef0076727988f944e51fd85b40ba65c74d1d5c8b970f4ccc8533c04', '2019-05-18 23:10:46', '2019-05-18 23:15:18'),
(72, 436, 2, 'withdraw', 'tst', 'smkim2866', '0x7d16df32607A6921B1aE85488fFF7bAf66a4F0D7', '0.01000000', 'withdraw_confirmed', NULL, '0x5f7ace79e8f8f10575fa86c011380e3e7251a301aa70cd66efe3c55eb33d2733', '2019-05-19 00:40:10', '2019-05-19 00:40:56'),
(73, NULL, 2, 'deposit', 'tst', 'smkim2866', '0x7d16df32607A6921B1aE85488fFF7bAf66a4F0D7', '0.01000000', 'deposit_confirmed', NULL, '0x3f43e4610695cb8a0c915ea28e120c994d3bb28cc18304b3909b273953aab420', '2019-05-19 00:40:31', '2019-05-19 00:42:57');

--
-- 덤프된 테이블의 인덱스
--

--
-- 테이블의 인덱스 `eth_io_request`
--
ALTER TABLE `eth_io_request`
  ADD PRIMARY KEY (`id`);

--
-- 덤프된 테이블의 AUTO_INCREMENT
--

--
-- 테이블의 AUTO_INCREMENT `eth_io_request`
--
ALTER TABLE `eth_io_request`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=74;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
