-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- 생성 시간: 19-05-06 08:15
-- 서버 버전: 10.1.38-MariaDB
-- PHP 버전: 7.3.2

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- 데이터베이스: `sharebits_io`
--

-- --------------------------------------------------------

--
-- 테이블 구조 `eth_io_request`
--

CREATE TABLE `eth_io_request` (
  `id` int(11) NOT NULL,
  `in_progress` tinyint(1) DEFAULT '0' COMMENT '현재 처리중인지 여부(0: 대기중, 1: 처리중, 2: 처리완료, 3: 처리불가능)',
  `request_type` varchar(255) DEFAULT NULL COMMENT '요청 타입',
  `coin_kind` varchar(255) DEFAULT NULL COMMENT '코인 종류',
  `request_user_id` varchar(255) DEFAULT NULL COMMENT '요청 유저',
  `request_address` varchar(255) DEFAULT NULL COMMENT '요청 주소',
  `request_amount` decimal(21,8) DEFAULT NULL COMMENT '요청 수량',
  `request_status` varchar(255) DEFAULT NULL COMMENT '요청 상태',
  `pending_tx` varchar(255) DEFAULT NULL COMMENT '처리중인 트랜잭션',
  `confirm_tx` varchar(255) DEFAULT NULL COMMENT '컨펌 대상 트랜잭션',
  `created` timestamp NULL DEFAULT CURRENT_TIMESTAMP COMMENT '생성 시간',
  `updated` timestamp NULL DEFAULT NULL COMMENT '변경 시간'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 테이블의 덤프 데이터 `eth_io_request`
--

INSERT INTO `eth_io_request` (`id`, `in_progress`, `request_type`, `coin_kind`, `request_user_id`, `request_address`, `request_amount`, `request_status`, `pending_tx`, `confirm_tx`, `created`, `updated`) VALUES
(1, 2, 'withdraw', 'eth', 'smkim', '0x0ff3bC56AE9D1bf322E26425543eAa586a34F6c2', '0.05000000', 'withdraw_completed', NULL, NULL, '2019-04-23 05:45:41', '2019-04-26 11:19:15'),
(2, 2, 'withdraw', 'tst', 'smkim', '0x0ff3bC56AE9D1bf322E26425543eAa586a34F6c2', '0.10000000', 'withdraw_completed', NULL, NULL, '2019-04-23 05:45:41', '2019-04-26 11:20:24'),
(32, 2, 'deposit', 'eth', 'admin', '0x98942b9628e87128B8E484f1948006CB913B6294', '0.05000000', 'deposit_confirmed', NULL, '0x249f140fc3771d73324ca0b071be6942a3d45d874ed4fa497e9d920854399edf', '2019-04-26 11:22:15', '2019-04-26 11:23:25'),
(34, 2, 'deposit', 'tst', 'admin', '0x98942b9628e87128B8E484f1948006CB913B6294', '0.10000000', 'deposit_confirmed', NULL, '0xfcf646960904d71cff9c61fc84161f55999ab2bf315d917db3ab6bea1fa8ef0f', '2019-04-26 11:23:47', '2019-04-26 11:25:57'),
(35, 2, 'deposit', 'tst', 'admin', '0x42488a0396033D62Ed0cbf36A5ea4A33d51409DF', '0.10000000', 'deposit_confirmed', NULL, '0x8280ee2938bb3d435048056e7a8dd18bf889db9de3085b4007f9931da4836432', '2019-04-29 04:23:44', '2019-04-29 04:26:21');

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
