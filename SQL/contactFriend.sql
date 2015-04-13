-- phpMyAdmin SQL Dump
-- version 3.5.8.1
-- http://www.phpmyadmin.net
--
-- 主机: localhost
-- 生成日期: 2015 年 04 月 13 日 14:30
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
(1, 'eee', 0, 'eeeee', 7, 4, 1428470602, 1428470602, 1);

-- --------------------------------------------------------

--
-- 表的结构 `bBlogComment`
--

CREATE TABLE IF NOT EXISTS `bBlogComment` (
  `_id` int(11) NOT NULL AUTO_INCREMENT,
  `postUid` int(11) NOT NULL,
  `postTS` int(11) NOT NULL,
  `toUid` int(11) NOT NULL,
  `blogId` int(11) NOT NULL,
  `content` text NOT NULL,
  PRIMARY KEY (`_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

--
-- 转存表中的数据 `bBlogComment`
--

INSERT INTO `bBlogComment` (`_id`, `postUid`, `postTS`, `toUid`, `blogId`, `content`) VALUES
(1, 1, 1428652294, 0, 1, '2222333'),
(2, 1, 1428652334, 0, 1, '3334'),
(3, 1, 1428652388, 0, 1, '44444'),
(4, 1, 1428652752, 0, 1, 'ddd');

-- --------------------------------------------------------

--
-- 表的结构 `bBlogZan`
--

CREATE TABLE IF NOT EXISTS `bBlogZan` (
  `_id` int(11) NOT NULL AUTO_INCREMENT,
  `postUid` int(11) NOT NULL,
  `postTS` int(11) NOT NULL,
  `blogId` int(11) NOT NULL,
  PRIMARY KEY (`_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=8 ;

--
-- 转存表中的数据 `bBlogZan`
--

INSERT INTO `bBlogZan` (`_id`, `postUid`, `postTS`, `blogId`) VALUES
(1, 1, 1428651485, 1),
(2, 1, 1428651653, 1),
(3, 1, 1428652433, 1),
(4, 1, 1428652460, 1),
(5, 1, 1428652700, 1),
(6, 1, 1428652706, 1),
(7, 1, 1428652743, 1);

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
('5524b6e40cf85', '5524b6e40cf85', '', 1, '1', 1428473218, 1428469476, '94cd7f94', 0),
('5527327fddd52', '5527327fddd52', '15800972778', 1, '1', 1428652764, 1428632191, '1fca19bb', 8696),
('5527954d6aea1', '5527954d6aea1', '', 4, '4', 1428657485, 1428657485, 'ed27767d', 0),
('5527a0a3eef15', '5527a0a3eef15', '', 1, '1', 1428660387, 1428660387, '807ff063', 0);

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
  `name` varchar(32) NOT NULL,
  `inviteCode` varchar(32) NOT NULL,
  `intro` varchar(255) NOT NULL,
  `everEdit` int(11) NOT NULL,
  PRIMARY KEY (`_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

--
-- 转存表中的数据 `uUser`
--

INSERT INTO `uUser` (`_id`, `email`, `phone`, `qq`, `weixin`, `regTS`, `typ`, `isAdmin`, `pwd`, `name`, `inviteCode`, `intro`, `everEdit`) VALUES
(1, 'guojia@nnn.com', '18621190931', 'ssss333', '郭佳', 1428469476, 1, 0, 'e92e8ef8c45f1137248c96c88b18ebb7', '郭佳', 'ade09cc0', '', 0),
(4, 'chenmashao@gmail.com', '15800972778', '', '', 1428647187, 1, 0, 'e92e8ef8c45f1137248c96c88b18ebb7', '陈红玉', 'c0168a47', '', 0);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
