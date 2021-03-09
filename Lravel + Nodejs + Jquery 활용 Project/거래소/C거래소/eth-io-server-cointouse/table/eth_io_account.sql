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
(1, 'smkim', '0x42488a0396033D62Ed0cbf36A5ea4A33d51409DF', '0x0700b5f49274bd0d768a34199744e8bd49a758207162226d6acd970f6c0316ad', '2019-04-23 05:17:15'),
(10, 'test', '0x98942b9628e87128B8E484f1948006CB913B6294', '0xa7b93911cdec91883894f6ef4181d08a55ea50ef3eddf8c20d2df3f0bcd29db6', '2019-04-25 12:35:12');

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
