/*
 Navicat Premium Data Transfer

 Source Server         : 119.23.34.123
 Source Server Type    : MySQL
 Source Server Version : 50710
 Source Host           : 119.23.34.123
 Source Database       : api_test_2

 Target Server Type    : MySQL
 Target Server Version : 50710
 File Encoding         : utf-8

 Date: 03/20/2018 12:05:55 PM
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
--  Table structure for `api_admin`
-- ----------------------------
DROP TABLE IF EXISTS `api_admin`;
CREATE TABLE `api_admin` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `account` varchar(64) NOT NULL DEFAULT '' COMMENT '管理员账号',
  `password` varchar(64) NOT NULL DEFAULT '' COMMENT '管理员密码',
  `salt` varchar(16) NOT NULL DEFAULT '' COMMENT '管理员密码盐',
  `state` int(2) NOT NULL DEFAULT '0' COMMENT '账号状态（0正常，1为异常）',
  `add_time` int(11) NOT NULL DEFAULT '0' COMMENT '添加时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='管理用户表';

-- ----------------------------
--  Table structure for `api_api`
-- ----------------------------
DROP TABLE IF EXISTS `api_api`;
CREATE TABLE `api_api` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `app_id` int(11) NOT NULL DEFAULT '0' COMMENT 'app id',
  `api_item_id` int(11) DEFAULT '0' COMMENT '分类id',
  `name` varchar(64) DEFAULT '' COMMENT '接口名称',
  `path` varchar(255) DEFAULT '' COMMENT '相对路径',
  `sort` tinyint(4) DEFAULT '100' COMMENT '排序',
  PRIMARY KEY (`id`),
  KEY `nav_id` (`api_item_id`),
  KEY `app_id` (`app_id`),
  CONSTRAINT `api.api_item_id` FOREIGN KEY (`api_item_id`) REFERENCES `api_api_item` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `api.app_id` FOREIGN KEY (`app_id`) REFERENCES `api_app` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

-- ----------------------------
--  Table structure for `api_api_item`
-- ----------------------------
DROP TABLE IF EXISTS `api_api_item`;
CREATE TABLE `api_api_item` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `app_id` int(11) NOT NULL DEFAULT '0' COMMENT '项目id',
  `name` varchar(32) NOT NULL DEFAULT '' COMMENT '栏目名称',
  `sort` tinyint(4) DEFAULT '100' COMMENT '排序',
  PRIMARY KEY (`id`),
  KEY `app_id` (`app_id`),
  CONSTRAINT `nav.app_id` FOREIGN KEY (`app_id`) REFERENCES `api_app` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=44 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

-- ----------------------------
--  Table structure for `api_api_param`
-- ----------------------------
DROP TABLE IF EXISTS `api_api_param`;
CREATE TABLE `api_api_param` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `app_id` int(11) NOT NULL DEFAULT '0' COMMENT '所属app',
  `api_item_id` int(11) DEFAULT '0' COMMENT '分类id',
  `api_id` int(11) NOT NULL DEFAULT '0' COMMENT 'api表id',
  `name` varchar(64) DEFAULT '' COMMENT '参数名称',
  `remark` varchar(64) DEFAULT '' COMMENT '备注',
  `storage` tinyint(2) DEFAULT '1' COMMENT '是否进行本地缓存',
  `encrypt` enum('normal','base64','md5') DEFAULT 'normal' COMMENT '数据加密方式：normal：不加密，MD5：MD5加密，base4：base64加密',
  `value` varchar(255) DEFAULT '' COMMENT '默认值',
  `sort` tinyint(4) DEFAULT '100' COMMENT '排序',
  PRIMARY KEY (`id`),
  KEY `api_id` (`api_id`),
  CONSTRAINT `param.api_id` FOREIGN KEY (`api_id`) REFERENCES `api_api` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

-- ----------------------------
--  Table structure for `api_app`
-- ----------------------------
DROP TABLE IF EXISTS `api_app`;
CREATE TABLE `api_app` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `team_id` int(11) NOT NULL DEFAULT '0' COMMENT '团队id',
  `name` varchar(64) NOT NULL DEFAULT '' COMMENT 'app名称',
  `version` varchar(16) DEFAULT '' COMMENT '版本',
  `describe` varchar(255) DEFAULT '' COMMENT '描述',
  `domain` text COMMENT '域名',
  `sort` tinyint(4) DEFAULT '100' COMMENT '排序',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=33 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='项目列表';

-- ----------------------------
--  Table structure for `api_layui_icon`
-- ----------------------------
DROP TABLE IF EXISTS `api_layui_icon`;
CREATE TABLE `api_layui_icon` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `icon` varchar(8) DEFAULT '' COMMENT '图标编号',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='layui图标列表';

-- ----------------------------
--  Table structure for `api_team`
-- ----------------------------
DROP TABLE IF EXISTS `api_team`;
CREATE TABLE `api_team` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL DEFAULT '0' COMMENT '用户uid',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='团队成员表';

-- ----------------------------
--  Table structure for `api_user`
-- ----------------------------
DROP TABLE IF EXISTS `api_user`;
CREATE TABLE `api_user` (
  `uid` int(11) NOT NULL AUTO_INCREMENT,
  `account` varchar(64) NOT NULL DEFAULT '' COMMENT '账号',
  `email` varchar(64) DEFAULT '' COMMENT '邮箱',
  `password` varchar(64) NOT NULL DEFAULT '' COMMENT '密码',
  `salt` varchar(16) NOT NULL DEFAULT '' COMMENT '密码盐',
  `state` tinyint(4) NOT NULL DEFAULT '1' COMMENT '用户状态（0：禁用，1：正常）',
  `reg_time` int(10) NOT NULL DEFAULT '0' COMMENT '添加时间',
  `login_time` int(10) DEFAULT NULL,
  `reg_ip` varchar(16) DEFAULT NULL,
  `login_ip` varchar(16) DEFAULT NULL,
  `del` tinyint(2) DEFAULT '0' COMMENT '删除状态（0：未删除，1已删除）',
  `team_id` int(11) DEFAULT '0' COMMENT '团队id',
  `rid` tinyint(4) DEFAULT '0' COMMENT '角色 1管理员 2普通用户',
  `error_count` tinyint(4) DEFAULT '0' COMMENT '密码错误次数',
  `app_ids` varchar(255) DEFAULT '' COMMENT '允许访问的app',
  PRIMARY KEY (`uid`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

SET FOREIGN_KEY_CHECKS = 1;
