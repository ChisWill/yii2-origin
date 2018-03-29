/*
Navicat MySQL Data Transfer

Source Server         : ChisWill
Source Server Version : 50151
Source Host           : localhost:3306
Source Database       : hsh_ver2

Target Server Type    : MYSQL
Target Server Version : 50151
File Encoding         : 65001

Date: 2015-11-22 23:28:27
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for hsh_manual_article
-- ----------------------------
DROP TABLE IF EXISTS `hsh_manual_article`;
CREATE TABLE `hsh_manual_article` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `menu_id` int(11) NOT NULL COMMENT '菜单ID',
  `content` mediumtext COMMENT '文章内容',
  `status` tinyint(4) DEFAULT '1',
  `created` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated` datetime DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='手册文章表';

-- ----------------------------
-- Table structure for hsh_manual_collection
-- ----------------------------
DROP TABLE IF EXISTS `hsh_manual_collection`;
CREATE TABLE `hsh_manual_collection` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL COMMENT '用户ID',
  `menu_id` int(11) NOT NULL COMMENT '收藏的菜单ID',
  `status` tinyint(4) DEFAULT '1',
  `created` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated` datetime DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_id` (`user_id`,`menu_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='手册收藏表';

-- ----------------------------
-- Table structure for hsh_manual_menu
-- ----------------------------
DROP TABLE IF EXISTS `hsh_manual_menu`;
CREATE TABLE `hsh_manual_menu` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL DEFAULT '' COMMENT '菜单名',
  `pid` int(11) DEFAULT '0' COMMENT '父ID',
  `level` smallint(6) DEFAULT '1' COMMENT '层级',
  `code` varchar(250) DEFAULT NULL COMMENT '从属排序值',
  `sort` int(11) DEFAULT '1' COMMENT '排序值',
  `child_num` int(11) DEFAULT '0' COMMENT '子集数',
  `url` varchar(250) DEFAULT '' COMMENT '跳转链接',
  `is_show` tinyint(4) DEFAULT '1' COMMENT '是否显示',
  `category` tinyint(4) DEFAULT '1' COMMENT '菜单分类',
  `status` tinyint(4) DEFAULT '1',
  `created` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated` datetime DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='手册菜单表';

-- ----------------------------
-- Table structure for hsh_manual_version
-- ----------------------------
DROP TABLE IF EXISTS `hsh_manual_version`;
CREATE TABLE `hsh_manual_version` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `menu_id` int(11) NOT NULL COMMENT '文章ID',
  `content` mediumtext COMMENT '内容',
  `action` tinyint(4) DEFAULT '1' COMMENT '操作类型：1创建，2更新，3恢复，4删除',
  `status` tinyint(4) DEFAULT '1',
  `created` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='手册版本表';
