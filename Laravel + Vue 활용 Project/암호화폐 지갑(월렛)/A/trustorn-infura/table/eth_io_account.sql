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
-- 테이블 구조 `eth_io_account`
--

CREATE TABLE `eth_io_account` (
  `id` int(11) NOT NULL,
  `user_id` varchar(45) DEFAULT NULL COMMENT '유저 아이디',
  `address` varchar(255) DEFAULT NULL COMMENT '주소(공개키)',
  `private` varchar(255) DEFAULT NULL COMMENT '비밀번호(개인키)',
  `created` timestamp NULL DEFAULT CURRENT_TIMESTAMP COMMENT '생성시간'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 테이블의 덤프 데이터 `eth_io_account`
--

INSERT INTO `eth_io_account` (`id`, `user_id`, `address`, `private`, `created`) VALUES
(1, 'phillips', '0x8C48F52E5161CA6deb8A25118FD02CA75a9217D0', '0x4b9421789195f85e513f06080fd1cb02694c0639d9fb7abc643bda5e6513c180', '2019-09-05 06:41:56'),
(2, 'scissorstail_1567695108gvmlM7oBFXLKz2nO289x', '0x29f9c6414c81F2B78C20007BaEA2574B941701fC', '0x51480c380bee6c662f86e4c3fc5a081da1557cf450217dbb6264182949687958', '2019-09-05 14:51:49');

--
-- 덤프된 테이블의 인덱스
--

--
-- 테이블의 인덱스 `eth_io_account`
--
ALTER TABLE `eth_io_account`
  ADD PRIMARY KEY (`id`);

--
-- 덤프된 테이블의 AUTO_INCREMENT
--

--
-- 테이블의 AUTO_INCREMENT `eth_io_account`
--
ALTER TABLE `eth_io_account`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
