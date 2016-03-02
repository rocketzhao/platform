# Host: localhost  (Version: 5.6.16)
# Date: 2015-08-21 11:02:06
# Generator: MySQL-Front 5.3  (Build 2.42)

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE */;
/*!40101 SET SQL_MODE='NO_ENGINE_SUBSTITUTION' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES */;
/*!40103 SET SQL_NOTES='ON' */;

#
# Source for table "ocenter_user_member"
#

CREATE TABLE `ocenter_user_member` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` varchar(255) NOT NULL DEFAULT '' COMMENT 'uid',
  `orderid` varchar(255) DEFAULT NULL COMMENT '订单流水号',
  `lesson_date` varchar(255) DEFAULT NULL COMMENT '课程时间',
  `IssueId` varchar(255) DEFAULT NULL COMMENT 'IssueId',
  `title` varchar(255) DEFAULT NULL COMMENT '课程title',
  `name` varchar(255) DEFAULT NULL COMMENT '家长名称',
  `idcard` varchar(255) DEFAULT NULL COMMENT '身份证号码',
  `tel` varchar(20) DEFAULT NULL COMMENT '联系电话',
  `addr` varchar(255) DEFAULT NULL COMMENT '家庭住址',
  `payment` varchar(50) DEFAULT NULL COMMENT '支付方式',
  `takebus` varchar(5) DEFAULT NULL,
  `count` int(2) DEFAULT NULL COMMENT '报名儿童数',
  `fee` varchar(55) DEFAULT NULL COMMENT '支付金额',
  `discount` varchar(255) DEFAULT NULL COMMENT '优惠金额',
  `create_time` varchar(255) DEFAULT NULL COMMENT 'create_time',
  `c_name_0` varchar(255) DEFAULT NULL,
  `c_name_1` varchar(255) DEFAULT NULL,
  `c_name_2` varchar(255) DEFAULT NULL,
  `c_name_3` varchar(255) DEFAULT NULL,
  `c_cardno_0` varchar(255) DEFAULT NULL,
  `c_cardno_1` varchar(255) DEFAULT NULL,
  `c_cardno_2` varchar(255) DEFAULT NULL,
  `c_cardno_3` varchar(255) DEFAULT NULL,
  `c_sex_0` varchar(255) DEFAULT NULL,
  `c_sex_1` varchar(255) DEFAULT NULL,
  `c_sex_2` varchar(255) DEFAULT NULL,
  `c_sex_3` varchar(255) DEFAULT NULL,
  `c_age_0` varchar(2) DEFAULT NULL,
  `c_age_1` varchar(2) DEFAULT NULL,
  `c_age_2` varchar(2) DEFAULT NULL,
  `c_age_3` varchar(2) DEFAULT NULL,
  `c_comments` text,
  PRIMARY KEY (`Id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
