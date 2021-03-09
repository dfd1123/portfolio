-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- 생성 시간: 19-09-11 09:17
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
-- 데이터베이스: `eth_io_server`
--

-- --------------------------------------------------------

--
-- 테이블 구조 `eth_io_request`
--

CREATE TABLE `eth_io_request` (
  `id` int(11) NOT NULL,
  `request_id` int(11) DEFAULT NULL COMMENT '출금 요청 id (외부 요청시)',
  `in_progress` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0: 대기중, 1: 처리중, 2: 처리완료, 3: 처리불가능',
  `request_type` varchar(255) NOT NULL COMMENT '요청종류 (deposit, withdraw, withdraw_from_user(none central)))',
  `coin_kind` varchar(255) NOT NULL COMMENT '코인 종류(coin, token)	',
  `request_user_id` varchar(255) NOT NULL COMMENT '요청 유저',
  `request_address` varchar(255) NOT NULL COMMENT '요청 주소',
  `request_amount` decimal(21,8) NOT NULL DEFAULT '0.00000000' COMMENT '요청 수량(소수점 8자리까지)',
  `request_status` varchar(255) NOT NULL COMMENT '요청 상태 (deposit_requested, withdraw_requested, etc...)',
  `pending_tx` varchar(255) DEFAULT NULL COMMENT '처리중인 트랜잭션',
  `confirm_tx` varchar(255) DEFAULT NULL COMMENT '컨펌 대상 트랜잭션',
  `created` timestamp NULL DEFAULT CURRENT_TIMESTAMP COMMENT '생성 시간',
  `updated` timestamp NULL DEFAULT CURRENT_TIMESTAMP COMMENT '변경 시간'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
