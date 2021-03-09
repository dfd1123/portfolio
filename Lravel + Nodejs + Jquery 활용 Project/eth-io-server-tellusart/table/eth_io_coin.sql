-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- 생성 시간: 19-05-06 08:13
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
-- 테이블 구조 `eth_io_coin`
--

CREATE TABLE `eth_io_coin` (
  `id` int(11) NOT NULL,
  `coin_name` varchar(255) DEFAULT NULL COMMENT '코인 이름',
  `coin_kind` varchar(255) DEFAULT NULL COMMENT '코인 심볼(소문자)',
  `coin_type` varchar(255) DEFAULT NULL COMMENT '코인 종류(coin, token)',
  `coin_decimals` int(11) DEFAULT NULL COMMENT '코인 소숫점 자리수',
  `contract_address` varchar(255) DEFAULT NULL COMMENT '컨트랙트 주소',
  `created` timestamp NULL DEFAULT CURRENT_TIMESTAMP COMMENT '생성 시간'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 테이블의 덤프 데이터 `eth_io_coin`
--

INSERT INTO `eth_io_coin` (`id`, `coin_name`, `coin_kind`, `coin_type`, `coin_decimals`, `contract_address`, `created`) VALUES
(1, 'Ethereum', 'eth', 'coin', 18, NULL, '2019-04-23 06:00:21'),
(2, 'Test Standard Token', 'tst', 'token', 18, '0x722dd3f80bac40c951b51bdd28dd19d435762180', '2019-04-23 06:00:23');

--
-- 덤프된 테이블의 인덱스
--

--
-- 테이블의 인덱스 `eth_io_coin`
--
ALTER TABLE `eth_io_coin`
  ADD PRIMARY KEY (`id`);

--
-- 덤프된 테이블의 AUTO_INCREMENT
--

--
-- 테이블의 AUTO_INCREMENT `eth_io_coin`
--
ALTER TABLE `eth_io_coin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
