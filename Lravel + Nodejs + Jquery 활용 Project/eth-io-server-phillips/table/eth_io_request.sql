-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- 생성 시간: 19-09-08 11:30
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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
