-- phpMyAdmin SQL Dump
-- version 3.5.8.1
-- http://www.phpmyadmin.net
--
-- 主机: localhost
-- 生成日期: 2015 年 04 月 08 日 14:08
-- 服务器版本: 5.6.23
-- PHP 版本: 5.5.14

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- 数据库: `contactFriend`
--

-- --------------------------------------------------------

--
-- 表的结构 `bBlog`
--

CREATE TABLE IF NOT EXISTS `bBlog` (
  `_id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `orgId` int(11) NOT NULL,
  `content` text NOT NULL,
  `goodCount` int(11) NOT NULL,
  `commentCount` int(11) NOT NULL,
  `postTS` int(11) NOT NULL,
  `editTS` int(11) NOT NULL,
  `postUser` int(11) NOT NULL,
  PRIMARY KEY (`_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- 转存表中的数据 `bBlog`
--

INSERT INTO `bBlog` (`_id`, `title`, `orgId`, `content`, `goodCount`, `commentCount`, `postTS`, `editTS`, `postUser`) VALUES
(1, 'eee', 0, 'eeeee', 0, 0, 1428470602, 1428470602, 1);

-- --------------------------------------------------------

--
-- 表的结构 `oOrg`
--

CREATE TABLE IF NOT EXISTS `oOrg` (
  `_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(32) NOT NULL,
  `beginTS` int(11) NOT NULL,
  `commonInviteCode` varchar(32) NOT NULL,
  `supperInviteCode` varchar(32) NOT NULL,
  PRIMARY KEY (`_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `uOnlineInfo`
--

CREATE TABLE IF NOT EXISTS `uOnlineInfo` (
  `_id` varchar(32) NOT NULL,
  `onlineId` varchar(32) NOT NULL,
  `loginname` varchar(32) NOT NULL,
  `uid` int(11) NOT NULL,
  `uuid` varchar(32) NOT NULL,
  `login_ts` int(11) NOT NULL,
  `last_op` int(11) NOT NULL,
  `auth` varchar(32) NOT NULL,
  `rememberme` int(11) NOT NULL,
  PRIMARY KEY (`_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `uOnlineInfo`
--

INSERT INTO `uOnlineInfo` (`_id`, `onlineId`, `loginname`, `uid`, `uuid`, `login_ts`, `last_op`, `auth`, `rememberme`) VALUES
('55249c25c86c7', '55249c25c86c7', '', 551, '551ced9a511deedc180041a7', 1428462928, 1428462629, '37c9f4b0', 1),
('5524b6e40cf85', '5524b6e40cf85', '', 1, '1', 1428473218, 1428469476, '94cd7f94', 0);

-- --------------------------------------------------------

--
-- 表的结构 `uUser`
--

CREATE TABLE IF NOT EXISTS `uUser` (
  `_id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(32) NOT NULL,
  `phone` varchar(32) NOT NULL,
  `qq` varchar(32) NOT NULL,
  `weixin` varchar(32) NOT NULL,
  `regTS` int(11) NOT NULL,
  `typ` int(11) NOT NULL,
  `isAdmin` int(11) NOT NULL,
  `pwd` varchar(32) NOT NULL,
  `orgId` int(11) NOT NULL,
  `name` varchar(32) NOT NULL,
  `inviteCode` varchar(32) NOT NULL,
  `intro` varchar(255) NOT NULL,
  `everEdit` int(11) NOT NULL,
  PRIMARY KEY (`_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- 转存表中的数据 `uUser`
--

INSERT INTO `uUser` (`_id`, `email`, `phone`, `qq`, `weixin`, `regTS`, `typ`, `isAdmin`, `pwd`, `orgId`, `name`, `inviteCode`, `intro`, `everEdit`) VALUES
(1, '0', '15800972778', '', '', 1428469476, 0, 0, '', 0, '郭佳', 'ade09cc0', '', 0);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
