/*
 Navicat Premium Data Transfer

 Source Server         : localhost
 Source Server Type    : MySQL
 Source Server Version : 50717
 Source Host           : localhost
 Source Database       : api_test_development

 Target Server Type    : MySQL
 Target Server Version : 50717
 File Encoding         : utf-8

 Date: 05/31/2017 18:04:54 PM
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
  `nav_id` int(11) DEFAULT '0' COMMENT '分类id',
  `name` varchar(64) DEFAULT '' COMMENT '接口名称',
  `url` varchar(255) DEFAULT '' COMMENT '相对路径',
  `sort` tinyint(4) DEFAULT '100' COMMENT '排序',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=56 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

-- ----------------------------
--  Records of `api_api`
-- ----------------------------
BEGIN;
INSERT INTO `api_api` VALUES ('55', '7', '32', '用户登录', '/user/login', '100');
COMMIT;

-- ----------------------------
--  Table structure for `api_api_param`
-- ----------------------------
DROP TABLE IF EXISTS `api_api_param`;
CREATE TABLE `api_api_param` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `app_id` int(11) NOT NULL DEFAULT '0' COMMENT '所属app',
  `nav_id` int(11) DEFAULT '0' COMMENT '分类id',
  `api_id` int(11) NOT NULL DEFAULT '0' COMMENT 'api表id',
  `name` varchar(64) DEFAULT '' COMMENT '参数名称',
  `remark` varchar(64) DEFAULT '' COMMENT '备注',
  `storage` tinyint(2) DEFAULT '1' COMMENT '是否进行本地缓存',
  `encrypt` enum('normal','base64','md5') DEFAULT 'normal' COMMENT '数据加密方式：normal：不加密，MD5：MD5加密，base4：base64加密',
  `value` varchar(255) DEFAULT '' COMMENT '默认值',
  `sort` tinyint(4) DEFAULT '100' COMMENT '排序',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=147 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

-- ----------------------------
--  Records of `api_api_param`
-- ----------------------------
BEGIN;
INSERT INTO `api_api_param` VALUES ('145', '7', '32', '55', 'phone', 'phone', '1', 'normal', '', '100'), ('146', '7', '32', '55', 'password', 'password', '1', 'normal', '', '100');
COMMIT;

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
  `domain` varchar(255) DEFAULT '' COMMENT '域名',
  `test_domain` varchar(255) DEFAULT '' COMMENT '测试域名',
  `sort` tinyint(4) DEFAULT '100' COMMENT '排序',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='项目列表';

-- ----------------------------
--  Records of `api_app`
-- ----------------------------
BEGIN;
INSERT INTO `api_app` VALUES ('7', '1', '测试项目', '', '', 'http://test.com', 'http://test0.com', '100'), ('8', '1', '测试项目1', '', '', 'http://a.com', 'http://a.com', '100'), ('9', '1', '测试项目2', '', '', 'http://a.com', 'http://a.com', '100'), ('10', '1', '测试项目3', '', '', 'http://a.com', 'http://a.com', '100'), ('11', '1', '测试项目5', '', '', 'http://a.com', 'http://a.com', '100'), ('12', '1', '测试项目6', '', '', 'http://a.com', 'http://a.com', '100');
COMMIT;

-- ----------------------------
--  Table structure for `api_layui_icon`
-- ----------------------------
DROP TABLE IF EXISTS `api_layui_icon`;
CREATE TABLE `api_layui_icon` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `icon` varchar(8) DEFAULT '' COMMENT '图标编号',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='layui图标列表';

-- ----------------------------
--  Records of `api_layui_icon`
-- ----------------------------
BEGIN;
INSERT INTO `api_layui_icon` VALUES ('1', '&#xe611;'), ('2', '&#xe614;'), ('3', '&#x1002;'), ('4', '&#xe60f;'), ('5', '&#xe615;'), ('6', '&#xe641;');
COMMIT;

-- ----------------------------
--  Table structure for `api_nav_item`
-- ----------------------------
DROP TABLE IF EXISTS `api_nav_item`;
CREATE TABLE `api_nav_item` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `app_id` int(11) NOT NULL DEFAULT '0' COMMENT '项目id',
  `name` varchar(32) NOT NULL DEFAULT '' COMMENT '栏目名称',
  `icon` varchar(16) DEFAULT NULL,
  `sort` tinyint(4) DEFAULT '100' COMMENT '排序',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=34 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

-- ----------------------------
--  Records of `api_nav_item`
-- ----------------------------
BEGIN;
INSERT INTO `api_nav_item` VALUES ('32', '7', '用户接口', 'xe612', '100'), ('33', '7', '通用接口', 'xe60f', '100');
COMMIT;

-- ----------------------------
--  Table structure for `api_team`
-- ----------------------------
DROP TABLE IF EXISTS `api_team`;
CREATE TABLE `api_team` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL DEFAULT '0' COMMENT '用户uid',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='团队成员表';

-- ----------------------------
--  Records of `api_team`
-- ----------------------------
BEGIN;
INSERT INTO `api_team` VALUES ('1', '1'), ('2', '6');
COMMIT;

-- ----------------------------
--  Table structure for `api_user`
-- ----------------------------
DROP TABLE IF EXISTS `api_user`;
CREATE TABLE `api_user` (
  `uid` int(11) NOT NULL AUTO_INCREMENT,
  `account` varchar(64) NOT NULL DEFAULT '' COMMENT '账号',
  `password` varchar(64) NOT NULL DEFAULT '' COMMENT '密码',
  `salt` varchar(16) NOT NULL DEFAULT '' COMMENT '密码盐',
  `state` tinyint(4) NOT NULL DEFAULT '1' COMMENT '用户状态（0：禁用，1：正常）',
  `add_time` int(10) NOT NULL DEFAULT '0' COMMENT '添加时间',
  `del` tinyint(2) DEFAULT '0' COMMENT '删除状态（0：未删除，1已删除）',
  `team_id` int(11) DEFAULT '0' COMMENT '团队id',
  `rid` tinyint(4) DEFAULT '0' COMMENT '角色 1管理员 2普通用户',
  `app_ids` varchar(255) DEFAULT '' COMMENT '允许访问的app',
  PRIMARY KEY (`uid`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

-- ----------------------------
--  Records of `api_user`
-- ----------------------------
BEGIN;
INSERT INTO `api_user` VALUES ('1', 'admin', 'b9677632c899fb35d12556c648e59200', '9ba2c', '0', '1473406751', '0', '0', '1', '7,10,'), ('6', 'lhc', '275209941296f9b711da5a6e262c9119', 'f58d7', '1', '1486457789', '0', '0', '2', ''), ('7', 'cto-ljc@qq.com', 'c7200fcb522eff5146119d747aca9b6e', 'aebc9', '0', '1496210904', '0', '0', '2', '7,9,'), ('8', 'test', 'c7a516a255f59e440437d8dee1e44724', '20a66', '0', '1496214410', '0', '1', '2', '7,8,'), ('9', 'test2', '507dd998d8acb72d7aaa9e8d5370d19f', 'fcb17', '0', '1496222385', '0', '0', '2', '7,9,');
COMMIT;

SET FOREIGN_KEY_CHECKS = 1;
