CREATE DATABASE  IF NOT EXISTS `demo` /*!40100 DEFAULT CHARACTER SET utf8 */;
USE `demo`;
-- MySQL dump 10.13  Distrib 5.7.17, for macos10.12 (x86_64)
--
-- Host: 127.0.0.1    Database: demo
-- ------------------------------------------------------
-- Server version	5.6.25

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `admin_user`
--

DROP TABLE IF EXISTS `admin_user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `admin_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `admin_number` varchar(45) CHARACTER SET utf8 DEFAULT NULL COMMENT '用户编号',
  `loginname` varchar(45) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '账户名',
  `nickname` varchar(45) CHARACTER SET utf8 DEFAULT '' COMMENT '用户名',
  `password` varchar(100) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '密码',
  `created_at` int(11) DEFAULT '0' COMMENT '创建时间',
  `last_login_at` int(11) DEFAULT '0' COMMENT '上次登录时间',
  `last_login_ip` varchar(45) CHARACTER SET utf8 DEFAULT '' COMMENT '上次登录ip',
  `last_login_source` varchar(45) COLLATE utf8_bin DEFAULT '' COMMENT '登录来源',
  `is_disabled` int(11) DEFAULT '0' COMMENT '是否禁用',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='用户信息表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `admin_user`
--

LOCK TABLES `admin_user` WRITE;
/*!40000 ALTER TABLE `admin_user` DISABLE KEYS */;
INSERT INTO `admin_user` VALUES (1,NULL,'admin','超级管理员','a66abb5684c45962d887564f08346e8d',0,1526004017,'::1','网页',0);
/*!40000 ALTER TABLE `admin_user` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `authassignment`
--

DROP TABLE IF EXISTS `authassignment`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `authassignment` (
  `itemname` varchar(64) NOT NULL,
  `userid` varchar(64) NOT NULL,
  `bizrule` text,
  `data` text,
  PRIMARY KEY (`itemname`,`userid`),
  CONSTRAINT `fk_authassignment_1` FOREIGN KEY (`itemname`) REFERENCES `authitem` (`name`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `authassignment`
--

LOCK TABLES `authassignment` WRITE;
/*!40000 ALTER TABLE `authassignment` DISABLE KEYS */;
INSERT INTO `authassignment` VALUES ('超级管理员','1',NULL,'N;');
/*!40000 ALTER TABLE `authassignment` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `authitem`
--

DROP TABLE IF EXISTS `authitem`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `authitem` (
  `name` varchar(64) NOT NULL COMMENT '操作名称',
  `type` int(11) NOT NULL DEFAULT '0' COMMENT '类型   0 ：操作      1 : 任务       2:角色',
  `description` text COMMENT '描述',
  `bizrule` text,
  `data` text,
  `priority` int(11) DEFAULT '100' COMMENT '优先级',
  PRIMARY KEY (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `authitem`
--

LOCK TABLES `authitem` WRITE;
/*!40000 ALTER TABLE `authitem` DISABLE KEYS */;
INSERT INTO `authitem` VALUES ('操作日志',0,'',NULL,'N;',4),('权限管理',0,'',NULL,'N;',1),('用户管理',0,'',NULL,'N;',3),('系统管理',0,'',NULL,'N;',100),('角色管理',0,'',NULL,'N;',2),('超级管理员',2,'',NULL,'N;',100);
/*!40000 ALTER TABLE `authitem` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `authitemchild`
--

DROP TABLE IF EXISTS `authitemchild`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `authitemchild` (
  `parent` varchar(64) NOT NULL,
  `child` varchar(64) NOT NULL,
  PRIMARY KEY (`parent`,`child`),
  KEY `child` (`child`),
  CONSTRAINT `fk_authitemchild_1` FOREIGN KEY (`parent`) REFERENCES `authitem` (`name`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_authitemchild_2` FOREIGN KEY (`child`) REFERENCES `authitem` (`name`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `authitemchild`
--

LOCK TABLES `authitemchild` WRITE;
/*!40000 ALTER TABLE `authitemchild` DISABLE KEYS */;
INSERT INTO `authitemchild` VALUES ('系统管理','操作日志'),('超级管理员','操作日志'),('系统管理','权限管理'),('超级管理员','权限管理'),('系统管理','用户管理'),('超级管理员','用户管理'),('超级管理员','系统管理'),('系统管理','角色管理'),('超级管理员','角色管理');
/*!40000 ALTER TABLE `authitemchild` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `log`
--

DROP TABLE IF EXISTS `log`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `business_name` varchar(45) NOT NULL COMMENT '业务名',
  `operation_type` varchar(45) NOT NULL COMMENT '操作类型',
  `table_name` varchar(45) NOT NULL DEFAULT '' COMMENT '表名',
  `oldValue` varchar(1000) DEFAULT '' COMMENT '旧值',
  `newValue` varchar(1000) DEFAULT '' COMMENT '新值',
  `comment` varchar(200) DEFAULT '' COMMENT '备注/描述',
  `created_by` int(11) NOT NULL DEFAULT '0' COMMENT '操作人',
  `created_at` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='日志';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `log`
--

LOCK TABLES `log` WRITE;
/*!40000 ALTER TABLE `log` DISABLE KEYS */;
/*!40000 ALTER TABLE `log` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `menu`
--

DROP TABLE IF EXISTS `menu`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `menu` (
  `name` varchar(64) NOT NULL COMMENT '菜单mingc',
  `is_parent` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否父菜单  0 ：不是     1: 是',
  `parent` varchar(64) NOT NULL DEFAULT '' COMMENT '所属父菜单',
  `icon` varchar(100) NOT NULL DEFAULT '' COMMENT '图标',
  `url` varchar(100) NOT NULL DEFAULT '' COMMENT '菜单链接',
  `priority` int(11) DEFAULT '100' COMMENT '优先级',
  PRIMARY KEY (`name`),
  KEY `parent_index` (`parent`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='后台菜单';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `menu`
--

LOCK TABLES `menu` WRITE;
/*!40000 ALTER TABLE `menu` DISABLE KEYS */;
INSERT INTO `menu` VALUES ('操作日志',0,'系统管理','','log/index',4),('权限管理',0,'系统管理','','operation/index',1),('用户管理',0,'系统管理','','adminUser/index',3),('系统管理',1,'','/images/uploads/201805/JZA8WlmRubfWkqzo.png','',100),('角色管理',0,'系统管理','','role/index',2);
/*!40000 ALTER TABLE `menu` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2018-05-11 10:38:22
