/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50617
Source Host           : localhost:3306
Source Database       : yii2-origin

Target Server Type    : MYSQL
Target Server Version : 50617
File Encoding         : 65001

Date: 2018-03-28 17:26:32
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for admin_action
-- ----------------------------
DROP TABLE IF EXISTS `admin_action`;
CREATE TABLE `admin_action` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `table_name` varchar(100) NOT NULL DEFAULT '' COMMENT '表名',
  `key` int(11) NOT NULL COMMENT '主键',
  `action` varchar(100) NOT NULL COMMENT '动作',
  `field` varchar(500) DEFAULT '' COMMENT '被修改的字段',
  `value` text COMMENT '被修改的值',
  `type` tinyint(4) DEFAULT '1' COMMENT '操作类型：1更新，2插入，3删除',
  `created_at` datetime DEFAULT NULL COMMENT '操作时间',
  `created_by` int(11) DEFAULT NULL COMMENT '操作者ID',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='操作记录表';

-- ----------------------------
-- Records of admin_action
-- ----------------------------

-- ----------------------------
-- Table structure for admin_menu
-- ----------------------------
DROP TABLE IF EXISTS `admin_menu`;
CREATE TABLE `admin_menu` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '序号',
  `name` varchar(30) NOT NULL COMMENT '菜单名',
  `pid` int(11) DEFAULT '0' COMMENT '父ID',
  `level` smallint(6) DEFAULT '1' COMMENT '层级',
  `sort` int(11) DEFAULT '1' COMMENT '排序值',
  `url` varchar(250) DEFAULT '' COMMENT '跳转链接',
  `icon` varchar(250) DEFAULT NULL COMMENT '图标',
  `is_show` tinyint(4) DEFAULT '1' COMMENT '是否显示',
  `category` tinyint(4) DEFAULT '1' COMMENT '菜单分类',
  `state` tinyint(4) DEFAULT '1' COMMENT '状态',
  `created_at` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8 COMMENT='菜单表';

-- ----------------------------
-- Records of admin_menu
-- ----------------------------
INSERT INTO `admin_menu` VALUES ('1', '系统管理', '0', '1', '1', 'system', '<i class=\"Hui-iconfont\">&#xe62e;</i>', '1', '1', '1', '2016-08-22 17:54:31', '1', '2016-09-01 17:21:03', '1');
INSERT INTO `admin_menu` VALUES ('2', '系统设置', '1', '2', '2', 'system/setting', '', '1', '1', '1', '2016-08-22 17:54:58', '1', '2016-08-22 17:54:58', '1');
INSERT INTO `admin_menu` VALUES ('3', '系统菜单', '1', '2', '3', 'system/menu', '', '1', '1', '1', '2016-08-22 17:55:35', '1', '2016-08-22 18:59:43', '1');
INSERT INTO `admin_menu` VALUES ('4', '系统日志', '1', '2', '4', 'system/logList', '', '1', '1', '1', '2016-08-22 18:42:11', '1', '2016-09-02 11:40:45', '1');
INSERT INTO `admin_menu` VALUES ('5', '管理员管理', '0', '1', '2', 'admin', '<i class=\"Hui-iconfont\">&#xe62d;</i>', '1', '1', '1', '2016-08-22 18:43:29', '1', '2016-09-01 17:21:03', '1');
INSERT INTO `admin_menu` VALUES ('6', '管理员列表', '5', '2', '2', 'admin/list', '', '1', '1', '1', '2016-08-22 18:46:24', '1', '2016-08-22 18:46:24', '1');
INSERT INTO `admin_menu` VALUES ('7', '角色列表', '5', '2', '3', 'admin/roleList', '', '1', '1', '1', '2016-08-22 18:46:50', '1', '2016-08-30 18:25:01', '1');
INSERT INTO `admin_menu` VALUES ('8', '权限列表', '5', '2', '4', 'admin/permissionList', '', '1', '1', '1', '2016-08-22 18:47:10', '1', '2016-08-30 18:24:58', '1');
INSERT INTO `admin_menu` VALUES ('9', '会员管理', '0', '1', '3', 'user', '<i class=\"Hui-iconfont\">&#xe60d;</i>', '1', '1', '1', '2016-08-22 18:47:49', '1', '2016-09-01 17:21:03', '1');
INSERT INTO `admin_menu` VALUES ('10', '会员列表', '9', '2', '2', 'user/list', '', '1', '1', '1', '2016-08-22 18:48:13', '1', '2016-08-27 19:45:26', '1');
INSERT INTO `admin_menu` VALUES ('11', '资讯管理', '0', '1', '4', 'article', '<i class=\"Hui-iconfont\">&#xe616;</i>', '1', '1', '1', '2016-08-22 18:48:55', '1', '2016-09-01 17:21:03', '1');
INSERT INTO `admin_menu` VALUES ('12', '资讯列表', '11', '2', '2', 'article/list', '', '1', '1', '1', '2016-08-22 18:49:15', '1', '2016-11-07 16:16:43', '1');
INSERT INTO `admin_menu` VALUES ('13', '图片管理', '0', '1', '5', 'picture', '<i class=\"Hui-iconfont\">&#xe613;</i>', '1', '1', '1', '2016-08-22 18:49:39', '1', '2016-09-01 17:21:03', '1');
INSERT INTO `admin_menu` VALUES ('14', '图片列表', '13', '2', '2', 'picture/list', '', '1', '1', '1', '2016-08-22 18:49:54', '1', '2016-08-22 18:49:54', '1');
INSERT INTO `admin_menu` VALUES ('15', '产品管理', '0', '1', '6', 'product', '<i class=\"Hui-iconfont\">&#xe620;</i>', '1', '1', '1', '2016-08-22 18:51:04', '1', '2016-09-01 17:21:03', '1');
INSERT INTO `admin_menu` VALUES ('16', '产品列表', '15', '2', '2', 'product/list', '', '1', '1', '1', '2016-08-22 18:51:18', '1', '2016-08-22 18:51:18', '1');
INSERT INTO `admin_menu` VALUES ('17', '评论管理', '0', '1', '7', 'comment', '<i class=\"Hui-iconfont\">&#xe622;</i>', '1', '1', '1', '2016-08-22 18:51:35', '1', '2016-09-01 17:21:03', '1');
INSERT INTO `admin_menu` VALUES ('18', '评论列表', '17', '2', '2', 'comment/list', '', '1', '1', '1', '2016-08-22 18:52:10', '1', '2016-08-22 18:52:10', '1');
INSERT INTO `admin_menu` VALUES ('19', '应用管理', '0', '1', '9', 'app', '<i class=\"Hui-iconfont\">&#xe61a;</i>', '1', '1', '1', '2016-08-22 19:00:05', '1', '2018-03-23 19:28:11', '1');
INSERT INTO `admin_menu` VALUES ('20', '栏目菜单', '11', '2', '1', 'article/menu', '', '1', '1', '1', '2016-11-07 16:16:40', '1', '2016-11-07 16:16:43', '1');
INSERT INTO `admin_menu` VALUES ('21', '操作日志', '1', '2', '5', 'system/actionList', '', '1', '1', '1', '2016-12-14 16:00:00', '1', '2016-12-14 16:00:00', '1');
INSERT INTO `admin_menu` VALUES ('22', '应用列表', '19', '2', '1', 'app/list', '', '1', '1', '1', '2018-03-23 19:28:21', '1', '2018-03-23 19:28:21', '1');
INSERT INTO `admin_menu` VALUES ('23', '客户列表', '19', '2', '1', 'app/userList', '', '1', '1', '1', '2018-03-26 16:22:01', '1', '2018-03-26 16:22:01', '1');
INSERT INTO `admin_menu` VALUES ('24', '调用记录', '19', '2', '1', 'app/callList', '', '1', '1', '1', '2018-03-26 18:53:35', '1', '2018-03-26 18:53:35', '1');

-- ----------------------------
-- Table structure for admin_user
-- ----------------------------
DROP TABLE IF EXISTS `admin_user`;
CREATE TABLE `admin_user` (
  `id` int(11) NOT NULL,
  `username` varchar(30) NOT NULL COMMENT '账号',
  `password` varchar(80) NOT NULL COMMENT '密码',
  `realname` varchar(30) NOT NULL DEFAULT '' COMMENT '真名',
  `login_time` datetime DEFAULT NULL COMMENT '最后登录时间',
  `power` int(11) DEFAULT '10000' COMMENT '权力值',
  `state` tinyint(4) DEFAULT '1' COMMENT '状态',
  `created_at` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='后台用户表';

-- ----------------------------
-- Records of admin_user
-- ----------------------------
INSERT INTO `admin_user` VALUES ('1', 'ChisWill', '$2y$13$a8vBCI7Ah7MNoXV7O6gokuGYmv1FHLZx7amWEFSn1K4zI8JE9rJpi', 'ChisWill', '2018-02-26 09:32:30', '10000', '1', '2016-08-06 23:36:12', '0', '2017-08-17 14:17:01', '1');
INSERT INTO `admin_user` VALUES ('2', 'admin', '$2y$13$a8vBCI7Ah7MNoXV7O6gokuGYmv1FHLZx7amWEFSn1K4zI8JE9rJpi', 'admin', '2018-03-28 17:18:49', '9999', '1', '2016-10-26 17:41:00', '1', '2017-01-20 22:00:52', '1');

-- ----------------------------
-- Table structure for api_app
-- ----------------------------
DROP TABLE IF EXISTS `api_app`;
CREATE TABLE `api_app` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '序号',
  `user_id` int(11) NOT NULL,
  `app_name` varchar(100) NOT NULL COMMENT '项目名',
  `key` varchar(100) NOT NULL COMMENT '秘钥',
  `rate_limit` decimal(8,4) NOT NULL DEFAULT '1.0000' COMMENT '每秒最大请求次数',
  `total` bigint(20) NOT NULL DEFAULT '0' COMMENT '总调用次数',
  `rest` bigint(20) DEFAULT '0' COMMENT '剩余调用次数',
  `ip` varchar(100) DEFAULT '' COMMENT 'IP',
  `allowance` int(11) DEFAULT '0' COMMENT '当前剩余次数',
  `allowance_updated_at` int(11) DEFAULT '0' COMMENT '最后请求时间',
  `state` tinyint(4) DEFAULT '1' COMMENT '状态',
  `auth_date` varchar(50) DEFAULT '' COMMENT '授权日期',
  `created_at` datetime DEFAULT NULL COMMENT '创建日期',
  PRIMARY KEY (`id`),
  UNIQUE KEY `key` (`key`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='API接口调用次数表';

-- ----------------------------
-- Records of api_app
-- ----------------------------
INSERT INTO `api_app` VALUES ('1', '1', 'a1', 'jri434jv93nsc23fwf34f34', '5.0000', '1000', '911', '127.0.0.1', '4', '1522130490', '1', '', '2018-03-28 17:17:44');

-- ----------------------------
-- Table structure for api_call_record
-- ----------------------------
DROP TABLE IF EXISTS `api_call_record`;
CREATE TABLE `api_call_record` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `app_id` int(11) NOT NULL,
  `method` varchar(10) DEFAULT '' COMMENT '方式',
  `url` varchar(255) DEFAULT '' COMMENT '链接',
  `ip` varchar(100) DEFAULT '' COMMENT 'IP',
  `state` tinyint(4) DEFAULT '-1' COMMENT '调用结果',
  `post_data` text COMMENT '提交数据',
  `created_at` datetime DEFAULT NULL COMMENT '调用日期',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of api_call_record
-- ----------------------------

-- ----------------------------
-- Table structure for api_user
-- ----------------------------
DROP TABLE IF EXISTS `api_user`;
CREATE TABLE `api_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '序号',
  `username` varchar(50) NOT NULL COMMENT '账号',
  `password` varchar(100) NOT NULL COMMENT '密码',
  `nickname` varchar(50) NOT NULL DEFAULT '' COMMENT '客户名称',
  `comment` text COMMENT '备注',
  `state` tinyint(4) DEFAULT '1' COMMENT '状态',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='API用户表';

-- ----------------------------
-- Records of api_user
-- ----------------------------
INSERT INTO `api_user` VALUES ('1', 'a', 'b', '1号客户', '123', '1', null, '2018-03-26 17:06:09');

-- ----------------------------
-- Table structure for article
-- ----------------------------
DROP TABLE IF EXISTS `article`;
CREATE TABLE `article` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(100) NOT NULL COMMENT '标题',
  `menu_id` int(11) NOT NULL DEFAULT '0',
  `template` varchar(100) DEFAULT '' COMMENT '模板',
  `content` text COMMENT '内容',
  `cover` varchar(100) DEFAULT '' COMMENT '封面图',
  `state` tinyint(4) DEFAULT '1' COMMENT '状态',
  `created_at` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of article
-- ----------------------------

-- ----------------------------
-- Table structure for article_menu
-- ----------------------------
DROP TABLE IF EXISTS `article_menu`;
CREATE TABLE `article_menu` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL COMMENT '菜单名',
  `pid` int(11) DEFAULT '0',
  `level` smallint(6) DEFAULT '1' COMMENT '层级',
  `code` varchar(250) DEFAULT '1' COMMENT '从属排序值',
  `sort` int(11) DEFAULT '1' COMMENT '排序值',
  `child_num` int(11) DEFAULT '0' COMMENT '子集数',
  `passwd` varchar(100) DEFAULT '' COMMENT '密码',
  `url` varchar(250) DEFAULT '' COMMENT '链接',
  `is_show` tinyint(4) DEFAULT '1' COMMENT '是否显示',
  `category` tinyint(4) DEFAULT '1' COMMENT '菜单分类',
  `state` tinyint(4) DEFAULT '1' COMMENT '状态',
  `created_at` datetime DEFAULT NULL COMMENT '创建时间',
  `created_by` int(11) DEFAULT NULL COMMENT '创建人',
  `updated_at` datetime DEFAULT NULL COMMENT '修改时间',
  `updated_by` int(11) DEFAULT NULL COMMENT '修改人',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='文章栏目菜单表';

-- ----------------------------
-- Records of article_menu
-- ----------------------------

-- ----------------------------
-- Table structure for auth_assignment
-- ----------------------------
DROP TABLE IF EXISTS `auth_assignment`;
CREATE TABLE `auth_assignment` (
  `item_name` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `user_id` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`item_name`,`user_id`),
  CONSTRAINT `auth_assignment_ibfk_1` FOREIGN KEY (`item_name`) REFERENCES `auth_item` (`name`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of auth_assignment
-- ----------------------------
INSERT INTO `auth_assignment` VALUES ('员工', '12', '1490615057');
INSERT INTO `auth_assignment` VALUES ('员工', '13', '1490615091');
INSERT INTO `auth_assignment` VALUES ('员工', '14', '1490615107');
INSERT INTO `auth_assignment` VALUES ('员工', '16', '1494331210');
INSERT INTO `auth_assignment` VALUES ('员工', '17', '1500862096');
INSERT INTO `auth_assignment` VALUES ('员工', '18', '1501573358');
INSERT INTO `auth_assignment` VALUES ('员工', '19', '1502527329');
INSERT INTO `auth_assignment` VALUES ('员工', '4', '1479462209');
INSERT INTO `auth_assignment` VALUES ('员工', '7', '1481090590');
INSERT INTO `auth_assignment` VALUES ('客户经理', '10', '1482809759');
INSERT INTO `auth_assignment` VALUES ('客户经理', '11', '1489484162');
INSERT INTO `auth_assignment` VALUES ('客户经理', '5', '1479891082');
INSERT INTO `auth_assignment` VALUES ('客户经理', '6', '1479891114');
INSERT INTO `auth_assignment` VALUES ('客户经理', '8', '1482809701');
INSERT INTO `auth_assignment` VALUES ('客户经理', '9', '1482809726');
INSERT INTO `auth_assignment` VALUES ('财务', '11', '1500638911');
INSERT INTO `auth_assignment` VALUES ('财务', '3', '1502261822');
INSERT INTO `auth_assignment` VALUES ('项目管理员', '11', '1497509321');
INSERT INTO `auth_assignment` VALUES ('项目管理员', '12', '1502597453');
INSERT INTO `auth_assignment` VALUES ('项目管理员', '13', '1495871033');
INSERT INTO `auth_assignment` VALUES ('项目管理员', '17', '1500862505');
INSERT INTO `auth_assignment` VALUES ('项目管理员', '18', '1501573358');
INSERT INTO `auth_assignment` VALUES ('项目管理员', '19', '1502597458');
INSERT INTO `auth_assignment` VALUES ('项目管理员', '3', '1495871040');
INSERT INTO `auth_assignment` VALUES ('项目管理员', '8', '1499053329');
INSERT INTO `auth_assignment` VALUES ('领导', '2', '1479378259');
INSERT INTO `auth_assignment` VALUES ('领导', '3', '1479462142');

-- ----------------------------
-- Table structure for auth_item
-- ----------------------------
DROP TABLE IF EXISTS `auth_item`;
CREATE TABLE `auth_item` (
  `name` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `type` int(11) NOT NULL,
  `description` text COLLATE utf8_unicode_ci,
  `rule_name` varchar(64) COLLATE utf8_unicode_ci DEFAULT NULL,
  `data` text COLLATE utf8_unicode_ci,
  `created_at` int(11) DEFAULT NULL,
  `updated_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`name`),
  KEY `rule_name` (`rule_name`),
  KEY `idx-auth_item-type` (`type`),
  CONSTRAINT `auth_item_ibfk_1` FOREIGN KEY (`rule_name`) REFERENCES `auth_rule` (`name`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of auth_item
-- ----------------------------
INSERT INTO `auth_item` VALUES ('adminAdminAddPermission', '2', '添加权限', null, null, '1479461682', '1479461682');
INSERT INTO `auth_item` VALUES ('adminAdminAjaxDeleteAdmin', '2', '删除管理员', null, null, '1479461682', '1479461682');
INSERT INTO `auth_item` VALUES ('adminAdminAjaxDeleteRole', '2', '删除角色', null, null, '1479461682', '1479461682');
INSERT INTO `auth_item` VALUES ('adminAdminAjaxRoleInfo', '2', '查看角色权限', null, null, '1479461682', '1479461682');
INSERT INTO `auth_item` VALUES ('adminAdminAjaxUpdateAdmin', '2', '快捷修改管理员信息', null, null, '1522229008', '1522229008');
INSERT INTO `auth_item` VALUES ('adminAdminAjaxUpdatePermission', '2', '修改权限', null, null, '1479461682', '1479461682');
INSERT INTO `auth_item` VALUES ('adminAdminCreateRole', '2', '创建角色', null, null, '1479461682', '1479461682');
INSERT INTO `auth_item` VALUES ('adminAdminEditRole', '2', '编辑角色', null, null, '1479461682', '1479461682');
INSERT INTO `auth_item` VALUES ('adminAdminList', '2', '管理员列表', null, null, '1479461682', '1479461682');
INSERT INTO `auth_item` VALUES ('adminAdminPermissionList', '2', '权限列表', null, null, '1479461682', '1479461682');
INSERT INTO `auth_item` VALUES ('adminAdminRoleList', '2', '角色列表', null, null, '1479461682', '1479461682');
INSERT INTO `auth_item` VALUES ('adminAdminSaveAdmin', '2', '创建/修改管理员', null, null, '1479461682', '1479461682');
INSERT INTO `auth_item` VALUES ('adminAppCallList', '2', '调用记录', null, null, '1522229008', '1522229008');
INSERT INTO `auth_item` VALUES ('adminAppList', '2', '应用列表', null, null, '1522229008', '1522229008');
INSERT INTO `auth_item` VALUES ('adminAppSaveApp', '2', '添加/修改应用', null, null, '1522229008', '1522229008');
INSERT INTO `auth_item` VALUES ('adminAppSaveUser', '2', '添加客户', null, null, '1522229008', '1522229008');
INSERT INTO `auth_item` VALUES ('adminAppUserList', '2', '客户列表', null, null, '1522229008', '1522229008');
INSERT INTO `auth_item` VALUES ('adminArticleList', '2', '资讯列表', null, null, '1479461682', '1479461682');
INSERT INTO `auth_item` VALUES ('adminArticleMenu', '2', '栏目菜单', null, null, '1479461682', '1479461682');
INSERT INTO `auth_item` VALUES ('adminArticleSaveArticle', '2', '添加/编辑文章', null, null, '1479461682', '1479461682');
INSERT INTO `auth_item` VALUES ('adminSystemActionList', '2', '查看操作日志', null, null, '1522229008', '1522229008');
INSERT INTO `auth_item` VALUES ('adminSystemAddSetting', '2', '添加系统设置', null, null, '1479461682', '1479461682');
INSERT INTO `auth_item` VALUES ('adminSystemDeleteSetting', '2', '删除系统设置', null, null, '1479461682', '1479461682');
INSERT INTO `auth_item` VALUES ('adminSystemLogDetail', '2', '查看日志详情', null, null, '1479461682', '1479461682');
INSERT INTO `auth_item` VALUES ('adminSystemLogList', '2', '系统日志', null, null, '1479461682', '1479461682');
INSERT INTO `auth_item` VALUES ('adminSystemMenu', '2', '系统菜单', null, null, '1479461682', '1479461682');
INSERT INTO `auth_item` VALUES ('adminSystemSaveSetting', '2', '修改系统设置', null, null, '1479461682', '1479461682');
INSERT INTO `auth_item` VALUES ('adminSystemSetting', '2', '查看系统设置', null, null, '1479461682', '1479461682');
INSERT INTO `auth_item` VALUES ('adminSystemUpdateSetting', '2', '更改系统设置属性', null, null, '1522229008', '1522229008');
INSERT INTO `auth_item` VALUES ('adminUserList', '2', '会员列表', null, null, '1479461682', '1479461682');
INSERT INTO `auth_item` VALUES ('oaAdminAddPermission', '2', '添加权限', null, null, '1479377039', '1494165279');
INSERT INTO `auth_item` VALUES ('oaAdminAddScore', '2', '录入业绩', null, null, '1492163392', '1492163392');
INSERT INTO `auth_item` VALUES ('oaAdminAjaxDeleteAdmin', '2', '删除管理员', null, null, '1479377039', '1479377039');
INSERT INTO `auth_item` VALUES ('oaAdminAjaxDeleteRole', '2', '删除角色', null, null, '1479377039', '1479377039');
INSERT INTO `auth_item` VALUES ('oaAdminAjaxRoleInfo', '2', '查看角色权限', null, null, '1479377039', '1479377039');
INSERT INTO `auth_item` VALUES ('oaAdminAjaxUpdateAdmin', '2', '快速修改', null, null, '1479378130', '1479378130');
INSERT INTO `auth_item` VALUES ('oaAdminAjaxUpdatePermission', '2', '修改权限', null, null, '1479377039', '1479377039');
INSERT INTO `auth_item` VALUES ('oaAdminBonusDetailList', '2', '业绩明细列表', null, null, '1495076191', '1495076191');
INSERT INTO `auth_item` VALUES ('oaAdminBonusList', '2', '员工业绩', null, null, '1492163392', '1492163392');
INSERT INTO `auth_item` VALUES ('oaAdminCreateRole', '2', '创建角色', null, null, '1479377039', '1479377039');
INSERT INTO `auth_item` VALUES ('oaAdminEditRole', '2', '编辑角色', null, null, '1479377039', '1479377039');
INSERT INTO `auth_item` VALUES ('oaAdminList', '2', '管理员列表', null, null, '1479377039', '1479377039');
INSERT INTO `auth_item` VALUES ('oaAdminPermissionList', '2', '权限列表', null, null, '1479377039', '1479377039');
INSERT INTO `auth_item` VALUES ('oaAdminRoleList', '2', '角色列表', null, null, '1479377039', '1479377039');
INSERT INTO `auth_item` VALUES ('oaAdminSaveAdmin', '2', '创建/修改管理员', null, null, '1479377039', '1479377039');
INSERT INTO `auth_item` VALUES ('oaAppAddServer', '2', '添加服务器', null, null, '1506870196', '1506870196');
INSERT INTO `auth_item` VALUES ('oaAppAdvanceUpdate', '2', '私密信息修改', null, null, '1479377603', '1479377603');
INSERT INTO `auth_item` VALUES ('oaAppAjaxUpdateApp', '2', '快速修改', null, null, '1479378802', '1479378802');
INSERT INTO `auth_item` VALUES ('oaAppCommonUpdate', '2', '公共信息修改', null, null, '1492163392', '1492163392');
INSERT INTO `auth_item` VALUES ('oaAppFeeList', '2', '项目费用列表', null, null, '1500908789', '1500908789');
INSERT INTO `auth_item` VALUES ('oaAppList', '2', '项目列表', null, null, '1479377039', '1479377039');
INSERT INTO `auth_item` VALUES ('oaAppOverTask', '2', '任务标记完成', null, null, '1500908789', '1500908789');
INSERT INTO `auth_item` VALUES ('oaAppSaveApp', '2', '创建/编辑项目', null, null, '1479377039', '1479377039');
INSERT INTO `auth_item` VALUES ('oaAppSaveTask', '2', '添加/编辑任务', null, null, '1500908789', '1500908789');
INSERT INTO `auth_item` VALUES ('oaAppServerList', '2', '服务器列表', null, null, '1506870196', '1506870196');
INSERT INTO `auth_item` VALUES ('oaAppTaskList', '2', '任务列表', null, null, '1500908789', '1500908789');
INSERT INTO `auth_item` VALUES ('oaFinanceAccountList', '2', '资金明细列表', null, null, '1502261788', '1502261788');
INSERT INTO `auth_item` VALUES ('oaFinanceAddCategory', '2', '添加分类', null, null, '1502261788', '1502261788');
INSERT INTO `auth_item` VALUES ('oaFinanceAddIncome', '2', '录入收入明细', null, null, '1502261788', '1502261788');
INSERT INTO `auth_item` VALUES ('oaFinanceAddSpend', '2', '录入支出明细', null, null, '1502261788', '1502261788');
INSERT INTO `auth_item` VALUES ('oaFinanceCategoryList', '2', '类别管理', null, null, '1502261788', '1502261788');
INSERT INTO `auth_item` VALUES ('oaSystemMenu', '2', '系统菜单', null, null, '1479377039', '1479377039');
INSERT INTO `auth_item` VALUES ('oaUserAddProduct', '2', '添加客户产品', null, null, '1495076191', '1495076191');
INSERT INTO `auth_item` VALUES ('oaUserAjaxUpdateOaUser', '2', '快捷更新客户信息', null, null, '1479890685', '1479890685');
INSERT INTO `auth_item` VALUES ('oaUserList', '2', '客户列表', null, null, '1479890685', '1479890685');
INSERT INTO `auth_item` VALUES ('oaUserProductList', '2', '客户产品列表', null, null, '1495076191', '1495076191');
INSERT INTO `auth_item` VALUES ('oaUserRecord', '2', '录入联系记录', null, null, '1479890685', '1479890685');
INSERT INTO `auth_item` VALUES ('oaUserRecordList', '2', '查看联系记录', null, null, '1479890685', '1479890685');
INSERT INTO `auth_item` VALUES ('oaUserSaveUser', '2', '添加客户', null, null, '1479890685', '1479890685');
INSERT INTO `auth_item` VALUES ('oaUserStatisticsList', '2', '客户统计报表', null, null, '1495076191', '1495076191');
INSERT INTO `auth_item` VALUES ('员工', '1', 'oa', null, null, '1479377692', '1500908828');
INSERT INTO `auth_item` VALUES ('客户经理', '1', 'oa', null, null, '1479890728', '1507546569');
INSERT INTO `auth_item` VALUES ('系统管理员', '1', 'admin', null, null, '1479461885', '1479461935');
INSERT INTO `auth_item` VALUES ('财务', '1', 'oa', null, null, '1500638885', '1503658741');
INSERT INTO `auth_item` VALUES ('超级管理员', '1', 'admin', null, null, '1479461946', '1479461946');
INSERT INTO `auth_item` VALUES ('运营管理员', '1', 'admin', null, null, '1479461922', '1522229103');
INSERT INTO `auth_item` VALUES ('项目管理员', '1', 'oa', null, null, '1495871026', '1507545684');
INSERT INTO `auth_item` VALUES ('领导', '1', 'oa', null, null, '1479377062', '1506870207');

-- ----------------------------
-- Table structure for auth_item_child
-- ----------------------------
DROP TABLE IF EXISTS `auth_item_child`;
CREATE TABLE `auth_item_child` (
  `parent` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `child` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`parent`,`child`),
  KEY `child` (`child`),
  CONSTRAINT `auth_item_child_ibfk_1` FOREIGN KEY (`parent`) REFERENCES `auth_item` (`name`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `auth_item_child_ibfk_2` FOREIGN KEY (`child`) REFERENCES `auth_item` (`name`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of auth_item_child
-- ----------------------------
INSERT INTO `auth_item_child` VALUES ('系统管理员', 'adminAdminAjaxDeleteAdmin');
INSERT INTO `auth_item_child` VALUES ('系统管理员', 'adminAdminAjaxDeleteRole');
INSERT INTO `auth_item_child` VALUES ('系统管理员', 'adminAdminAjaxRoleInfo');
INSERT INTO `auth_item_child` VALUES ('系统管理员', 'adminAdminCreateRole');
INSERT INTO `auth_item_child` VALUES ('系统管理员', 'adminAdminEditRole');
INSERT INTO `auth_item_child` VALUES ('系统管理员', 'adminAdminList');
INSERT INTO `auth_item_child` VALUES ('系统管理员', 'adminAdminRoleList');
INSERT INTO `auth_item_child` VALUES ('系统管理员', 'adminAdminSaveAdmin');
INSERT INTO `auth_item_child` VALUES ('运营管理员', 'adminAppList');
INSERT INTO `auth_item_child` VALUES ('运营管理员', 'adminAppSaveApp');
INSERT INTO `auth_item_child` VALUES ('运营管理员', 'adminAppSaveUser');
INSERT INTO `auth_item_child` VALUES ('运营管理员', 'adminAppUserList');
INSERT INTO `auth_item_child` VALUES ('运营管理员', 'adminArticleList');
INSERT INTO `auth_item_child` VALUES ('运营管理员', 'adminArticleMenu');
INSERT INTO `auth_item_child` VALUES ('运营管理员', 'adminArticleSaveArticle');
INSERT INTO `auth_item_child` VALUES ('系统管理员', 'adminSystemSaveSetting');
INSERT INTO `auth_item_child` VALUES ('系统管理员', 'adminSystemSetting');
INSERT INTO `auth_item_child` VALUES ('运营管理员', 'adminUserList');
INSERT INTO `auth_item_child` VALUES ('财务', 'oaAdminAddScore');
INSERT INTO `auth_item_child` VALUES ('领导', 'oaAdminAjaxDeleteAdmin');
INSERT INTO `auth_item_child` VALUES ('领导', 'oaAdminAjaxUpdateAdmin');
INSERT INTO `auth_item_child` VALUES ('员工', 'oaAdminBonusDetailList');
INSERT INTO `auth_item_child` VALUES ('财务', 'oaAdminBonusDetailList');
INSERT INTO `auth_item_child` VALUES ('员工', 'oaAdminBonusList');
INSERT INTO `auth_item_child` VALUES ('财务', 'oaAdminBonusList');
INSERT INTO `auth_item_child` VALUES ('员工', 'oaAdminList');
INSERT INTO `auth_item_child` VALUES ('领导', 'oaAdminSaveAdmin');
INSERT INTO `auth_item_child` VALUES ('领导', 'oaAppAddServer');
INSERT INTO `auth_item_child` VALUES ('项目管理员', 'oaAppAdvanceUpdate');
INSERT INTO `auth_item_child` VALUES ('领导', 'oaAppAdvanceUpdate');
INSERT INTO `auth_item_child` VALUES ('项目管理员', 'oaAppAjaxUpdateApp');
INSERT INTO `auth_item_child` VALUES ('领导', 'oaAppAjaxUpdateApp');
INSERT INTO `auth_item_child` VALUES ('员工', 'oaAppCommonUpdate');
INSERT INTO `auth_item_child` VALUES ('项目管理员', 'oaAppCommonUpdate');
INSERT INTO `auth_item_child` VALUES ('客户经理', 'oaAppFeeList');
INSERT INTO `auth_item_child` VALUES ('员工', 'oaAppList');
INSERT INTO `auth_item_child` VALUES ('项目管理员', 'oaAppList');
INSERT INTO `auth_item_child` VALUES ('员工', 'oaAppOverTask');
INSERT INTO `auth_item_child` VALUES ('项目管理员', 'oaAppOverTask');
INSERT INTO `auth_item_child` VALUES ('员工', 'oaAppSaveApp');
INSERT INTO `auth_item_child` VALUES ('项目管理员', 'oaAppSaveApp');
INSERT INTO `auth_item_child` VALUES ('项目管理员', 'oaAppSaveTask');
INSERT INTO `auth_item_child` VALUES ('领导', 'oaAppServerList');
INSERT INTO `auth_item_child` VALUES ('员工', 'oaAppTaskList');
INSERT INTO `auth_item_child` VALUES ('项目管理员', 'oaAppTaskList');
INSERT INTO `auth_item_child` VALUES ('财务', 'oaFinanceAccountList');
INSERT INTO `auth_item_child` VALUES ('财务', 'oaFinanceAddCategory');
INSERT INTO `auth_item_child` VALUES ('财务', 'oaFinanceAddIncome');
INSERT INTO `auth_item_child` VALUES ('财务', 'oaFinanceAddSpend');
INSERT INTO `auth_item_child` VALUES ('客户经理', 'oaUserAjaxUpdateOaUser');
INSERT INTO `auth_item_child` VALUES ('客户经理', 'oaUserList');
INSERT INTO `auth_item_child` VALUES ('客户经理', 'oaUserRecord');
INSERT INTO `auth_item_child` VALUES ('客户经理', 'oaUserRecordList');
INSERT INTO `auth_item_child` VALUES ('客户经理', 'oaUserSaveUser');
INSERT INTO `auth_item_child` VALUES ('客户经理', '员工');
INSERT INTO `auth_item_child` VALUES ('领导', '员工');
INSERT INTO `auth_item_child` VALUES ('领导', '客户经理');
INSERT INTO `auth_item_child` VALUES ('超级管理员', '系统管理员');
INSERT INTO `auth_item_child` VALUES ('超级管理员', '运营管理员');
INSERT INTO `auth_item_child` VALUES ('领导', '项目管理员');

-- ----------------------------
-- Table structure for auth_rule
-- ----------------------------
DROP TABLE IF EXISTS `auth_rule`;
CREATE TABLE `auth_rule` (
  `name` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `data` text COLLATE utf8_unicode_ci,
  `created_at` int(11) DEFAULT NULL,
  `updated_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of auth_rule
-- ----------------------------

-- ----------------------------
-- Table structure for log
-- ----------------------------
DROP TABLE IF EXISTS `log`;
CREATE TABLE `log` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `level` int(11) DEFAULT NULL,
  `category` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `log_time` double DEFAULT NULL,
  `prefix` text COLLATE utf8_unicode_ci,
  `message` text COLLATE utf8_unicode_ci,
  PRIMARY KEY (`id`),
  KEY `idx_log_level` (`level`),
  KEY `idx_log_category` (`category`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of log
-- ----------------------------

-- ----------------------------
-- Table structure for manual_article
-- ----------------------------
DROP TABLE IF EXISTS `manual_article`;
CREATE TABLE `manual_article` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `menu_id` int(11) NOT NULL COMMENT '菜单ID',
  `content` mediumtext COMMENT '文章内容',
  `state` tinyint(4) DEFAULT '1',
  `created_at` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `menu_id` (`menu_id`)
) ENGINE=InnoDB AUTO_INCREMENT=93 DEFAULT CHARSET=utf8 COMMENT='手册文章表';

-- ----------------------------
-- Records of manual_article
-- ----------------------------
INSERT INTO `manual_article` VALUES ('3', '4', '## PHP 代码规范\n\n> 目标：\n1. 建立节操感、责任感\n2. 减少阅读障碍，降低维护成本\n3. 遵循统一模式的代码更易于扩展\n', '1', '2015-12-09 16:48:18', '1', '2016-09-04 19:37:53', '1');
INSERT INTO `manual_article` VALUES ('24', '26', '>代码格式采用PSR规范，以下是该规范的简练要点\n\n1. 所有变量名尽量控制不出现拼音类型，使用尽量语义化、简单易懂的英文命名\n2. 所有变量都用驼峰法明明，私有方法、私有变量都采用下划线开头，如：`private function _getName()`\n3. `,`号和`;`号后要加空格，其他操作符、关键字前后都要加空格\n4. 双引号中的变量必须使用{}包起来；如果大段文本中包含变量，则采用分界符`<<<EOT`\n5. 所有SQL语句的关键字全部大写，并且必须美化SQL语句格式\n6. if 下即使只有一条语句，也必须用花括号包起来\n7. PHP文件禁止使用`?>`结束标签，每个php文件最后必须是空行\n8. 常量名必须全大写，用下划线分割单词', '1', '2016-01-07 18:08:53', '1', '2016-09-04 19:45:42', '1');
INSERT INTO `manual_article` VALUES ('25', '27', '1. action名和视图名保持一致，如 `function actionViewArticle()` 对应渲染的视图为 `$this->render(\'viewArticle\')`\n2. 所有纯ajax请求处理的方法全部以ajax为前缀命名，如 `actionAjaxCreate()`，form表单提交除外\n3. Ajax返回使用`return self::success()`和`return self::error()`，如果是输出模型错误，则`return self::error($model)`即可\n4. 表单的创建与修改，参考以下代码：\n\n\n    public function actionUser($id = null)\n    {\n        $user = User::findModel($id);\n        if ($user->load($_POST)) {\n            if ($user->save()) {\n                return self::success();\n            } else {\n                return self::error($user);\n            }\n        }\n        return $this->render(\'user\', compact(\'user\'));\n    }\n5.列表的创建，参考以下代码：\n\n    public function actionList()\n    {\n        $query = (new User)->search();\n        $html = $query->getTable([\n            \'id\',\n            \'name\' => \'姓名\',\n            \'age\' => function ($row) {\n                return $row[\'age\'] . \'岁\';\n            },\n            \'reg_time\' => [\'header\' => \'注册日期\', \'value\' => function ($row) {\n                return date(\'Y-m-d H:i:s\', $row[\'reg_time\']);\n            }],\n            [\'type\' => [\'edit\', \'delete\' => \'deleteUser\']]\n        ]);\n        return $this->render(\'list\', compact(\'html\'));\n    }', '1', '2016-01-08 15:45:30', '1', '2016-09-06 11:13:20', '2');
INSERT INTO `manual_article` VALUES ('26', '28', '1. 模型必须使用生成工具生成！\n2. 表示状态的字段必须使用生成工具生成对应的map方法和value方法，并为该状态字段设置类常量\n3. 每个列表条件都采用一个模型方法进行封装，以便控制器调用，如下所示：\n\n\n    public function listQuery()\n    {\n        return $this->search()\n                    ->with([\'user\', \'post\']\n                    ->andWhere([\'user.state\' => User::STATE_VALID])\n                    ->orderBy(\'post.id DESC\');\n    }\n4.当有字段变动时，同一个模型可反复生成，但必须确保公共父类模型中的`rules()`、`attributeLabels()`、`search()`方法没被修改过，否则所做的修改会被覆盖！\n5.为每一个表单设置一个场景名，并在`scenario()`方法中设定该场景中验证哪些字段。', '1', '2016-01-08 15:47:19', '1', '2016-09-04 20:08:41', '1');
INSERT INTO `manual_article` VALUES ('27', '29', '1. 无特殊情况，不允许直接加载js和css文件，而应使用Yii2的`AssetBundle`对象进行静态资源管理\n2. 使用`if/endif` 语法代替花括号语法\n3. 使用`<?= $name ?>` 代替 `<?php echo $name; ?>`\n4. 无特殊情况，禁止使用`style`属性和`on`系列事件属性\n5. a标签空链接使用href=\"javascript:;\"，禁止使用href=\"#\"\n6. 表单代码按照以下代码所示：\n\n\n    <?php $form = self::beginForm() ?>\n    <?= $model->title(\'表单标题\') ?>\n    <?= $form->field($model, \'name\') ?>\n    <?= $form->field($model, \'state\')->radioList() ?>\n    <?= $form->field($model, \'password\')->passwordInput() ?>    \n    <?= $form->submit(\'提交按钮名称\') ?>\n    <?php self::endForm() ?>\n', '1', '2016-01-08 15:58:28', '1', '2016-09-04 20:12:06', '1');
INSERT INTO `manual_article` VALUES ('28', '30', '1. 主键必须是id\n2. 字段名和表名用下划线分割\n3. 每个表的 `created_at、created_by、updated_at、updated_by`等字段，分别表示创建时间、创建人id、修改时间、修改人；如无必要，不要主动修改这些字段的值，他们会被框架自动处理\n4. 如果表中含有`state`字段，表示该表采用逻辑删除规则，则查询时必须加上诸如`state=User::STATE_VALID`的条件，来确保逻辑的一致性', '1', '2016-01-08 16:00:31', '1', '2016-09-04 20:18:07', '1');
INSERT INTO `manual_article` VALUES ('29', '31', '>采用PHPDoc文档注释标准\n\n1. 所填写的每个注释标记，必须表达清楚，不然不如不写\n2. 每个公共方法至少要有最基础的汉字说明\n3. 后台的公共action方法，使用自定义的标记 `@authname`来注明权限名称', '1', '2016-01-08 16:04:38', '1', '2016-09-04 20:23:23', '1');
INSERT INTO `manual_article` VALUES ('55', '67', '**一、	业务员**\n**1.	扫描微信（绑定）**\n登录系统，未进行绑定微信的账号会被弹屏要求扫码绑定微信，使用微信的扫一扫功能绑定即可，收到（恭喜您已经成功绑定微信号）这个提示完成绑定。（绑定过的不会再提醒）\n**2.	发起申请**\na)	销售申请：销售已经有库存的产品时，选择此申请；\nb)	采购申请：准备先采购回来，然后再分销的采购，可以选此申请；\nc)	销售申请（以销定采）：一销一采锁定的选择此申请，一销多采先通过销售与采购申请分别进行（后期会增加）。\n**3.	申请进度查看（历史发起）**\n发起申请之后，想了解审批已经进行到哪里了，可以点击“历史发起“菜单，即可查看申请的右边审批人是谁，只需要按提示去催对应人员即可。\na)	如果想查看所有的审批意见，点击该条记录即可；\nb)	如果想查看申请的具体内容，请点击该条记录中的“查看详情”即可。\n历史发起还分“未完结”与“已完结”两种类别：\na)	未完结：该审批还在进行或者有问题不能完结。\n已完结：该审批已经完成了。\n**4.	驳回重新提交**\n如果申请被驳回，会出现在待办任务中，点击处理即可！\n**5.	撤销重新提交**\n提交申请之后，发现自己填写的申请单有错误，可点击“历史发起”中对应记录上的“撤回”（前提：该流程未完结），然后到“待办任务”中进行修改并提交。\n\n**二、	主管及产品经理（风控、财务、高管）**\n**1.	扫描微信（绑定）**\n登录系统，未绑定微信的账号会被弹屏要求扫码绑定微信，使用微信的扫一扫功能绑定即可，收到（恭喜您已经成功绑定微信号）这个提示完成绑定。\n**2.	处理待办任务**\n有业务员发起申请时，主管会收到“待办任务”，只需要点击该条记录，即可进入查看申请内容，填写审批意见，点击“通过”，或“不通过”即可完成本次审批！\n**3.	进度查看（历史处理）**\n如果主管或产品经理想了解某个自己处理过的审批的进度，只需要点击“历史处理”菜单，查看该条审批的右边即可知道当前审批人是哪位，可直接去催对应审批人处理。\n \n历史处理还分“未完结”与“已完结”两种类别：\nb)	未完结：该审批还在进行或者有问题不能完结。\nc)	已完结：该审批已经完成了。\n**4.	转签**\n进行审核时发现该申请需要其他领导进行授权才可以进行审核，所以可以将审批的权力转交给更上级的领导（主要应用于财务，风控无法做决定时，将审批权力转交给业务的高层领导）\n', '1', '2016-03-08 15:06:12', '1', '2016-03-08 15:06:19', '1');
INSERT INTO `manual_article` VALUES ('56', '69', '\n**1.命名规范****\n(1)库名、表名、字段名必须使用小写字母,并采用下划线分割。\n\n(2)库名、表名、字段名禁止超过32个字符。\n\n(3)库名、表名、字段名必须见名知意。命名与业务、产品线等相关联。\n\n(4)库名、表名、字段名禁止使用MySQL保留字。（保留字列表见官方网站）\n\n(5)临时库、表名必须以tmp为前缀,并以日期为后缀。例如 tmp_test01_20130704。\n\n(6)备份库、表必须以bak为前缀,并以日期为后缀。例如 bak_test01_20130704。\n\n**2.基础规范**\n(1)使用INNODB存储引擎。\n\n(2)表字符集使用使用UTF8字符集。\n\n(3)所有表都需要添加注释;除主键外的其他字段都需要增加注释。推荐采用英文标点,避免出现乱码。\n\n(4)禁止在数据库中存储图片、文件等大数据。\n\n(5)每张表数据量建议控制在5000W以内。\n\n(6)禁止在线上做数据库压力测试。\n\n(7)禁止从测试、开发环境直连数据库。\n\n**3.库表设计**\n(1)禁止使用分区表。\n\n(2)将大字段、访问频率低的字段拆分到单独的表中存储,分离冷热数据。\n\n(3)推荐使用HASH进行散表,表名后缀使用十进制数,数字必须从0开始。\n\n(4)按日期时间分表需符合YYYY[MM][DD][HH]格式,例如2013071601。年份必须用4位数字表示。例如按日散表user_20110209、 按月散表user_201102。\n\n(5)采用合适的分库分表策略。例如千库十表、十库百表等。\n\n**4.字段设计**\n(1)建议使用UNSIGNED存储非负数值。\n\n(2)建议使用INT UNSIGNED存储IPV4。\n\n(3)用DECIMAL代替FLOAT和DOUBLE存储精确浮点数。例如与货币、金融相关的数据。\n\n(4)INT类型固定占用4字节存储,例如INT(4)仅代表显示字符宽度为4位,不代表存储长度。\n\n(5)区分使用TINYINT、SMALLINT、MEDIUMINT、INT、BIGINT数据类型。例如取值范围为0-80时,使用TINYINT UNSIGNED。\n\n(6)强烈建议使用TINYINT来代替ENUM类型。\n\n(7)尽可能不使用TEXT、BLOB类型。\n\n(8)禁止在数据库中存储明文密码。\n\n(9)使用VARBINARY存储大小写敏感的变长字符串或二进制内容。\n\n(10)使用尽可能小的VARCHAR字段。VARCHAR(N)中的N表示字符数而非字节数。\n\n(11)区分使用DATETIME和TIMESTAMP。存储年使用YEAR类型。存储日期使用DATE类型。 存储时间(精确到秒)建议使用TIMESTAMP类型。\n\n(12)所有字段均定义为NOT NULL。\n\n** 5.索引规范**\n(1)单张表中索引数量不超过5个。\n\n(2)单个索引中的字段数不超过5个。\n\n(3)索引名必须全部使用小写。\n\n(4)非唯一索引按照“idx_字段名称[_字段名称]”进用行命名。例如idx_age_name。\n\n(5)唯一索引按照“uniq_字段名称[_字段名称]”进用行命名。例如uniq_age_name。\n\n(6)组合索引建议包含所有字段名,过长的字段名可以采用缩写形式。例如idx_age_name_add。\n\n(7)表必须有主键,推荐使用UNSIGNED自增列作为主键。\n\n(8)唯一键由3个以下字段组成,并且字段都是整形时,可使用唯一键作为主键。其他情况下,建议使用自增列作主键。\n\n(9)禁止冗余索引。\n\n(10)禁止重复索引。\n\n(11)禁止使用外键。\n\n(12)联表查询时,JOIN列的数据类型必须相同,并且要建立索引。\n\n(13)不在低基数列上建立索引,例如“性别”。\n\n(14)选择区分度大的列建立索引。组合索引中,区分度大的字段放在最前。\n\n(15)对字符串使用前缀索引,前缀索引长度不超过8个字符。\n\n(16)不对过长的VARCHAR字段建立索引。建议优先考虑前缀索引,或添加CRC32或MD5伪列并建立索引。\n\n(17)合理创建联合索引,(a,b,c) 相当于 (a) 、(a,b) 、(a,b,c)。\n\n(18)合理使用覆盖索引减少IO,避免排序。\n\n**6.SQL设计**\n(1)使用prepared statement,可以提升性能并避免SQL注入。\n\n(2)使用IN代替OR。SQL语句中IN包含的值不应过多,应少于1000个。\n\n(3)禁止隐式转换。数值类型禁止加引号;字符串类型必须加引号。\n\n(4)尽量避免使用JOIN和子查询。必要时推荐用JOIN代替子查询。\n\n(5)禁止在MySQL中进行数学运算和函数运算。\n\n(6)减少与数据库交互次数,尽量采用批量SQL语句。\n\n(7)拆分复杂SQL为多个小SQL,避免大事务。\n\n(8)获取大量数据时,建议分批次获取数据,每次获取数据少于2000条,结果集应小于1M。\n\n(9)使用UNION ALL代替UNION。\n\n(10)统计行数使用COUNT(*)。\n\n(11)SELECT只获取必要的字段,禁止使用SELECT *。\n\n(12)SQL中避免出现now()、rand()、sysdate()、current_user()等不确定结果的函数。\n\n(13)INSERT语句必须指定字段列表,禁止使用 INSERT INTO TABLE()。\n\n(14)禁止单条SQL语句同时更新多个表。\n\n(15)禁止使用存储过程、触发器、视图、自定义函数等。\n\n(16)建议使用合理的分页方式以提高分页效率。\n\n(17)禁止在从库上执行统计类功能的QUERY,必要时申请统计类从库。\n\n(18)程序应有捕获SQL异常的处理机制,必要时通过rollback显式回滚。\n\n(19)重要SQL必须被索引:update、delete的where条件列、order by、group by、distinct字段、多表join字段。\n\n(20)禁止使用%前导查询,例如:like “%abc”,无法利用到索引。\n\n(21)禁止使用负向查询,例如 not in、!=、not like。\n\n(22)使用EXPLAIN判断SQL语句是否合理使用索引,尽量避免extra列出现:Using File Sort、Using Temporary。\n\n(23)禁止使用order by rand()。\n\n**7.行为规范**\n(1)表结构变更必须通知DBA进行审核。\n\n(2)禁止有super权限的应用程序账号存在。\n\n(3)禁止有DDL、DCL权限的应用程序账号存在。\n\n(4)重要项目的数据库方案选型和设计必须提前通知DBA参与。\n\n(5)批量导入、导出数据必须通过DBA审核,并在执行过程中观察服务。\n\n(6)批量更新数据,如UPDATE、DELETE操作,必须DBA进行审核,并在执行过程中观察服务。\n\n(7)产品出现非数据库导致的故障时,如被攻击,必须及时通DBA,便于维护服务稳定。\n\n(8)业务部门程序出现BUG等影响数据库服务的问题,必须及时通知DBA,便于维护服务稳定。\n\n(9)业务部门推广活动或上线新功能,必须提前通知DBA进行服务和访问量评估,并留出必要时间以便DBA完成扩容。\n\n(10)出现业务部门人为误操作导致数据丢失,需要恢复数据的,必须第一时间通知DBA,并提供准确时间点、误操作语句等重要线索。\n\n(11)提交线上建表改表需求,必须详细注明涉及到的所有SQL语句(包括INSERT、DELETE、UPDATE),便于DBA进行审核和优化。\n\n(12)对同一个表的多次alter操作必须合并为一次操作。\n\n(13)不要在MySQL数据库中存放业务逻辑。\n\n注：在DBA未到职前，由系统运维工程师兼职。工程师上线时，涉及到数据结构的变更时，需要写邮件给运维工程师，并抄送上级主管。\n\n**8.FAQ**\n1.库名、表名、字段名必须使用小写字母,并采用下划线分割。\na)MySQL有配置参数lower_case_table_names,不可动态更改,linux系统默认为 0,即库表名以实际情况存储,大小写敏感。如果是1,以小写存储,大小写不敏感。如果是2,以实际情况存储,但以小写比较。\n\nb)如果大小写混合使用,可能存在abc,Abc,ABC等多个表共存,容易导致混乱。\n\nc)字段名显式区分大小写,但实际使用不区分,即不可以建立两个名字一样但大小写不一样的字段。\n\nd)为了统一规范, 库名、表名、字段名使用小写字母。\n\n2.库名、表名、字段名禁止超过32个字符。\n库名、表名、字段名支持最多64个字符,但为了统一规范、易于辨识以及减少传输量,禁止超过32个字符。 \n\n3.使用INNODB存储引擎。\nINNODB引擎是MySQL5.5版本以后的默认引擘,支持事务、行级锁,有更好的数据恢复能力、更好的并发性能,同时对多核、大内存、SSD等硬件支持更好,支持数据热备份等,因此INNODB相比MyISAM有明显优势。\n\n4.库名、表名、字段名禁止使用MySQL保留字。\n当库名、表名、字段名等属性含有保留字时,SQL语句必须用反引号引用属性名称,这将使得SQL语句书写、SHELL脚本中变量的转义等变得非常复杂。\n\n5.禁止使用分区表。\n分区表对分区键有严格要求;分区表在表变大后,执行DDL、SHARDING、单表恢复等都变得更加困难。因此禁止使用分区表,并建议业务端手动SHARDING。\n\n6.建议使用UNSIGNED存储非负数值。\n同样的字节数,非负存储的数值范围更大。如TINYINT有符号为 -128-127,无符号为0-255。\n\n7.建议使用INT UNSIGNED存储IPV4。\nUNSINGED INT存储IP地址占用4字节,CHAR(15)则占用15字节。另外,计算机处理整数类型比字符串类型快。使用INT UNSIGNED而不是CHAR(15)来存储IPV4地址,通过MySQL函数inet_ntoa和inet_aton来进行转化。IPv6地址目前没有转化函数,需要使用DECIMAL或两个BIGINT来存储。\n\n例如:\n\nSELECT INET_ATON(\'209.207.224.40\'); 3520061480\n\nSELECT INET_NTOA(3520061480); 209.207.224.40\n\n8.强烈建议使用TINYINT来代替ENUM类型。\nENUM类型在需要修改或增加枚举值时,需要在线DDL,成本较大;ENUM列值如果含有数字类型,可能会引起默认值混淆。\n\n9.使用VARBINARY存储大小写敏感的变长字符串或二进制内容。\nVARBINARY默认区分大小写,没有字符集概念,速度快。\n\n10.INT类型固定占用4字节存储,例如INT(4)仅代表显示字符宽度为4位,不代表存储长度。\n数值类型括号后面的数字只是表示宽度而跟存储范围没有关系,比如INT(3)默认显示3位,空格补齐,超出时正常显示,python、java客户端等不具备这个功能。\n\n11.区分使用DATETIME和TIMESTAMP。存储年使用YEAR类型。存储日期使用DATE类型。 存储时间(精确到秒)建议使用TIMESTAMP类型。\nDATETIME和TIMESTAMP都是精确到秒,优先选择TIMESTAMP,因为TIMESTAMP只有4个字节,而DATETIME8个字节。同时TIMESTAMP具有自动赋值以及自动更新的特性。注意:在5.5和之前的版本中,如果一个表中有多个timestamp列,那么最多只能有一列能具有自动更新功能。\n\n如何使用TIMESTAMP的自动赋值属性?\na)自动初始化,并自动更新: column1 TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP\n\nb)只是自动初始化: column1 TIMESTAMP DEFAULT CURRENT_TIMESTAMP\n\nc)自动更新,初始化的值为0: column1 TIMESTAMP DEFAULT 0 ON UPDATE CURRENT_TIMESTAMP\n\nd)初始化的值为0: column1 TIMESTAMP DEFAULT 0\n\n12.所有字段均定义为NOT NULL。\na)对表的每一行,每个为NULL的列都需要额外的空间来标识。\n\nb)B树索引时不会存储NULL值,所以如果索引字段可以为NULL,索引效率会下降。\n\nc)建议用0、特殊值或空串代替NULL值。\n\n13.将大字段、访问频率低的字段拆分到单独的表中存储,分离冷热数据。\n有利于有效利用缓存,防止读入无用的冷数据,较少磁盘IO,同时保证热数据常驻内存提高缓存命中率。 \n\n14.禁止在数据库中存储明文密码。\n采用加密字符串存储密码，并保证密码不可解密，同时采用随机字符串加盐保证密码安全。防止数据库数据被公司内部人员或黑客获取后，采用字典攻击等方式暴力破解用户密码。\n\n15.表必须有主键,推荐使用UNSIGNED自增列作为主键。\n表没有主键,INNODB会默认设置隐藏的主键列;没有主键的表在定位数据行的时候非常困难,也会降低基于行复制的效率。\n\n16.禁止冗余索引。\n索引是双刃剑,会增加维护负担,增大IO压力。(a,b,c)、(a,b),后者为冗余索引。可以利用前缀索引来达到加速目的,减轻维护负担。\n\n17.禁止重复索引。\nprimary key a;uniq index a;重复索引增加维护负担、占用磁盘空间,同时没有任何益处。\n\n18.不在低基数列上建立索引,例如“性别”。\n大部分场景下,低基数列上建立索引的精确查找,相对于不建立索引的全表扫描没有任何优势,而且增大了IO负担。\n\n19.合理使用覆盖索引减少IO,避免排序。\n覆盖索引能从索引中获取需要的所有字段,从而避免回表进行二次查找,节省IO。INNODB存储引擎中, secondary index(非主键索引,又称为辅助索引、二级索引)没有直接存储行地址,而是存储主键值。如果用户需要查询secondary index中所不包含的数据列,则需要先通过secondary index查找到主键值,然后再通过主键查询到其他数据列,因此需要查询两次。覆盖索引则可以在一个索引中获取所有需要的数据,因此效率较高。主键查询是天然的覆盖索引。例如SELECT email,uid FROM user_email WHERE uid=xx,如果uid 不是主键,适当时候可以将索引添加为index(uid,email),以获得性能提升。\n\n20.用IN代替OR。SQL语句中IN包含的值不应过多,应少于1000个。\nIN是范围查找,MySQL内部会对IN的列表值进行排序后查找,比OR效率更高。\n\n21.表字符集使用UTF8,必要时可申请使用UTF8MB4字符集。\na)UTF8字符集存储汉字占用3个字节,存储英文字符占用一个字节。\n\nb)UTF8统一而且通用,不会出现转码出现乱码风险。\n\nc)如果遇到EMOJ等表情符号的存储需求,可申请使用UTF8MB4字符集。\n\n22.用UNION ALL代替UNION。\nUNION ALL不需要对结果集再进行排序。\n\n23.禁止使用order by rand()。\norder by rand()会为表增加一个伪列,然后用rand()函数为每一行数据计算出rand()值,然后基于该行排序, 这通常都会生成磁盘上的临时表,因此效率非常低。建议先使用rand()函数获得随机的主键值,然后通过主键获取数据。\n\n24.建议使用合理的分页方式以提高分页效率。\n第一种分页写法：\n\nselect *  from t where thread_id = 771025 and deleted = 0 order by gmt_create asc limit 0, 15;\n\n 原理：一次性根据过滤条件取出所有字段进行排序返回。\n\n 数据访问开销=索引IO+索引全部记录结果对应的表数据IO\n\n 缺点：该种写法越翻到后面执行效率越差，时间越长，尤其表数据量很大的时候。\n\n适用场景：当中间结果集很小（10000行以下）或者查询条件复杂（指涉及多个不同查询字段或者多表连接）时适用。\n\n第二种分页写法：\n\nselect t.* from ( select id from t where thread_id = 771025 and deleted = 0 order by gmt_create asc limit 0, 15) a, t  where a.id = t.id;\n\n前提：假设t表主键是id列，且有覆盖索引secondary key:(thread_id, deleted, gmt_create)\n\n原理：先根据过滤条件利用覆盖索引取出主键id进行排序，再进行join操作取出其他字段。\n\n数据访问开销=索引IO+索引分页后结果（例子中是15行）对应的表数据IO。\n\n优点：每次翻页消耗的资源和时间都基本相同，就像翻第一页一样。\n\n适用场景：当查询和排序字段（即where子句和order by子句涉及的字段）有对应覆盖索引时，且中间结果集很大的情况时适用。\n\n25.SELECT只获取必要的字段,禁止使用SELECT *。\n减少网络带宽消耗;\n\n能有效利用覆盖索引;\n\n表结构变更对程序基本无影响。\n\n26.SQL中避免出现now()、rand()、sysdate()、current_user()等不确定结果的函数。\n语句级复制场景下,引起主从数据不一致;不确定值的函数,产生的SQL语句无法利用QUERY CACHE。 \n\n27.采用合适的分库分表策略。例如千库十表、十库百表等。\n采用合适的分库分表策略,有利于业务发展后期快速对数据库进行水平拆分,同时分库可以有效利用MySQL的多线程复制特性。\n\n28.减少与数据库交互次数,尽量采用批量SQL语句。\n使用下面的语句来减少和db的交互次数:\n\na)INSERT ... ON DUPLICATE KEY UPDATE\n\nb)REPLACE INTO\n\nc)INSERT IGNORE\n\nd)INSERT INTO VALUES()\n\n29.拆分复杂SQL为多个小SQL,避免大事务。\n简单的SQL容易使用到MySQL的QUERY CACHE;减少锁表时间特别是MyISAM;可以使用多核CPU。\n\n30.对同一个表的多次alter操作必须合并为一次操作。\nmysql对表的修改绝大部分操作都需要锁表并重建表,而锁表则会对线上业务造成影响。为减少这种影响,必须把对表的多次alter操作合并为一次操作。例如,要给表t增加一个字段b,同时给已有的字段aa建立索引, 通常的做法分为两步:\n\nalter table t add column b varchar(10);\n\n然后增加索引:\n\nalter table t add index idx_aa(aa);\n\n正确的做法是:\n\nalter table t add column b varchar(10),add index idx_aa(aa);\n\n31.避免使用存储过程、触发器、视图、自定义函数等。\n这些高级特性有性能问题,以及未知BUG较多。业务逻辑放到数据库会造成数据库的DDL、SCALE OUT、 SHARDING等变得更加困难。\n\n32.禁止有super权限的应用程序账号存在。\n安全第一。super权限会导致read only失效,导致较多诡异问题而且很难追踪。\n\n33.提交线上建表改表需求,必须详细注明涉及到的所有SQL语句(包括INSERT、DELETE、UPDATE),便于DBA进行审核和优化。\n并不只是SELECT语句需要用到索引。UPDATE、DELETE都需要先定位到数据才能执行变更。因此需要业务提供所有的SQL语句便于DBA审核。\n\n34.不要在MySQL数据库中存放业务逻辑。\n数据库是有状态的服务,变更复杂而且速度慢,如果把业务逻辑放到数据库中,将会限制业务的快速发展。建议把业务逻辑提前,放到前端或中间逻辑层,而把数据库作为存储层,实现逻辑与存储的分离。', '1', '2016-03-25 19:08:37', '1', '2016-03-25 19:08:37', '1');
INSERT INTO `manual_article` VALUES ('57', '70', 'Mysql高负载排查命令top\n1. 确定高负载的类型,top命令看负载高是CPU还是IO。 \n2. mysql 下执行查看当前的连接数与执行的sql 语句。 \n3. 检查慢查询日志，可能是慢查询引起负载高。 \n4. 检查硬件问题，是否磁盘故障问题造成的。 \n5. 检查监控平台，对比此机器不同时间的负载。 \n\n**1．确定负载类型(top)**\ntop - 10:14:18 up 23 days, 11:01,  1 user, load average: 124.17, 55.88, 24.70 \nTasks: 138 total,   1 running, 137 sleeping,   0 stopped,   0 zombie \nCpu(s):  2.4%us,  1.0%sy,  0.0%ni, 95.2%id,  2.0%wa,  0.1%hi,  0.2%si,  0.0%st \nMem:   3090528k total,  2965772k used,   124756k free,    93332k buffers \nSwap:  4192956k total,  2425132k used,  1767824k free,   756524k cached \n           \nPID USER      PR  NI  VIRT  RES  SHR S %CPU %MEM    TIME+  COMMAND \n30833 mysql     15   0 6250m 2.5g 4076 S 257.1 49.9 529:34.45 mysqld  \n\n**2．查看当前的连接数与执行的sql 语句**\nshow processlist; \n\n**3. 查看慢查询日志**\ntail /usr/local/mysql/var/slow_queries.log \n \n主要参数 \nQuery_time: 0 Lock_time: 0 Rows_sent: 1 Rows_examined: 54 \n分别意思为:查询时间 锁定时间 查询结果行数 扫描行数,主要看扫描行数多的语句,然后去数据库加上对应的索引,再优化下变态的sql 语句。\n\n**4. 极端情况kill sql进程**\n找出占用cpu时间过长的sql，在mysql 下执行如下命令： \nshow processlist; \n确定后一条sql处于Query状态，且Time时间过长，锁定它的ID，执行如下命令： \nkill QUERY  269815764;  \n\nmysqldumpslow  -s t -t 2 slow_querys.log\n-s, 是表示按照何种方式排序，c、t、l、r分别是按照记录次数、时间、查询时间、返回的记录数来排序，ac、at、al、ar，表示相应的倒叙；\n-t, 是top n的意思，即为返回前面多少条的数据；\n-g, 后边可以写一个正则匹配模式，大小写不敏感的；\nmysqldumpslow -s l -t 5 -g \"hsh_bpm\" slow_querys_new.log\n\n查看网站访问日志\n查看IP访问次数\n[root@nginx logs]# more huasuhui.access.log| grep news.huasuhui.com  | awk \'{print $1}\' |sort |uniq -c | sort -nr  | more\n查看指定IP访问的url\n[root@nginx logs]# more huasuhui.access.log| grep news.huasuhui.com  | grep 180.175.162.229 | awk \'{print $7}\' |sort |uniq -c | sort -nr  | more\n\n磁盘空间\n[root@exmail qqbackup]# df -hT\nFilesystem     Type   Size  Used Avail Use% Mounted on\n/dev/xvda1     ext4    20G  4.6G   15G  25% /\ntmpfs          tmpfs  3.9G     0  3.9G   0% /dev/shm\n/dev/xvdb1     ext3   197G  6.9G  181G   4% /alidata\n－h：以容易理解的格式印出文件系统大小，例如136KB、2 4MB、21GB\n－T：显示文件系统类型\n－i：显示inode信息而非块使用量\n\n内存空间\n[root@iZ28hmm7lc9Z mysqlLog]# free -m\n               total       used       free     shared    buffers     cached\nMem:         32107      28992       3114          0       1031      18001\n-/+ buffers/cache:       9960      22146 \nSwap:            0          0          0\n可用内存=系统free memory+buffers+cached.\nbuffers是指用来给块设备做的缓冲大小\ncached是用来给文件做缓冲。\n\n进程查询\nps -ef | grep java\n查询当前系统运行多少java\nps -ef| grep -v grep | grep java | wc –l\n-e 显示所有进程\n-f 全格式\n\n域名解析\ndig @8.8.8.8 www.baidu.cn \nnslookup www.baidu.com\n\n文件下载\nwget -S http://www.baidu.cn/test.log\ncurl -O http://www.baidu.cn/test.log\n\n查看端口是否开启\ntelnet 1.1.1.1 22\n\n查看网络连通性\nPing 1.1.1.1\nTraceroute 1.1.1.1\nmtr 1.1.1.1\n\n查看占用cpu最高的进程\n[root@exmail ~]# dstat --top-cpu\n-most-expensive-\n  cpu process   \njava         0.0\njava         0.2\njava         0.5\njava         0.2\njava         1.0\njava         0.5\n\n查看端口\nnetstat –lntp\nlsof –i:80\n\n查看磁盘IO使用情况\niostat -xmt 2 10\n\n字段说明：\nrrqm/s:   每秒进行 merge 的读操作数目\nwrqm/s:   每秒进行 merge 的写操作数目\nr/s:      每秒完成的读 I/O 设备次数\nw/s:      每秒完成的写 I/O 设备次数\nrMB/s:    每秒读M字节数\nwMB/s:    每秒写M字节数\navgrq-sz  平均每次设备I/O的数据大小\navgqu-sz  平均队列长度\nawait     平均每次I/O操作的等待时间(毫秒)\nsvctm     平均每次I/O操作的服务时间(毫秒)\n%util     1秒中有百分之多少时间用于 I/O 操作，如果%util接近 100%，磁盘可能存在瓶颈。\n\n实时排序显示所有运行进程的磁盘I/O情况。 \nIotop\n\n文件操作\n更改文件所属用户和组\nchown –R www.www /home/web\n\n更改文件权限\nchmod 775 /home/web\n-R 递归\n\n执行权限\nchmod a+x   test.sh\n\n创建目录\nmkdir test\n\n创建多级目录\nmkdir –p /test/abc/bbb/cccc/nnnn\n\n创建文件\ntouch 1.txt\necho “helloworld”>1.txt\nvim 1.txt\n\n文件统计\n查看目录大小并重新排序\ndu -sh  /home |sort –nr\n\n统计文件行数\nmore abc.txt | wc -l\n\n删除命令\nrm \n不建议用-f\n参数\n-r 递归\n-f 强制（不提示确认）', '1', '2016-03-25 19:15:42', '1', '2016-03-28 13:36:09', '1');
INSERT INTO `manual_article` VALUES ('78', '66', '#该部分主要为BPM说明，一般情况不用看', '1', '2016-09-04 20:24:05', '1', '2016-09-04 20:24:05', '1');
INSERT INTO `manual_article` VALUES ('79', '94', '#常规功能的开发示例', '1', '2016-10-04 17:05:21', '1', '2016-10-04 17:05:21', '1');
INSERT INTO `manual_article` VALUES ('80', '95', '以下代码基本是固定模式，可以录制成宏。\n控制器：\n\n    public function actionDetail($id = 0)\n    {\n        $model = User::findModel($id);\n\n        if ($model->load(post()) {\n            if ($model->save()) {\n                return success();\n            } else {\n                return error($model);\n            }\n        }\n\n        return $this->render(\'detail\', compact(\'model\'));\n    }\n\n视图：\n\n    <?php $form = self::beginForm() ?>\n    <?= $model->title(\'用户\') ?>\n    <?= $form->field($model, \'username\') ?>\n    <?= $form->submit($model) ?>\n    <?php self::endForm() ?>\n\n    <script>\n    $(function () {\n        $(\"#submitBtn\").click(function () {\n            $(\"form\").ajaxSubmit($.config(\'ajaxSubmit\', {\n                success: function (msg) {\n                    if (msg.state) {\n                        $.alert(msg.info || \'操作成功\', function () {\n                            parent.location.reload();\n                        });\n                    } else {\n                        $.alert(msg.info);\n                    }\n                }\n            }));\n            return false;\n        });\n    });\n    </script>\n', '1', '2016-10-04 17:29:34', '1', '2016-10-04 17:29:34', '1');
INSERT INTO `manual_article` VALUES ('81', '100', '1.类名全小写，多单词时以 - 号分隔，如：\n`<div class=\"main-nav\"></div>`\n2.每个层级缩进4个空格，每个层级下如果不是标签，则需要换行显示如：\n\n\n    <ul>\n        <li>a</li>\n        <li>\n            <span>b</span>\n        </li>\n    </ul>\n\n3.不允许使用style属性\n4. a标签的href属性设为“#”，否则href设置为 \"javascript:;”', '1', '2016-10-25 15:47:44', '1', '2017-08-10 16:12:33', '1');
INSERT INTO `manual_article` VALUES ('82', '101', '1. 变量驼峰式命名\n2. 每条语句最后必须加上分号\n3. 多条声明语句采用如下格式\n    \n    \n    var a = 1,\n        b = 2,\n        c = 3;\n4.存放jquery对象的变量必须以 $ 符作为前缀命名，如：`$a = $(\".link\");`\n5.事件绑定方法必须保存当前触发元素到变量中（function关键字右边必须含有一个空格），如：\n\n\n\n    $(\".container\").on(\'click\', \'.btn\', function () {\n        var $this = $(this);\n        // other code...\n    });\n6.标签中存放的变量必须放到data属性中，jquery中使用data方法来获取，如：\n\n\n    Html:\n    <input type=\"text\" data-key=\"1\">\n    Js:\n    var key = $(\"#userInput\").data(\'key\');', '1', '2016-10-26 10:40:27', '1', '2017-04-12 18:49:25', '1');
INSERT INTO `manual_article` VALUES ('83', '102', '    1.书写规范，{ 前和 ：后需要空格，每条规则后要加分号，如：\n    .title {\n        color: red;\n    }\n    2.去掉小数点前的 0,如：\n    .font-size: .8em;\n    3.除了常用的缩写，其他尽量不缩写\n    4.16进制颜色代码缩写，如：color: #ebc;\n    5.不要随意使用id属性设置css样式', '1', '2016-10-26 10:48:26', '1', '2016-10-26 10:52:54', '1');
INSERT INTO `manual_article` VALUES ('84', '96', '以下代码基本是固定模式，可以录制成宏。\n控制器：\n\n    public function actionList()\n    {\n        $query = (new User)->search();\n\n        $html = $query->getTable([\n            \'id\',\n            \'username\' => [\'search\' => true],\n            \'realname\' => [\'search\' => true, \'type\' => \'text\'],\n            \'roles\' => [\'header\' => \'角色\', \'value\' => function ($user) {\n                $roles = [];\n                foreach ($user->roles as $role) {\n                    $roles[] = Html::likeSpan($role->item_name);\n                }\n                return implode(\'，\', $roles);\n            }],\n            \'state\' => [\'search\' => \'select\'],\n            [\'type\' => [\'edit\' => \'member\', \'delete\' => \'ajaxDeleteMember\']]\n        ], [\n            \'addBtn\' => [\'member\' => \'添加管理员\']\n        ]);\n\n        return $this->render(\'list\', compact(\'html\'));\n    }\n\n视图：\n\n    <?= html ?>\n\n注：控制器中$query->getTable()方法的具体使用，可参看 common\\widgets\\Table 中的说明\n', '1', '2016-10-26 10:59:32', '1', '2016-10-26 10:59:32', '1');
INSERT INTO `manual_article` VALUES ('85', '97', '    $query = User::find()->join(\'posts\')->where(\'state\' => 1)->limit(5)->orderBy(\'id\');\n    $count = $query->count();\n    $model = $query->one();\n    $data = $query->all();', '1', '2016-10-26 11:07:58', '1', '2016-10-26 11:07:58', '1');
INSERT INTO `manual_article` VALUES ('86', '98', '> 本章将使用日期插件资源包作为例子说明一些常规js插件的使用方式\n所有js插件的资源包都放在 common\\assets 目录下\n\n1. 引入资源包（如以下方式是在视图中直接引入日期插件资源包）\n\n\n     <?php common\\assets\\DateAsset::register($this) ?>\n2.通过指定class名使用（如下使用\"datepicker\"class，即可使该input框点击后出现日期控件）\n\n    <input type=\"text\" class=\"datepicker\">\n3.如需定制该插件的参数，使用如下方式\n\n    <script>\n    \'datepicker\'.config({\n        dateFormat: \'yy-mm-dd\'\n    });\n    </script>\n\n> 由于每个js插件的实际用途不同，所以 common\\assets 下的类并不都是如上方式使用\n\n如 common\\assets\\JqueryFormAsset，是Ajax提交表单插件\n页面中引入资源包后，按如下方式进行使用：\n    \n    $(\"form\").ajaxSubmit($.config(\'ajaxSubmit\', {\n        success: function (msg) {\n            if (msg.state) {\n                $.alert(\'操作成功\', function () {\n                    parent.location.reload();\n                });\n            } else {\n                $.alert(msg.info);\n            }\n        }\n    }));', '1', '2016-10-26 11:25:00', '1', '2016-10-26 11:25:00', '1');
INSERT INTO `manual_article` VALUES ('87', '103', '> 统一使用 Navicat 工具操作mysql\n\n1. 数据库名和项目名一致，字符集统一使用 `utf8 -- UTF-8 Unicode`， 排序规则使用默认值（即不用手动选择）\n2. 表名全小写，用下划线连接单词，表名使用单词的单数形式\n3. 每个表必须设有主键，除了拓展表外，主键一律设置为`id`，并且设置自增、非null\n4. 如`user`表的拓展表`user_extend`，主键为`user_id`\n5. 非必填字段必须设置默认值（除了诸如text类型不能设置外），必填字段不必设置默认值\n6. 表示状态的字段一律使用`tinyint`类型，使用正数表示正面状态， 负数表示负面状态\n    如果仅表示有效、无效，则使用`1`表示有效，`-1`表示无效\n7. 预设字段在程序中都会进行相应的自动处理，根据情况设置\n    \n    \n    state: 表示逻辑删除的状态，设置该字段表示该表的所有记录均为逻辑删除，即更改state的值\n    created_at：表示创建时间，类型为datetime\n    created_by：表示创建人ID，类型为int\n    updated_at：表示修改时间，类型为datetime\n    updated_by：表示修改人ID，类型为int', '1', '2016-10-26 11:49:15', '1', '2016-12-02 11:02:51', '1');
INSERT INTO `manual_article` VALUES ('88', '104', '> 开发环境与正式环境在某些细节上会有一定的差异，导致上线时发生一些测试时没发生过的事\n以下就是这些差异的集锦\n\n    1.开发：图片验证码始终为123；正式：随机4个英文字母\n    2.开发：短信验证码始终为1234；正式：随机4个数字\n    3.开发：始终会加载FancyBoxAsset和JqueryFormAsset（因为调试工具会自动加载这两个资源包）；\n    4.开发：不校验AJAX的表单重复提交；\n    5.开发：错误时会显示程序调用的堆栈信息；正式：调用自定义的错误界面（默认是site/error）', '1', '2016-12-02 11:02:20', '1', '2016-12-02 11:02:20', '1');
INSERT INTO `manual_article` VALUES ('89', '105', '###通过 shell 链接 linux 服务器\n###安装 PHPSTUDY\n    wget -c http://lamp.phpstudy.net/phpstudy.bin #下载安装包\n    chmod +x phpstudy.bin    #权限设置\n    ./phpstudy.bin 　　　　#运行安装 \n   \n    \n###安装SVN\n     yum update\n     yum -y install subversion\n ###通过 SVN 下载项目到服务器\n    cd /phpstudy/www #进入www目录\n    svn co svn://114.55.63.141/app/app7（下载app7项目）\n ### NGINX 配置\n    上传vhost.conf到 /phpstudy/server/nginx/conf/vhost/vhost.conf ，并修改相应配置\n    修改nginx.conf，在http段中加入 \'server_tokens off;\' 隐藏服务器版本号\n    并注释 `server 中localhost的配置` 禁止直接使用IP方式访问服务器ss\n    在Navicat中通过SSH方式创建远程连接，并修改连接的配色为红色\n    创建数据库导入项目根目录下的init.sql文件初始化数据库\n###解析域名\n    主机名        记录类型        ip或别名\n     www             A           服务器ip\n      @              A           服务器ip\n      *              A           服务器ip\n    \n ### 配置参数\n    ln -s /phpstudy/server/php/bin/php /usr/sbin/php\n    ln -s /phpstudy/server/nginx/sbin/nginx /usr/local/bin/nginx\n    vi /phpstudy/server/php/etc/php.ini\n    修改数据库默认密码\n    update mysql.user set password=password(\'123\') where user=\'root\';\n    FLUSH PRIVILEGES;\n    修改date.timezone = Asia/Shanghai\n    修改expose_php = Off\n    chmod a+x yii\n    ./yii init/app\n    common/config/main-local.php (注意 localhost 改成 127.0.0.1) \n    common/config/Env.php （false prod）\n### 相关命令\n    phpstudy start|restart|stop （运行|重启|停止）所有服务\n    nginx -s reload 重启nginx\n    nohup ./yii init/hq & （开启采集数据）\n', '1', '2017-04-12 19:30:33', '15', '2017-08-08 11:53:23', '1');
INSERT INTO `manual_article` VALUES ('91', '111', '> 前戏\n\n    使用yum下载各种依赖库，具体参考baidu结果\n    此处假定已安装好phpstudy\n\n> 下载与解压\n    \n    直接从官方下载 \'.tar.gz\' 版本的源码包，上传到服务器\n    执行命令解压：\'tar -zxvf\'\n> 配置\n\n    ./configure \\\n    --prefix=/usr/local/php7 \\\n    --with-config-file-path=/usr/local/php7/etc \\\n    --with-config-file-scan-dir=/usr/local/php7/etc/php.d \\\n    --with-mcrypt=/usr/include \\\n    --enable-mysqlnd \\\n    --with-mysqli \\\n    --with-pdo-mysql \\\n    --enable-fpm  \\\n    --with-gd \\\n    --with-iconv \\\n    --with-zlib \\\n    --enable-xml \\\n    --enable-shmop \\\n    --enable-sysvsem \\\n    --enable-inline-optimization \\\n    --enable-mbregex \\\n    --enable-mbstring \\\n    --enable-ftp \\\n    --enable-gd-native-ttf \\\n    --with-openssl \\\n    --enable-pcntl \\\n    --enable-sockets \\\n    --with-xmlrpc \\\n    --enable-zip \\\n    --enable-soap \\\n    --without-pear \\\n    --with-gettext \\\n    --enable-session \\\n    --with-curl \\\n    --with-jpeg-dir \\\n    --with-freetype-dir \\\n    --enable-opcache \\\n    --with-pcre-regex \\\n    --with-png-dir\n > 安装\n\n    make && make install\n> 复制配置文件\n\n    cp 解压包里/php.ini.development /usr/local/php7/etc/php.ini\n    cp /usr/local/php7/etc/php-fpm.conf.default /usr/local/php7/etc/php-fpm.conf\n    cp /usr/local/php7/etc/php-fpm.d/www.conf.default /usr/local/php7/etc/php-fpm.d/www.conf\n    修改 www.conf 中运行用户与组为www\n> 创建快捷命令与执行\n    \n    ln -s /usr/local/php7/bin/php /usr/sbin/php\n    ln -s /usr/local/php7/sbin/php-fpm /usr/sbin/php-fpm\n    cp 源码包/sapi/fpm/init.d.php-fpm /etc/init.d/php-fpm\n    chmod 755 /etc/init.d/php-fpm\n    chkconfig php-fpm on\n    service php-fpm start \n\n', '1', '2017-06-01 23:20:40', '1', '2017-08-07 12:03:43', '1');
INSERT INTO `manual_article` VALUES ('92', '112', '> 面板安装\n\n    1. yum install -y wget && wget -O install.sh http://download.bt.cn/install/install.sh && sh install.sh\n    2. 下载完成后，输入y，进行安装\n> 面板配置\n \n    1. 修改面板别名\n    2. 修改端口号为9999（如果服务器在阿里云中，则确保在阿里云控制台中的云ECS安全组中已解锁端口封印）\n    3. 修改登录账号为chiswill\n    4. 修改默认登录密码为标准密码\n > 软件安装\n\n    1. nginx-tengine\n    2. mysql5.7\n    3. php7.0\n> PHP配置\n   \n    1. 修改上传限制为10M\n    2. 修改超时时间为30s\n    3. 禁用函数中，取消`exec`,`passthru`,`system`,`symlink`\n    \n > 服务器端配置\n\n    1. 从xinyu-5中，同步 ~/.bashrc 的文件内容，执行：source ~/.bashrc\n    2. 安装SVN： yum update && yum -y install subversion\n    3. svn co svn://114.55.63.141/app/app23（下载app23项目）\n> 添加网站\n    \n    1. 设置-网站目录，取消防跨域攻击\n    2. 设置-伪静态，规则设置为 \"mvc\"', '1', '2017-08-18 11:43:36', '1', '2017-08-24 18:45:23', '1');

-- ----------------------------
-- Table structure for manual_collection
-- ----------------------------
DROP TABLE IF EXISTS `manual_collection`;
CREATE TABLE `manual_collection` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL COMMENT '用户ID',
  `menu_id` int(11) NOT NULL COMMENT '收藏的菜单ID',
  `state` tinyint(4) DEFAULT '1',
  `created_at` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_id` (`user_id`,`menu_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='手册收藏表';

-- ----------------------------
-- Records of manual_collection
-- ----------------------------
INSERT INTO `manual_collection` VALUES ('1', '13', '104', '1', '2017-09-29 15:25:05', '13', '2017-09-29 15:25:05', '13');

-- ----------------------------
-- Table structure for manual_menu
-- ----------------------------
DROP TABLE IF EXISTS `manual_menu`;
CREATE TABLE `manual_menu` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL COMMENT '菜单名',
  `pid` int(11) DEFAULT '0',
  `level` smallint(6) DEFAULT '1' COMMENT '层级',
  `code` varchar(250) DEFAULT '' COMMENT '从属排序值',
  `sort` int(11) DEFAULT '1' COMMENT '排序值',
  `child_num` int(11) DEFAULT '0' COMMENT '子集数',
  `url` varchar(250) DEFAULT '' COMMENT '链接',
  `is_show` tinyint(4) DEFAULT '1' COMMENT '是否显示',
  `category` tinyint(4) DEFAULT '1' COMMENT '菜单分类',
  `state` tinyint(4) DEFAULT '1' COMMENT '状态',
  `created_at` datetime DEFAULT NULL COMMENT '创建时间',
  `created_by` int(11) DEFAULT NULL COMMENT '创建人',
  `updated_at` datetime DEFAULT NULL COMMENT '编辑时间',
  `updated_by` int(11) DEFAULT NULL COMMENT '编辑人',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=113 DEFAULT CHARSET=utf8 COMMENT='手册菜单表';

-- ----------------------------
-- Records of manual_menu
-- ----------------------------
INSERT INTO `manual_menu` VALUES ('4', 'PHP 代码规范', '0', '1', '1', '1', '6', '', '1', '1', '1', '2015-12-09 16:41:05', '1', '2016-01-07 17:52:50', '1');
INSERT INTO `manual_menu` VALUES ('26', '通用规范', '4', '2', '1-1', '0', '0', '', '1', '1', '1', '2016-01-07 17:55:40', '1', '2016-01-07 17:56:46', '1');
INSERT INTO `manual_menu` VALUES ('27', '控制器', '4', '2', '1-2', '1', '0', '', '1', '1', '1', '2016-01-07 17:55:55', '1', '2016-01-07 17:58:46', '1');
INSERT INTO `manual_menu` VALUES ('28', '模型', '4', '2', '1-3', '2', '0', '', '1', '1', '1', '2016-01-07 17:58:52', '1', '2016-01-07 17:58:52', '1');
INSERT INTO `manual_menu` VALUES ('29', '视图', '4', '2', '1-4', '3', '0', '', '1', '1', '1', '2016-01-07 17:59:02', '1', '2016-01-07 17:59:02', '1');
INSERT INTO `manual_menu` VALUES ('30', '数据库', '4', '2', '1-5', '4', '0', '', '1', '1', '1', '2016-01-07 17:59:13', '1', '2016-01-07 17:59:13', '1');
INSERT INTO `manual_menu` VALUES ('31', '注释', '4', '2', '1-6', '5', '0', '', '1', '1', '1', '2016-01-07 17:59:21', '1', '2016-01-07 17:59:21', '1');
INSERT INTO `manual_menu` VALUES ('66', 'BPM', '0', '1', '7', '4', '1', '', '1', '1', '1', '2016-03-08 14:56:51', '1', '2016-03-08 14:56:51', '1');
INSERT INTO `manual_menu` VALUES ('67', '用户使用手册', '66', '2', '7-1', '67', '0', '', '1', '1', '1', '2016-03-08 14:57:26', '1', '2016-03-08 14:57:26', '1');
INSERT INTO `manual_menu` VALUES ('69', 'mysql开发规范', '0', '1', '8', '5', '0', '', '1', '1', '1', '2016-03-25 19:05:20', '1', '2016-08-10 10:58:30', '1');
INSERT INTO `manual_menu` VALUES ('70', '服务器常见故障排查方法', '0', '1', '9', '6', '0', '', '1', '1', '1', '2016-03-25 19:12:39', '1', '2016-03-25 19:13:38', '1');
INSERT INTO `manual_menu` VALUES ('94', '项目开发文档', '0', '1', '7', '3', '6', '', '1', '1', '1', '2016-10-04 17:00:31', '1', '2016-10-04 17:00:31', '1');
INSERT INTO `manual_menu` VALUES ('95', '表单 - Form', '94', '2', '7-1', '0', '0', '', '1', '1', '1', '2016-10-04 17:00:59', '1', '2016-10-04 17:01:35', '1');
INSERT INTO `manual_menu` VALUES ('96', '列表 - List', '94', '2', '7-2', '1', '0', '', '1', '1', '1', '2016-10-04 17:01:24', '1', '2016-10-04 17:01:43', '1');
INSERT INTO `manual_menu` VALUES ('97', '查询 - Query', '94', '2', '7-3', '2', '0', '', '1', '1', '1', '2016-10-04 17:03:11', '1', '2016-10-04 17:03:11', '1');
INSERT INTO `manual_menu` VALUES ('98', 'JS插件 - Plugin', '94', '2', '7-4', '3', '0', '', '1', '1', '1', '2016-10-04 17:04:15', '1', '2016-10-04 17:04:15', '1');
INSERT INTO `manual_menu` VALUES ('99', '前段代码规范', '0', '1', '8', '2', '3', '', '1', '1', '1', '2016-10-25 15:36:23', '1', '2016-10-25 15:36:58', '1');
INSERT INTO `manual_menu` VALUES ('100', 'HTML', '99', '2', '8-1', '100', '0', '', '1', '1', '1', '2016-10-25 15:37:04', '1', '2016-10-25 15:37:04', '1');
INSERT INTO `manual_menu` VALUES ('101', 'JS', '99', '2', '8-2', '101', '0', '', '1', '1', '1', '2016-10-25 15:37:09', '1', '2016-10-25 15:37:09', '1');
INSERT INTO `manual_menu` VALUES ('102', 'CSS', '99', '2', '8-3', '102', '0', '', '1', '1', '1', '2016-10-25 15:37:16', '1', '2016-10-25 15:37:16', '1');
INSERT INTO `manual_menu` VALUES ('103', '建表规范', '94', '2', '7-5', '103', '0', '', '1', '1', '1', '2016-10-26 11:33:52', '1', '2016-10-26 11:33:52', '1');
INSERT INTO `manual_menu` VALUES ('104', '环境差异', '94', '2', '7-6', '104', '0', '', '1', '1', '1', '2016-12-02 10:52:43', '1', '2016-12-02 10:52:43', '1');
INSERT INTO `manual_menu` VALUES ('105', 'Linux 环境搭建', '0', '1', '9', '105', '2', '', '1', '1', '1', '2017-04-12 19:23:21', '15', '2017-04-12 19:23:21', '15');
INSERT INTO `manual_menu` VALUES ('111', 'PHP7', '105', '2', '9-1', '111', '0', '', '1', '1', '1', '2017-06-01 23:12:08', '1', '2017-06-01 23:12:08', '1');
INSERT INTO `manual_menu` VALUES ('112', '宝塔面板', '105', '2', '9-2', '112', '0', '', '1', '1', '1', '2017-08-18 11:14:08', '1', '2017-08-18 11:14:08', '1');

-- ----------------------------
-- Table structure for manual_version
-- ----------------------------
DROP TABLE IF EXISTS `manual_version`;
CREATE TABLE `manual_version` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `menu_id` int(11) NOT NULL COMMENT '文章ID',
  `content` mediumtext COMMENT '内容',
  `action` tinyint(4) DEFAULT '1' COMMENT '操作类型：1创建，2更新，3恢复，4删除',
  `state` tinyint(4) DEFAULT '1',
  `created_at` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=115 DEFAULT CHARSET=utf8 COMMENT='手册版本表';

-- ----------------------------
-- Records of manual_version
-- ----------------------------
INSERT INTO `manual_version` VALUES ('1', '93', '# 编程之禅\n* 记得早些年看过一本叫《编程之禅》的小书，是美国的编程大师Geoffrey James写的。书中描写了一位传说中的编程大师，关于他的寓言故事写的非常神秘有趣。尽管有对东方文化存在不了解的地方，甚至将禅宗思想与神秘的西藏还有什么忍者揉到一起。但梳理所讲述的内容都有深刻的寓意。那就是，除了理性的编程思维之外，还存在一种思想的感性升华，一种领悟编程本质的思想境界。这种思想境界就是编程之禅！\n* 其实，编程之禅无处不在。把代码排列得更美观些；让标识符的命名更合理些；让代码更直观和简洁一些；排出一个Bug之后的喜悦；解决一个难题后的领悟。无时无刻不体现着编程之禅。禅本来就如人饮水，冷暖自知。各自的感悟不同，但都能得到自己的快乐。\n* 编程之禅也是一种编程的态度，以一种轻松火大的心态对待变成。无需将编程看成一项工作，而将其看作一种生活方式。领悟了禅机的程序员把程序看成自身思想的延续，他们总喜欢以一种天真快乐的童心来看待现实世界。\n 编程之禅很难用言语来描述。编程之禅机就像生活经验一样不可以传授，只能由自己去经历，去体验，去思索，去领悟。编程之禅完全可以自行修炼，或渐悟，或顿悟，完全看自己的机缘。当然也可以求教禅师指点迷津。\n* 禅师从不会苦口婆心地教人该如何编程，如果人的内心还没有开悟，再多的外来灌输都是枉费心机。有人历经磨难苦苦思索而仍不得其法时，得遇禅师一语点化，或如当头棒喝间的猛然醒悟，或似醍醐灌顶般的豁然开朗，其喜悦的心情是不言而喻的。\n* 禅师自己也还在修炼，因为他知道编程之禅是没有止境的。禅师总是虚心地向所有人学习，因为他知道每一个都有值得学习的地方。禅师从不怕犯错误，当他意识到自己错了的时候，会毫不犹豫地承认自己的错误。禅师也从不为自己的错误辩解，他会在放下错误后立即轻松前进。\n* 禅师之所以能轻松快乐地编程，因为他放下了一切可以放下的东西。名利之争与禅师无关，禅师从来不把这些包袱背在身上。编程之禅从来不拘泥于某种固定的思维模式，禅师们也从来不把思维模式的包袱背在身上。禅师们甚至放下了自我，他们之所以能飞翔，是因为把自我看得很轻很轻。\n* 我们在学习编程的过程中，的确需要来一点清新的禅风。清新的禅风可以净化我们的编程心灵，让我们看到一个更广阔的天地，思想也会更深邃和睿智。我们不会纠缠于无聊的争执中，不会局限于固定的思维模式，也不会受困于狭隘的私心。这道清新的禅风可以让我们自由自在地飞翔在广阔的编程天空中，享受着编程的快乐。', '2', '1', '2016-09-04 19:35:03', '1');
INSERT INTO `manual_version` VALUES ('2', '93', '# 编程之禅\n* 记得早些年看过一本叫《编程之禅》的小书，是美国的编程大师Geoffrey James写的。书中描写了一位传说中的编程大师，关于他的寓言故事写的非常神秘有趣。尽管有对东方文化存在不了解的地方，甚至将禅宗思想与神秘的西藏还有什么忍者揉到一起。但梳理所讲述的内容都有深刻的寓意。那就是，除了理性的编程思维之外，还存在一种思想的感性升华，一种领悟编程本质的思想境界。这种思想境界就是编程之禅！\n* 其实，编程之禅无处不在。把代码排列得更美观些；让标识符的命名更合理些；让代码更直观和简洁一些；排出一个Bug之后的喜悦；解决一个难题后的领悟。无时无刻不体现着编程之禅。禅本来就如人饮水，冷暖自知。各自的感悟不同，但都能得到自己的快乐。\n* 编程之禅也是一种编程的态度，以一种轻松火大的心态对待编程。无需将编程看成一项工作，而将其看作一种生活方式。领悟了禅机的程序员把程序看成自身思想的延续，他们总喜欢以一种天真快乐的童心来看待现实世界。\n 编程之禅很难用言语来描述。编程之禅机就像生活经验一样不可以传授，只能由自己去经历，去体验，去思索，去领悟。编程之禅完全可以自行修炼，或渐悟，或顿悟，完全看自己的机缘。当然也可以求教禅师指点迷津。\n* 禅师从不会苦口婆心地教人该如何编程，如果人的内心还没有开悟，再多的外来灌输都是枉费心机。有人历经磨难苦苦思索而仍不得其法时，得遇禅师一语点化，或如当头棒喝间的猛然醒悟，或似醍醐灌顶般的豁然开朗，其喜悦的心情是不言而喻的。\n* 禅师自己也还在修炼，因为他知道编程之禅是没有止境的。禅师总是虚心地向所有人学习，因为他知道每一个都有值得学习的地方。禅师从不怕犯错误，当他意识到自己错了的时候，会毫不犹豫地承认自己的错误。禅师也从不为自己的错误辩解，他会在放下错误后立即轻松前进。\n* 禅师之所以能轻松快乐地编程，因为他放下了一切可以放下的东西。名利之争与禅师无关，禅师从来不把这些包袱背在身上。编程之禅从来不拘泥于某种固定的思维模式，禅师们也从来不把思维模式的包袱背在身上。禅师们甚至放下了自我，他们之所以能飞翔，是因为把自我看得很轻很轻。\n* 我们在学习编程的过程中，的确需要来一点清新的禅风。清新的禅风可以净化我们的编程心灵，让我们看到一个更广阔的天地，思想也会更深邃和睿智。我们不会纠缠于无聊的争执中，不会局限于固定的思维模式，也不会受困于狭隘的私心。这道清新的禅风可以让我们自由自在地飞翔在广阔的编程天空中，享受着编程的快乐。', '2', '1', '2016-09-04 19:35:23', '1');
INSERT INTO `manual_version` VALUES ('3', '4', '## PHP 代码规范\n\n> 目标：\n1. 建立节操感、责任感\n2. 减少阅读障碍，降低维护成本\n3. 遵循统一模式的代码更易于扩展\n', '2', '1', '2016-09-04 19:37:53', '1');
INSERT INTO `manual_version` VALUES ('4', '26', '>代码格式采用PSR规范，以下是该规范的简练要点\n\n1. 所有变量名尽量控制不出现拼音类型，使用尽量语义化、简单易懂的英文命名\n2. 所有变量都用驼峰法明明，私有方法、私有变量都采用下划线开头，如：`private function _getName()`\n3. `,`号和`;`号后要加空格，其他操作符、关键字前后都要加空格\n4. 双引号中的变量必须使用{}包起来；如果大段文本中包含变量，则采用分界符`<<<EOT`\n5. 所有SQL语句的关键字全部大写，并且必须美化SQL语句格式\n6. if 下即使只有一条语句，也必须用花括号包起来\n7. PHP文件禁止使用`?>`结束标签，每个php文件最后必须是空行\n8. 常量名必须全大写，用下划线分割单词', '2', '1', '2016-09-04 19:45:42', '1');
INSERT INTO `manual_version` VALUES ('5', '27', '1. action名和视图名保持一致，如 `function actionViewArticle()` 对应渲染的视图为 `$this->render(\'viewArticle\')`\n2. 所有纯ajax请求处理的方法全部以ajax为前缀命名，如 `actionAjaxCreate()`，form表单提交除外\n3. Ajax返回使用`self::success`和`self::error()`，如果是输出模型错误，直接self::error($model)即可\n4. 表单的创建与修改，尽量参考以下代码所示：\n\n\n    public function actionUser($id = null)\n    {\n        $user = User::findModel($id);\n        if ($user->load($_POST)) {\n            if ($user->save()) {\n                return self::success();\n            } else {\n                return self::error($user);\n            }\n        }\n        return $this->render(\'user\', compact(\'user\'));\n    }', '2', '1', '2016-09-04 19:47:26', '1');
INSERT INTO `manual_version` VALUES ('6', '27', '1. action名和视图名保持一致，如 `function actionViewArticle()` 对应渲染的视图为 `$this->render(\'viewArticle\')`\n2. 所有纯ajax请求处理的方法全部以ajax为前缀命名，如 `actionAjaxCreate()`，form表单提交除外\n3. Ajax返回使用`self::success`和`self::error()`，如果是输出模型错误，直接self::error($model)即可\n4. 表单的创建与修改，参考以下代码：\n\n\n    public function actionUser($id = null)\n    {\n        $user = User::findModel($id);\n        if ($user->load($_POST)) {\n            if ($user->save()) {\n                return self::success();\n            } else {\n                return self::error($user);\n            }\n        }\n        return $this->render(\'user\', compact(\'user\'));\n    }\n5.列表的创建，参考以下代码：\n\n    public function actionList()\n    {\n        $query = (new User)->search();\n        $html = $query->getTable([\n            \'id\',\n            \'name\' => \'姓名\',\n            \'age\' => function ($row) {\n                return $row[\'age\'] . \'岁\';\n            },\n            \'reg_time\' => [\'header\' => \'注册日期\', \'value\' => function ($row) {\n                return date(\'Y-m-d H:i:s\', $row[\'reg_time\']);\n            }],\n            [\'type\' => [\'edit\', \'delete\' => \'deleteUser\']]\n        ]);\n        return $this->render(\'list\', compact(\'html\'));\n    }', '2', '1', '2016-09-04 19:53:40', '1');
INSERT INTO `manual_version` VALUES ('7', '28', '1. 模型必须使用生成工具生成！\n2. 表示状态的字段必须使用生成工具生成对应的map方法和value方法，并为该状态字段设置类常量\n3. 每个列表条件都采用一个模型方法进行封装，以便控制器调用，如下所示：\n\n\n    public function listQuery()\n    {\n        return $this->search()\n                    ->with([\'user\', \'post\']\n                    ->andWhere([\'user.state\' => User::STATE_VALID])\n                    ->orderBy(\'post.id DESC\');\n    }\n4.当有字段变动时，同一个模型可反复生成，但必须确保公共父类模型中的`rules()`、`attributeLabels()`、`search()`方法没被修改过，否则所做的修改会被覆盖！\n5.为每一个表单设置一个场景名，并在`scenario()`方法中设定该场景中验证哪些字段。', '2', '1', '2016-09-04 20:08:41', '1');
INSERT INTO `manual_version` VALUES ('8', '29', '1. 无特殊情况，不允许直接加载js和css文件，而应使用Yii2的`AssetBundle`对象进行静态资源管理\n2. 使用`if/endif` 语法代替花括号语法\n3. 使用`<?= $name ?>` 代替 `<?php echo $name; ?>`\n4. 无特殊情况，禁止使用`style`属性和`on`系列事件属性\n5. a标签空链接使用href=\"javascript:;\"，禁止使用href=\"#\"\n6. 表单代码按照以下代码所示：\n\n\n    <?php $form = self::beginForm() ?>\n    <?= $model->title(\'表单标题\') ?>\n    <?= $form->field($model, \'name\') ?>\n    <?= $form->field($model, \'state\')->radioList() ?>\n    <?= $form->field($model, \'password\')->passwordInput() ?>    \n    <?= $form->submit(\'提交按钮名称\') ?>\n    <?php self::endForm() ?>\n', '2', '1', '2016-09-04 20:12:06', '1');
INSERT INTO `manual_version` VALUES ('9', '30', '1. 主键必须是id\n2. 字段名和表名用下划线分割\n3. 每个表的 `created_at、created_by、updated_at、updated_by`等字段，分别表示创建时间、创建人id、修改时间、修改人；如无必要，不要主动修改这些字段的值，他们会被框架自动处理\n4. 如果表中含有`state`字段，表示该表采用逻辑删除规则，则查询时必须加上诸如`state=User::STATE_VALID`条件，来确保逻辑的一致性', '2', '1', '2016-09-04 20:15:23', '1');
INSERT INTO `manual_version` VALUES ('10', '30', '1. 主键必须是id\n2. 字段名和表名用下划线分割\n3. 每个表的 `created_at、created_by、updated_at、updated_by`等字段，分别表示创建时间、创建人id、修改时间、修改人；如无必要，不要主动修改这些字段的值，他们会被框架自动处理\n4. 如果表中含有`state`字段，表示该表采用逻辑删除规则，则查询时必须加上诸如`state=User::STATE_VALID`的条件，来确保逻辑的一致性', '2', '1', '2016-09-04 20:18:07', '1');
INSERT INTO `manual_version` VALUES ('11', '31', '>采用PHPDoc文档注释标准\n\n1. 所填写的每个注释标记，必须表达清楚，不然不如不写\n2. 每个公共方法至少要有最基础的汉字说明\n3. 后台的公共action方法，使用自定义的标记 `@authname`来注明权限名称', '2', '1', '2016-09-04 20:23:23', '1');
INSERT INTO `manual_version` VALUES ('12', '66', '#该部分主要为BPM说明，一般情况不用看', '2', '1', '2016-09-04 20:24:05', '1');
INSERT INTO `manual_version` VALUES ('13', '93', '\n* 记得早些年看过一本叫《编程之禅》的小书，是美国的编程大师Geoffrey James写的。书中描写了一位传说中的编程大师，关于他的寓言故事写的非常神秘有趣。尽管有对东方文化存在不了解的地方，甚至将禅宗思想与神秘的西藏还有什么忍者揉到一起。但梳理所讲述的内容都有深刻的寓意。那就是，除了理性的编程思维之外，还存在一种思想的感性升华，一种领悟编程本质的思想境界。这种思想境界就是编程之禅！\n* 其实，编程之禅无处不在。把代码排列得更美观些；让标识符的命名更合理些；让代码更直观和简洁一些；排出一个Bug之后的喜悦；解决一个难题后的领悟。无时无刻不体现着编程之禅。禅本来就如人饮水，冷暖自知。各自的感悟不同，但都能得到自己的快乐。\n* 编程之禅也是一种编程的态度，以一种轻松火大的心态对待编程。无需将编程看成一项工作，而将其看作一种生活方式。领悟了禅机的程序员把程序看成自身思想的延续，他们总喜欢以一种天真快乐的童心来看待现实世界。\n 编程之禅很难用言语来描述。编程之禅机就像生活经验一样不可以传授，只能由自己去经历，去体验，去思索，去领悟。编程之禅完全可以自行修炼，或渐悟，或顿悟，完全看自己的机缘。当然也可以求教禅师指点迷津。\n* 禅师从不会苦口婆心地教人该如何编程，如果人的内心还没有开悟，再多的外来灌输都是枉费心机。有人历经磨难苦苦思索而仍不得其法时，得遇禅师一语点化，或如当头棒喝间的猛然醒悟，或似醍醐灌顶般的豁然开朗，其喜悦的心情是不言而喻的。\n* 禅师自己也还在修炼，因为他知道编程之禅是没有止境的。禅师总是虚心地向所有人学习，因为他知道每一个都有值得学习的地方。禅师从不怕犯错误，当他意识到自己错了的时候，会毫不犹豫地承认自己的错误。禅师也从不为自己的错误辩解，他会在放下错误后立即轻松前进。\n* 禅师之所以能轻松快乐地编程，因为他放下了一切可以放下的东西。名利之争与禅师无关，禅师从来不把这些包袱背在身上。编程之禅从来不拘泥于某种固定的思维模式，禅师们也从来不把思维模式的包袱背在身上。禅师们甚至放下了自我，他们之所以能飞翔，是因为把自我看得很轻很轻。\n* 我们在学习编程的过程中，的确需要来一点清新的禅风。清新的禅风可以净化我们的编程心灵，让我们看到一个更广阔的天地，思想也会更深邃和睿智。我们不会纠缠于无聊的争执中，不会局限于固定的思维模式，也不会受困于狭隘的私心。这道清新的禅风可以让我们自由自在地飞翔在广阔的编程天空中，享受着编程的快乐。', '2', '1', '2016-09-06 11:08:02', '2');
INSERT INTO `manual_version` VALUES ('14', '27', '1. action名和视图名保持一致，如 `function actionViewArticle()` 对应渲染的视图为 `$this->render(\'viewArticle\')`\n2. 所有纯ajax请求处理的方法全部以ajax为前缀命名，如 `actionAjaxCreate()`，form表单提交除外\n3. Ajax返回使用`return self::success()`和`return self::error()`，如果是输出模型错误，则`return self::error($model)`即可\n4. 表单的创建与修改，参考以下代码：\n\n\n    public function actionUser($id = null)\n    {\n        $user = User::findModel($id);\n        if ($user->load($_POST)) {\n            if ($user->save()) {\n                return self::success();\n            } else {\n                return self::error($user);\n            }\n        }\n        return $this->render(\'user\', compact(\'user\'));\n    }\n5.列表的创建，参考以下代码：\n\n    public function actionList()\n    {\n        $query = (new User)->search();\n        $html = $query->getTable([\n            \'id\',\n            \'name\' => \'姓名\',\n            \'age\' => function ($row) {\n                return $row[\'age\'] . \'岁\';\n            },\n            \'reg_time\' => [\'header\' => \'注册日期\', \'value\' => function ($row) {\n                return date(\'Y-m-d H:i:s\', $row[\'reg_time\']);\n            }],\n            [\'type\' => [\'edit\', \'delete\' => \'deleteUser\']]\n        ]);\n        return $this->render(\'list\', compact(\'html\'));\n    }', '2', '1', '2016-09-06 11:13:20', '2');
INSERT INTO `manual_version` VALUES ('15', '94', '', '1', '1', '2016-10-04 17:00:31', '1');
INSERT INTO `manual_version` VALUES ('16', '95', '', '1', '1', '2016-10-04 17:00:59', '1');
INSERT INTO `manual_version` VALUES ('17', '96', '', '1', '1', '2016-10-04 17:01:24', '1');
INSERT INTO `manual_version` VALUES ('18', '97', '', '1', '1', '2016-10-04 17:03:11', '1');
INSERT INTO `manual_version` VALUES ('19', '98', '', '1', '1', '2016-10-04 17:04:15', '1');
INSERT INTO `manual_version` VALUES ('20', '94', '#常规功能的开发示例', '2', '1', '2016-10-04 17:05:21', '1');
INSERT INTO `manual_version` VALUES ('21', '95', '以下代码基本是固定模式，可以录制成宏。\n控制器：\n\n    public function actionDetail($id = 0)\n    {\n        $model = User::findModel($id);\n\n        if ($model->load(post()) {\n            if ($model->save()) {\n                return success();\n            } else {\n                return error($model);\n            }\n        }\n\n        return $this->render(\'detail\', compact(\'model\'));\n    }\n\n视图：\n\n    <?php $form = self::beginForm() ?>\n    <?= $model->title(\'用户\') ?>\n    <?= $form->field($model, \'username\') ?>\n    <?= $form->submit($model) ?>\n    <?php self::endForm() ?>\n\n    <script>\n    $(function () {\n        $(\"#submitBtn\").click(function () {\n            $(\"form\").ajaxSubmit($.config(\'ajaxSubmit\', {\n                success: function (msg) {\n                    if (msg.state) {\n                        $.alert(msg.info || \'操作成功\', function () {\n                            parent.location.reload();\n                        });\n                    } else {\n                        $.alert(msg.info);\n                    }\n                }\n            }));\n            return false;\n        });\n    });\n    </script>\n', '2', '1', '2016-10-04 17:29:34', '1');
INSERT INTO `manual_version` VALUES ('22', '99', '', '1', '1', '2016-10-25 15:36:23', '1');
INSERT INTO `manual_version` VALUES ('23', '100', '', '1', '1', '2016-10-25 15:37:04', '1');
INSERT INTO `manual_version` VALUES ('24', '101', '', '1', '1', '2016-10-25 15:37:09', '1');
INSERT INTO `manual_version` VALUES ('25', '102', '', '1', '1', '2016-10-25 15:37:16', '1');
INSERT INTO `manual_version` VALUES ('26', '100', '1.类名全小写，多单词时以 - 号分隔，如：\n`<div class=\"main-nav\"></div>`\n2.每个层级缩进4个空格，每个层级下如果不是标签，则需要换行显示如：\n\n\n    <ul>\n        <li>a</li>\n        <li>\n            <span>b</span>\n        </li>\n    </ul>\n\n3.不允许使用style属性\n4. a标签的href属性设为“#”', '2', '1', '2016-10-25 15:47:44', '1');
INSERT INTO `manual_version` VALUES ('27', '101', '1. 变量驼峰式命名\n2. 每条语句最后必须加上分号\n3. 多条声明语句采用如下格式\n    \n    \n    var a = 1,\n        b = 2,\n        c = 3;\n4.存放jquery对象的变量必须以 $ 符作为前缀命名，如：`$a = $(\".link\");`\n5.事件绑定方法必须保存当前触发元素到变量中（function关键字右边必须含有一个空格），如：\n\n\n\n    $(\".container\").on(\'click\', \'.btn\', function () {\n        var $this = $(this);\n        // other code...\n    });\n6.标签中存放的变量必须放到data属性中，jquery中使用data方法来获取，如：\n\n\n    Html:\n    <input type=\"text\" data-key=\"1\" id=\"userInput\">\n    Js:\n    var key = $(\"#userInput\").data(\'key\');', '2', '1', '2016-10-26 10:40:27', '1');
INSERT INTO `manual_version` VALUES ('28', '102', '    1.书写规范，{ 前和 ：后需要空格，每条规则后要加分号，如：\n    .title {\n        color: red;\n    }\n    2.去掉小数点前的 0,如：\n    .font-size: .8em;\n    3.简写命名，必须要让人能看懂你的命名才能简写，如：\n    navigation -> nav\n    button -> btn\n    4.16进制颜色代码缩写，如：color: #ebc;', '2', '1', '2016-10-26 10:48:26', '1');
INSERT INTO `manual_version` VALUES ('29', '102', '    1.书写规范，{ 前和 ：后需要空格，每条规则后要加分号，如：\n    .title {\n        color: red;\n    }\n    2.去掉小数点前的 0,如：\n    .font-size: .8em;\n    3.除了常用的缩写，其他尽量不缩写\n    4.16进制颜色代码缩写，如：color: #ebc;\n    5.不要随意使用id属性设置css样式', '2', '1', '2016-10-26 10:52:54', '1');
INSERT INTO `manual_version` VALUES ('30', '96', '以下代码基本是固定模式，可以录制成宏。\n控制器：\n\n    public function actionList()\n    {\n        $query = (new User)->search();\n\n        $html = $query->getTable([\n            \'id\',\n            \'username\' => [\'search\' => true],\n            \'realname\' => [\'search\' => true, \'type\' => \'text\'],\n            \'roles\' => [\'header\' => \'角色\', \'value\' => function ($user) {\n                $roles = [];\n                foreach ($user->roles as $role) {\n                    $roles[] = Html::likeSpan($role->item_name);\n                }\n                return implode(\'，\', $roles);\n            }],\n            \'state\' => [\'search\' => \'select\'],\n            [\'type\' => [\'edit\' => \'member\', \'delete\' => \'ajaxDeleteMember\']]\n        ], [\n            \'addBtn\' => [\'member\' => \'添加管理员\']\n        ]);\n\n        return $this->render(\'list\', compact(\'html\'));\n    }\n\n视图：\n\n    <?= html ?>\n\n注：控制器中$query->getTable()方法的具体使用，可参看 common\\widgets\\Table 中的说明\n', '2', '1', '2016-10-26 10:59:32', '1');
INSERT INTO `manual_version` VALUES ('31', '97', '    $query = User::find()->join(\'posts\')->where(\'state\' => 1)->limit(5)->orderBy(\'id\');\n    $count = $query->count();\n    $model = $query->one();\n    $data = $query->all();', '2', '1', '2016-10-26 11:07:58', '1');
INSERT INTO `manual_version` VALUES ('32', '98', '> 本章将使用日期插件资源包作为例子说明一些常规js插件的使用方式\n所有js插件的资源包都放在 common\\assets 目录下\n\n1. 引入资源包（如以下方式是在视图中直接引入日期插件资源包）\n\n\n     <?php common\\assets\\DateAsset::register($this) ?>\n2.通过指定class名使用（如下使用\"datepicker\"class，即可使该input框点击后出现日期控件）\n\n    <input type=\"text\" class=\"datepicker\">\n3.如需定制该插件的参数，使用如下方式\n\n    <script>\n    \'datepicker\'.config({\n        dateFormat: \'yy-mm-dd\'\n    });\n    </script>\n\n> 由于每个js插件的实际用途不同，所以 common\\assets 下的类并不都是如上方式使用\n\n如 common\\assets\\JqueryFormAsset，是Ajax提交表单插件\n页面中引入资源包后，按如下方式进行使用：\n    \n    $(\"form\").ajaxSubmit($.config(\'ajaxSubmit\', {\n        success: function (msg) {\n            if (msg.state) {\n                $.alert(\'操作成功\', function () {\n                    parent.location.reload();\n                });\n            } else {\n                $.alert(msg.info);\n            }\n        }\n    }));', '2', '1', '2016-10-26 11:25:00', '1');
INSERT INTO `manual_version` VALUES ('33', '103', '', '1', '1', '2016-10-26 11:33:52', '1');
INSERT INTO `manual_version` VALUES ('34', '103', '> 统一使用 Navicat 工具操作mysql\n\n1. 数据库名和项目名一致，字符集统一使用 `utf8 -- UTF-8 Unicode`， 排序规则使用默认值（即不用手动选择）\n2. 表名全小写，用下划线连接单词，表名使用单词的单数形式\n3. 每个表必须设有主键，除了拓展表外，主键一律设置为`id`，并且设置自增、非null\n4. 如`user`表的拓展表`user_extend`，主键为`user_id`\n5. 非必填字段必须设置默认值（除了诸如text类型不能设置外），必填字段不必设置默认值\n6. 表示状态的字段一律使用`tinyint`类型，使用正数表示正面状态， 负数表示负面状态\n    如果仅表示有效、无效，则使用`1`表示有效，`-1`表示无效\n7. 预设字段在程序中都会进行相应的自动处理，根据情况设置\n    \n    \n    state: 表示逻辑删除的状态，设置该字段表示该表的所有记录均为逻辑删除，即更改state的值\n    created_at：表示创建时间，类型为datetime\n    created_by：表示创建人ID，类型为int\n    updated_at：表示修改时间\n    updated_by：表示修改人ID', '2', '1', '2016-10-26 11:49:15', '1');
INSERT INTO `manual_version` VALUES ('35', '104', '', '1', '1', '2016-11-08 19:49:34', '1');
INSERT INTO `manual_version` VALUES ('36', '104', 'wefwef', '2', '1', '2016-11-08 19:49:40', '1');
INSERT INTO `manual_version` VALUES ('37', '104', '', '4', '1', '2016-11-08 19:49:43', '1');
INSERT INTO `manual_version` VALUES ('38', '104', '', '1', '1', '2016-12-02 10:52:43', '1');
INSERT INTO `manual_version` VALUES ('39', '104', '> 开发环境与正式环境在某些细节上会有一定的差异，导致上线时发生一些测试时没发生过的事\n以下就是这些差异的集锦\n\n    1.开发：图片验证码始终为123；正式：随机4个英文字母\n    2.开发：短信验证码始终为1234；正式：随机4个数字\n    3.开发：始终会加载FancyBoxAsset和JqueryFormAsset（因为调试工具会自动加载这两个资源包）；\n    4.开发：不校验AJAX的表单重复提交；\n    5.开发：错误时会显示程序调用的堆栈信息；正式：调用自定义的错误界面（默认是site/error）', '2', '1', '2016-12-02 11:02:20', '1');
INSERT INTO `manual_version` VALUES ('40', '103', '> 统一使用 Navicat 工具操作mysql\n\n1. 数据库名和项目名一致，字符集统一使用 `utf8 -- UTF-8 Unicode`， 排序规则使用默认值（即不用手动选择）\n2. 表名全小写，用下划线连接单词，表名使用单词的单数形式\n3. 每个表必须设有主键，除了拓展表外，主键一律设置为`id`，并且设置自增、非null\n4. 如`user`表的拓展表`user_extend`，主键为`user_id`\n5. 非必填字段必须设置默认值（除了诸如text类型不能设置外），必填字段不必设置默认值\n6. 表示状态的字段一律使用`tinyint`类型，使用正数表示正面状态， 负数表示负面状态\n    如果仅表示有效、无效，则使用`1`表示有效，`-1`表示无效\n7. 预设字段在程序中都会进行相应的自动处理，根据情况设置\n    \n    \n    state: 表示逻辑删除的状态，设置该字段表示该表的所有记录均为逻辑删除，即更改state的值\n    created_at：表示创建时间，类型为datetime\n    created_by：表示创建人ID，类型为int\n    updated_at：表示修改时间，类型为datetime\n    updated_by：表示修改人ID，类型为int', '2', '1', '2016-12-02 11:02:51', '1');
INSERT INTO `manual_version` VALUES ('41', '101', '1. 变量驼峰式命名\n2. 每条语句最后必须加上分号\n3. 多条声明语句采用如下格式\n    \n    \n    var a = 1,\n        b = 2,\n        c = 3;\n4.存放jquery对象的变量必须以 $ 符作为前缀命名，如：`$a = $(\".link\");`\n5.事件绑定方法必须保存当前触发元素到变量中（function关键字右边必须含有一个空格），如：\n\n\n\n    $(\".container\").on(\'click\', \'.btn\', function () {\n        var $this = $(this);\n        // other code...\n    });\n6.标签中存放的变量必须放到data属性中，jquery中使用data方法来获取，如：\n\n\n    Html:\n    <input type=\"text\" data-key=\"1\">\n    Js:\n    var key = $(\"#userInput\").data(\'key\');', '2', '1', '2017-04-12 18:49:25', '1');
INSERT INTO `manual_version` VALUES ('42', '105', '', '1', '1', '2017-04-12 19:23:21', '15');
INSERT INTO `manual_version` VALUES ('43', '105', 'svn co svn://114.55.63.141/app/app7', '2', '1', '2017-04-12 19:30:33', '15');
INSERT INTO `manual_version` VALUES ('44', '105', '\n\n\n\n\nsvn co svn://114.55.63.141/app/app7', '2', '1', '2017-04-12 19:30:44', '15');
INSERT INTO `manual_version` VALUES ('45', '105', '1通过xshll 链接linu服务器\n\n\n\n\n\nsvn co svn://114.55.63.141/app/app7', '2', '1', '2017-04-12 19:31:32', '15');
INSERT INTO `manual_version` VALUES ('46', '105', '1通过xshll 链接linu服务器\n主要 服务器ip 用户名(root) 密码 \n\nsvn co svn://114.55.63.141/app/app7', '2', '1', '2017-04-12 19:32:51', '15');
INSERT INTO `manual_version` VALUES ('47', '105', '1通过xshll 链接linu服务器\n主要 服务器ip 用户名(root) 密码 \n2安装lnmp\n    php5.5+mysql+Nginx\nsvn co svn://114.55.63.141/app/app7', '2', '1', '2017-04-12 19:34:16', '15');
INSERT INTO `manual_version` VALUES ('48', '105', '1通过xshll 链接linu服务器\n主要 服务器ip 用户名(root) 密码 \n2安装lnmp\n    php5.5+mysql+Nginx\n     wget -c http://lamp.phpstudy.net/phpstudy.bin\nchmod +x phpstudy.bin    #权限设置\n./phpstudy.bin 　　　　#运行安装 \nsvn co svn://114.55.63.141/app/app7', '2', '1', '2017-04-12 19:35:16', '15');
INSERT INTO `manual_version` VALUES ('49', '105', '1通过xshll 链接linu服务器\n主要 服务器ip 用户名(root) 密码 \n2安装lnmp\n    php5.5+mysql+Nginx\n     wget -c http://lamp.phpstudy.net/phpstudy.bin\n    chmod +x phpstudy.bin    #权限设置\n    ./phpstudy.bin 　　　　#运行安装 \nsvn co svn://114.55.63.141/app/app7', '2', '1', '2017-04-12 19:35:21', '15');
INSERT INTO `manual_version` VALUES ('50', '105', '1通过xshll 链接linu服务器\n主要 服务器ip 用户名(root) 密码 \n2安装lnmp\n    php5.5+mysql+Nginx\n     wget -c http://lamp.phpstudy.net/phpstudy.bin\n    chmod +x phpstudy.bin    #权限设置\n    ./phpstudy.bin 　　　　#运行安装 \n3安装SVN\nsvn co svn://114.55.63.141/app/app7', '2', '1', '2017-04-12 19:35:50', '15');
INSERT INTO `manual_version` VALUES ('51', '105', '1通过xshll 链接linu服务器\n主要 服务器ip 用户名(root) 密码 \n2安装lnmp\n    php5.5+mysql+Nginx\n     wget -c http://lamp.phpstudy.net/phpstudy.bin\n    chmod +x phpstudy.bin    #权限设置\n    ./phpstudy.bin 　　　　#运行安装 \n3安装SVN\n    \nsvn co svn://114.55.63.141/app/app7', '2', '1', '2017-04-12 19:35:53', '15');
INSERT INTO `manual_version` VALUES ('52', '105', '1通过xshll 链接linu服务器\n    主要 服务器ip 用户名(root) 密码 \n2安装lnmp\n    php5.5+mysql+Nginx\n     wget -c http://lamp.phpstudy.net/phpstudy.bin\n    chmod +x phpstudy.bin    #权限设置\n    ./phpstudy.bin 　　　　#运行安装 \n3安装SVN\n    \nsvn co svn://114.55.63.141/app/app7', '2', '1', '2017-04-12 19:35:56', '15');
INSERT INTO `manual_version` VALUES ('53', '105', '1 通过xshll 链接linu服务器\n    主要 服务器ip 用户名(root) 密码 \n2 安装lnmp\n    php5.5+mysql+Nginx\n     wget -c http://lamp.phpstudy.net/phpstudy.bin\n    chmod +x phpstudy.bin    #权限设置\n    ./phpstudy.bin 　　　　#运行安装 \n3安装SVN\n    \nsvn co svn://114.55.63.141/app/app7', '2', '1', '2017-04-12 19:35:59', '15');
INSERT INTO `manual_version` VALUES ('54', '105', '1 通过xshll 链接linu服务器\n    主要 服务器ip 用户名(root) 密码 \n2 安装lnmp\n    php5.5+mysql+Nginx\n     wget -c http://lamp.phpstudy.net/phpstudy.bin\n    chmod +x phpstudy.bin    #权限设置\n    ./phpstudy.bin 　　　　#运行安装 \n3 安装SVN\n    \nsvn co svn://114.55.63.141/app/app7', '2', '1', '2017-04-12 19:36:00', '15');
INSERT INTO `manual_version` VALUES ('55', '105', '1 通过xshll 链接linu服务器\n    主要 服务器ip 用户名(root) 密码 \n2 安装lnmp\n    php5.5+mysql+Nginx\n     wget -c http://lamp.phpstudy.net/phpstudy.bin\n    chmod +x phpstudy.bin    #权限设置\n    ./phpstudy.bin 　　　　#运行安装 \n3 安装SVN\n    yum install subverion\nsvn co svn://114.55.63.141/app/app7', '2', '1', '2017-04-12 19:36:37', '15');
INSERT INTO `manual_version` VALUES ('56', '105', '1 通过xshll 链接linu服务器\n    主要 服务器ip 用户名(root) 密码 \n2 安装lnmp\n    php5.5+mysql+Nginx\n     wget -c http://lamp.phpstudy.net/phpstudy.bin\n    chmod +x phpstudy.bin    #权限设置\n    ./phpstudy.bin 　　　　#运行安装 \n3 安装SVN\n    yum install subverion\n4 通过SVN 下载项目到本服务器（注意本地-地址）\nsvn co svn://114.55.63.141/app/app7', '2', '1', '2017-04-12 19:37:45', '15');
INSERT INTO `manual_version` VALUES ('57', '105', '1  **通过xshll 链接linu服务器**\n    主要 服务器ip 用户名(root) 密码 \n2 **安装lnmp**\n    php5.5+mysql+Nginx\n     wget -c http://lamp.phpstudy.net/phpstudy.bin\n    chmod +x phpstudy.bin    #权限设置\n    ./phpstudy.bin 　　　　#运行安装 \n3 **安装SVN**\n    yum install subverion\n4 通过SVN 下载项目到本服务器（注意本地-地址）\nsvn co svn://114.55.63.141/app/app7', '2', '1', '2017-04-12 19:39:29', '15');
INSERT INTO `manual_version` VALUES ('58', '105', '1  **通过xshll 链接linu服务器**\n    主要 服务器ip 用户名(root) 密码 \n2 **安装lnmp**(运行命令)\n    php5.5+mysql+Nginx\n     wget -c http://lamp.phpstudy.net/phpstudy.bin\n    chmod +x phpstudy.bin    #权限设置\n    ./phpstudy.bin 　　　　#运行安装 \n3 **安装SVN**\n    yum install subverion\n4 通过SVN 下载项目到本服务器（注意本地-地址）\nsvn co svn://114.55.63.141/app/app7', '2', '1', '2017-04-12 19:39:48', '15');
INSERT INTO `manual_version` VALUES ('59', '105', '1  **通过xshll 链接linu服务器**\n    主要 服务器ip 用户名(root) 密码 \n2 **安装lnmp**(运行命令)\n    php5.5+mysql+Nginx\n     wget -c http://lamp.phpstudy.net/phpstudy.bin\n    chmod +x phpstudy.bin    #权限设置\n    ./phpstudy.bin 　　　　#运行安装 \n3 **安装SVN**\n    yum install subverion\n4 通过SVN 下载项目到本服务器（注意本地-地址）\nsvn co svn://114.55.63.141/app/app7（实例将app7下载到本地）', '2', '1', '2017-04-12 19:51:01', '15');
INSERT INTO `manual_version` VALUES ('60', '105', '1  **通过xshll 链接linu服务器**\n    主要 服务器ip 用户名(root) 密码 \n2 **安装lnmp**(运行命令)\n    php5.5+mysql+Nginx\n     wget -c http://lamp.phpstudy.net/phpstudy.bin\n    chmod +x phpstudy.bin    #权限设置\n    ./phpstudy.bin 　　　　#运行安装 \n3 **安装SVN**\n    yum -y install subversion\n4 通过SVN 下载项目到本服务器（注意本地-地址）\nsvn co svn://114.55.63.141/app/app7（实例将app7下载到本地）\n', '2', '1', '2017-04-12 20:35:39', '15');
INSERT INTO `manual_version` VALUES ('61', '105', '1  **通过xshll 链接linu服务器**\n    主要 服务器ip 用户名(root) 密码 \n2 **安装lnmp**(运行命令)\n    php5.5+mysql+Nginx\n     wget -c http://lamp.phpstudy.net/phpstudy.bin\n    chmod +x phpstudy.bin    #权限设置\n    ./phpstudy.bin 　　　　#运行安装 \n    x\n    phpstudy start  \n3 **安装SVN**\n    yum -y install subversion\n4 通过SVN 下载项目到本服务器（注意本地-地址）\nsvn co svn://114.55.63.141/app/app7（实例将app7下载到本地）\n', '2', '1', '2017-04-12 20:43:54', '15');
INSERT INTO `manual_version` VALUES ('62', '105', '1  **通过xshll 链接linu服务器**\n    主要 服务器ip 用户名(root) 密码 \n2 **安装lnmp**(运行命令)\n    php5.5+mysql+Nginx\n     wget -c http://lamp.phpstudy.net/phpstudy.bin\n    chmod +x phpstudy.bin    #权限设置\n    ./phpstudy.bin 　　　　#运行安装 \n   \n    phpstudy start  \n3 **安装SVN**\n    yum -y install subversion\n4 通过SVN 下载项目到本服务器（注意本地-地址）\nsvn co svn://114.55.63.141/app/app7（实例将app7下载到本地）\n', '2', '1', '2017-04-12 20:43:56', '15');
INSERT INTO `manual_version` VALUES ('63', '105', '1  **通过xshll 链接linu服务器**\n    主要 服务器ip 用户名(root) 密码 \n2 **安装lnmp**(运行命令)\n    php5.5+mysql+Nginx\n     wget -c http://lamp.phpstudy.net/phpstudy.bin\n    chmod +x phpstudy.bin    #权限设置\n    ./phpstudy.bin 　　　　#运行安装 \n   \n    phpstudy start  \n3 **安装SVN**\n    yum -y install subversion\n4 通过SVN 下载项目到本服务器（注意本地-地址）\nsvn co svn://114.55.63.141/app/app7（实例将app7下载到本地）\n\ndate.timezone = Asia/Shanghai 时区', '2', '1', '2017-04-12 20:52:04', '15');
INSERT INTO `manual_version` VALUES ('64', '105', '1  **通过xshll 链接linu服务器**\n    主要 服务器ip 用户名(root) 密码 \n2 **安装lnmp**(运行命令)\n    php5.5+mysql+Nginx\n     wget -c http://lamp.phpstudy.net/phpstudy.bin\n    chmod +x phpstudy.bin    #权限设置\n    ./phpstudy.bin 　　　　#运行安装 \n   \n    phpstudy start  \n3 **安装SVN**\n    yum -y install subversion\n4 通过SVN 下载项目到本服务器（注意本地-地址）\nsvn co svn://114.55.63.141/app/app7（实例将app7下载到本地）\n5\ndate.timezone = Asia/Shanghai 时区', '2', '1', '2017-04-12 20:52:31', '15');
INSERT INTO `manual_version` VALUES ('65', '105', '1  **通过xshll 链接linu服务器**\n    主要 服务器ip 用户名(root) 密码 \n2 **安装lnmp**(运行命令)\n    php5.5+mysql+Nginx\n     wget -c http://lamp.phpstudy.net/phpstudy.bin\n    chmod +x phpstudy.bin    #权限设置\n    ./phpstudy.bin 　　　　#运行安装 \n   \n    phpstudy start  \n3 **安装SVN**\n    yum -y install subversion\n4 通过SVN 下载项目到本服务器（注意本地-地址）\n    svn co svn://114.55.63.141/app/app7（实例将app7下载到本地）\n5 \ndate.timezone = Asia/Shanghai 时区', '2', '1', '2017-04-12 20:52:35', '15');
INSERT INTO `manual_version` VALUES ('66', '105', '1  **通过xshll 链接linu服务器**\n    主要 服务器ip 用户名(root) 密码 \n2 **安装lnmp**(运行命令)\n    php5.5+mysql+Nginx\n     wget -c http://lamp.phpstudy.net/phpstudy.bin\n    chmod +x phpstudy.bin    #权限设置\n    ./phpstudy.bin 　　　　#运行安装 \n   \n    phpstudy start  \n3 **安装SVN**\n    yum -y install subversion\n4 通过SVN 下载项目到本服务器（注意本地-地址）\n    svn co svn://114.55.63.141/app/app7（实例将app7下载到本地）\n5 chmod a+x yii（项目根目录下  执行结果 可执行 yii init/app）\ndate.timezone = Asia/Shanghai 时区', '2', '1', '2017-04-12 20:54:41', '15');
INSERT INTO `manual_version` VALUES ('67', '105', '1  **通过xshll 链接linu服务器**\n    主要 服务器ip 用户名(root) 密码 \n2 **安装lnmp**(运行命令)\n    php5.5+mysql+Nginx\n     wget -c http://lamp.phpstudy.net/phpstudy.bin\n    chmod +x phpstudy.bin    #权限设置\n    ./phpstudy.bin 　　　　#运行安装 \n   \n    phpstudy start  \n3 **安装SVN**\n    yum -y install subversion\n4 通过SVN 下载项目到本服务器（注意本地-地址）\n    svn co svn://114.55.63.141/app/app7（实例将app7下载到本地）5\n5 chmod a+x yii（项目根目录下  执行结果 可执行 yii init/app）\ndate.timezone = Asia/Shanghai 时区', '2', '1', '2017-04-12 20:54:49', '15');
INSERT INTO `manual_version` VALUES ('68', '105', '1  **通过xshll 链接linu服务器**\n    主要 服务器ip 用户名(root) 密码 \n2 **安装lnmp**(运行命令)\n    php5.5+mysql+Nginx\n     wget -c http://lamp.phpstudy.net/phpstudy.bin\n    chmod +x phpstudy.bin    #权限设置\n    ./phpstudy.bin 　　　　#运行安装 \n   \n    phpstudy start  \n3 **安装SVN**\n    yum -y install subversion\n4 通过SVN 下载项目到本服务器（注意本地-地址）\n    svn co svn://114.55.63.141/app/app7（实例将app7下载到本地）\n5 \n5 chmod a+x yii（项目根目录下  执行结果 可执行 yii init/app）\ndate.timezone = Asia/Shanghai 时区', '2', '1', '2017-04-12 20:54:54', '15');
INSERT INTO `manual_version` VALUES ('69', '105', '1  **通过xshll 链接linu服务器**\n    主要 服务器ip 用户名(root) 密码 \n2 **安装lnmp**(运行命令)\n    php5.5+mysql+Nginx\n     wget -c http://lamp.phpstudy.net/phpstudy.bin\n    chmod +x phpstudy.bin    #权限设置\n    ./phpstudy.bin 　　　　#运行安装 \n   \n    phpstudy start  \n3 **安装SVN**\n    yum -y install subversion\n4 通过SVN 下载项目到本服务器（注意本地-地址）\n    svn co svn://114.55.63.141/app/app7（实例将app7下载到本地）\n5 \n6 chmod a+x yii（项目根目录下  执行结果 可执行 yii init/app）\ndate.timezone = Asia/Shanghai 时区', '2', '1', '2017-04-12 20:54:57', '15');
INSERT INTO `manual_version` VALUES ('70', '105', '1  **通过xshll 链接linu服务器**\n    主要 服务器ip 用户名(root) 密码 \n2 **安装lnmp**(运行命令)\n    php5.5+mysql+Nginx\n     wget -c http://lamp.phpstudy.net/phpstudy.bin\n    chmod +x phpstudy.bin    #权限设置\n    ./phpstudy.bin 　　　　#运行安装 \n   \n    phpstudy start  \n3 **安装SVN**\n    yum -y install subversion\n4 通过SVN 下载项目到本服务器（注意本地-地址）\n    svn co svn://114.55.63.141/app/app7（实例将app7下载到本地）\n5 ln -s /phpstudy/server/php/bin/php /usr/sbin/php (php软连 相当于win下的环境变量)\n6 chmod a+x yii（项目根目录下  执行结果 可执行 yii init/app）\ndate.timezone = Asia/Shanghai 时区', '2', '1', '2017-04-12 20:55:57', '15');
INSERT INTO `manual_version` VALUES ('71', '105', '1  **通过xshll 链接linu服务器**\n    主要 服务器ip 用户名(root) 密码 \n2 **安装lnmp**(运行命令)\n    php5.5+mysql+Nginx\n     wget -c http://lamp.phpstudy.net/phpstudy.bin\n    chmod +x phpstudy.bin    #权限设置\n    ./phpstudy.bin 　　　　#运行安装 \n   \n    phpstudy start  \n3 **安装SVN**\n    yum -y install subversion\n4 通过SVN 下载项目到本服务器（注意本地-地址）\n    svn co svn://114.55.63.141/app/app7（实例将app7下载到本地）\n5 ln -s /phpstudy/server/php/bin/php /usr/sbin/php (php  软连 相当于win下的环境变量)\n6 chmod a+x yii（项目根目录下  执行结果 可执行 yii init/app）\ndate.timezone = Asia/Shanghai 时区', '2', '1', '2017-04-12 20:56:13', '15');
INSERT INTO `manual_version` VALUES ('72', '105', '1  **通过xshll 链接linu服务器**\n    主要 服务器ip 用户名(root) 密码 \n2 **安装lnmp**(运行命令)\n    php5.5+mysql+Nginx\n     wget -c http://lamp.phpstudy.net/phpstudy.bin\n    chmod +x phpstudy.bin    #权限设置\n    ./phpstudy.bin 　　　　#运行安装 \n   \n    phpstudy start  \n3 **安装SVN**\n    yum -y install subversion\n4 通过SVN 下载项目到本服务器（注意本地-地址）\n    svn co svn://114.55.63.141/app/app7（实例将app7下载到本地）\n5 ln -s /phpstudy/server/php/bin/php /usr/sbin/php (php  软连 相当于win下的环境变量)\n6 chmod a+x yii（项目根目录下  执行结果 可执行 yii init/app）\n7  vi /phpstudy/server/php/etc/php.ini  修改时区 date.timezone = Asia/Shanghai 时区', '2', '1', '2017-04-12 20:56:58', '15');
INSERT INTO `manual_version` VALUES ('73', '105', '1  **通过xshll 链接linu服务器**\n    主要 服务器ip 用户名(root) 密码 \n2 **安装lnmp**(运行命令)\n    php5.5+mysql+Nginx\n     wget -c http://lamp.phpstudy.net/phpstudy.bin\n    chmod +x phpstudy.bin    #权限设置\n    ./phpstudy.bin 　　　　#运行安装 \n   \n    phpstudy start  \n3 **安装SVN**\n    yum -y install subversion\n4 通过SVN 下载项目到本服务器（注意本地-地址）\n    svn co svn://114.55.63.141/app/app7（实例将app7下载到本地）\n5 ln -s /phpstudy/server/php/bin/php /usr/sbin/php (php  软连 相当于win下的环境变量)\n6 chmod a+x yii（项目根目录下  执行结果 可执行 yii init/app）\n7  vi /phpstudy/server/php/etc/php.ini  修改时区 date.timezone = Asia/Shanghai 时区\n\nnohup ./yii init/hq &', '2', '1', '2017-04-12 21:08:24', '15');
INSERT INTO `manual_version` VALUES ('74', '105', '1  **通过xshll 链接linu服务器**\n    主要 服务器ip 用户名(root) 密码 \n2 **安装lnmp**(运行命令)\n    php5.5+mysql+Nginx\n     wget -c http://lamp.phpstudy.net/phpstudy.bin\n    chmod +x phpstudy.bin    #权限设置\n    ./phpstudy.bin 　　　　#运行安装 \n   \n    phpstudy start  \n3 **安装SVN**\n    yum -y install subversion\n4 通过SVN 下载项目到本服务器（注意本地-地址）\n    svn co svn://114.55.63.141/app/app7（实例将app7下载到本地）\n5 ln -s /phpstudy/server/php/bin/php /usr/sbin/php (php  软连 相当于win下的环境变量)\n6 chmod a+x yii（项目根目录下  执行结果 可执行 yii init/app）\n7  vi /phpstudy/server/php/etc/php.ini  修改时区 date.timezone = Asia/Shanghai 时区 \n8（vi编辑 q! 不保存并退出 wq 保存并退出）\n\nnohup ./yii init/hq & （采集数据）', '2', '1', '2017-04-12 21:10:33', '15');
INSERT INTO `manual_version` VALUES ('75', '105', '1  **通过xshll 链接linu服务器**\n    主要 服务器ip 用户名(root) 密码 \n2 **安装lnmp**(运行命令)\n    php5.5+mysql+Nginx\n     wget -c http://lamp.phpstudy.net/phpstudy.bin\n    chmod +x phpstudy.bin    #权限设置\n    ./phpstudy.bin 　　　　#运行安装 \n   \n    phpstudy start  \n3 **安装SVN**\n    yum -y install subversion\n4 通过SVN 下载项目到本服务器（注意本地-地址）\n    svn co svn://114.55.63.141/app/app7（实例将app7下载到本地）\n5 ln -s /phpstudy/server/php/bin/php /usr/sbin/php (php  软连 相当于win下的环境变量)\n6 chmod a+x yii（项目根目录下  执行结果 可执行 yii init/app）\n7  vi /phpstudy/server/php/etc/php.ini  修改时区 date.timezone = Asia/Shanghai 时区 \n8（vi编辑 q! 不保存并退出 wq 保存并退出）\n\n9 nohup ./yii init/hq & （采集数据）', '2', '1', '2017-04-12 21:10:44', '15');
INSERT INTO `manual_version` VALUES ('76', '105', '1  **通过xshll 链接linu服务器**\n    主要 服务器ip 用户名(root) 密码 \n2 **安装lnmp**(运行命令)\n    php5.5+mysql+Nginx\n     wget -c http://lamp.phpstudy.net/phpstudy.bin\n    chmod +x phpstudy.bin    #权限设置\n    ./phpstudy.bin 　　　　#运行安装 \n   \n    phpstudy start  \n3 **安装SVN**\n    yum -y install subversion\n4 通过SVN 下载项目到本服务器（注意本地-地址）\n    svn co svn://114.55.63.141/app/app7（实例将app7下载到本地）\n    修改配置文件 main-local.php (注意 localhost 改成 127.0.0.1) ENV.php （fals prod）\n5 ln -s /phpstudy/server/php/bin/php /usr/sbin/php (php  软连 相当于win下的环境变量)\n6 chmod a+x yii（项目根目录下  执行结果 可执行 yii init/app）\n7  vi /phpstudy/server/php/etc/php.ini  修改时区 date.timezone = Asia/Shanghai 时区 \n8（vi编辑 q! 不保存并退出 wq 保存并退出）\n\n9 nohup ./yii init/hq & （采集数据）', '2', '1', '2017-04-12 21:13:03', '15');
INSERT INTO `manual_version` VALUES ('77', '105', '1  **通过xshll 链接linu服务器**\n    主要 服务器ip 用户名(root) 密码 \n2 **安装lnmp**(运行命令)\n    php5.5+mysql+Nginx\n     wget -c http://lamp.phpstudy.net/phpstudy.bin\n    chmod +x phpstudy.bin    #权限设置\n    ./phpstudy.bin 　　　　#运行安装 \n   \n    phpstudy start （运行） \n3 **安装SVN**\n    yum -y install subversion\n4 通过SVN 下载项目到本服务器（注意本地-地址）\n    svn co svn://114.55.63.141/app/app7（实例将app7下载到本地）\n    修改配置文件 main-local.php (注意 localhost 改成 127.0.0.1) ENV.php （fals prod）\n5 ln -s /phpstudy/server/php/bin/php /usr/sbin/php (php  软连 相当于win下的环境变量)\n6 chmod a+x yii（项目根目录下  执行结果 可执行 yii init/app）\n7  vi /phpstudy/server/php/etc/php.ini  修改时区 date.timezone = Asia/Shanghai 时区 \n8（vi编辑 q! 不保存并退出 wq 保存并退出）\n9 修改后需重启 （phpstudy restart）;\n9 nohup ./yii init/hq & （采集数据）\n', '2', '1', '2017-04-12 21:14:27', '15');
INSERT INTO `manual_version` VALUES ('78', '105', '###通过 shell 链接 linux 服务器\n###安装 PHPSTUDY\n    wget -c http://lamp.phpstudy.net/phpstudy.bin #下载安装包\n    chmod +x phpstudy.bin    #权限设置\n    ./phpstudy.bin 　　　　#运行安装 \n   \n    \n###安装SVN\n     yum update\n     yum -y install subversion\n ###通过 SVN 下载项目到服务器\n    cd /phpstudy/www #进入www目录\n    svn co svn://114.55.63.141/app/app7（下载app7项目）\n ### NGINX 配置\n    上传vhost.conf到 /phpstudy/server/nginx/conf/vhost/vhost.conf ，并修改相应配置\n    在Navicat中通过SSH方式创建远程连接，并修改连接的配色为红色\n    创建数据库导入项目根目录下的init.sql文件初始化数据库\n ### 配置参数\n    ln -s /phpstudy/server/php/bin/php /usr/sbin/php\n    vi /phpstudy/server/php/etc/php.ini  修改时区 date.timezone = Asia/Shanghai 时区\n    chmod a+x yii\n    ./yii init/app\n    common/config/main-local.php (注意 localhost 改成 127.0.0.1) \n    common/config/ENV.php （false prod）\n### 相关命令\n    phpstudy start|restart|stop （运行|重启|停止） \n    nohup ./yii init/hq & （开启采集数据）\n', '2', '1', '2017-04-12 21:30:06', '15');
INSERT INTO `manual_version` VALUES ('79', '105', '###通过 shell 链接 linux 服务器\n###安装 PHPSTUDY\n    wget -c http://lamp.phpstudy.net/phpstudy.bin #下载安装包\n    chmod +x phpstudy.bin    #权限设置\n    ./phpstudy.bin 　　　　#运行安装 \n   \n    \n###安装SVN\n     yum update\n     yum -y install subversion\n ###通过 SVN 下载项目到服务器\n    cd /phpstudy/www #进入www目录\n    svn co svn://114.55.63.141/app/app7（下载app7项目）\n ### NGINX 配置\n    上传vhost.conf到 /phpstudy/server/nginx/conf/vhost/vhost.conf ，并修改相应配置\n    在Navicat中通过SSH方式创建远程连接，并修改连接的配色为红色\n    创建数据库导入项目根目录下的init.sql文件初始化数据库\n###解析域名\n    主机名        记录类型        ip或别名\n     www             A           服务器ip\n      @              A           服务器ip\n      *              A           服务器ip\n    \n ### 配置参数\n    ln -s /phpstudy/server/php/bin/php /usr/sbin/php\n    vi /phpstudy/server/php/etc/php.ini  修改时区 date.timezone = Asia/Shanghai 时区\n    chmod a+x yii\n    ./yii init/app\n    common/config/main-local.php (注意 localhost 改成 127.0.0.1) \n    common/config/ENV.php （false prod）\n### 相关命令\n    phpstudy start|restart|stop （运行|重启|停止） \n    nohup ./yii init/hq & （开启采集数据）\n', '2', '1', '2017-04-12 21:35:15', '15');
INSERT INTO `manual_version` VALUES ('80', '105', '###通过 shell 链接 linux 服务器\n###安装 PHPSTUDY\n    wget -c http://lamp.phpstudy.net/phpstudy.bin #下载安装包\n    chmod +x phpstudy.bin    #权限设置\n    ./phpstudy.bin 　　　　#运行安装 \n   \n    \n###安装SVN\n     yum update\n     yum -y install subversion\n ###通过 SVN 下载项目到服务器\n    cd /phpstudy/www #进入www目录\n    svn co svn://114.55.63.141/app/app7（下载app7项目）\n ### NGINX 配置\n    上传vhost.conf到 /phpstudy/server/nginx/conf/vhost/vhost.conf ，并修改相应配置\n    在Navicat中通过SSH方式创建远程连接，并修改连接的配色为红色\n    创建数据库导入项目根目录下的init.sql文件初始化数据库\n###解析域名\n    主机名        记录类型        ip或别名\n     www             A           服务器ip\n      @              A           服务器ips\n      *              A           服务器ip\n    \n ### 配置参数\n    ln -s /phpstudy/server/php/bin/php /usr/sbin/php\n    vi /phpstudy/server/php/etc/php.ini  修改时区 date.timezone = Asia/Shanghai 时区\n    chmod a+x yii\n    ./yii init/app\n    common/config/main-local.php (注意 localhost 改成 127.0.0.1) \n    common/config/ENV.php （false prod）\n### 相关命令\n    phpstudy start|restart|stop （运行|重启|停止） \n    nohup ./yii init/hq & （开启采集数据）\n', '2', '1', '2017-04-12 21:36:46', '15');
INSERT INTO `manual_version` VALUES ('81', '105', '###通过 shell 链接 linux 服务器\n###安装 PHPSTUDY\n    wget -c http://lamp.phpstudy.net/phpstudy.bin #下载安装包\n    chmod +x phpstudy.bin    #权限设置\n    ./phpstudy.bin 　　　　#运行安装 \n   \n    \n###安装SVN\n     yum update\n     yum -y install subversion\n ###通过 SVN 下载项目到服务器\n    cd /phpstudy/www #进入www目录\n    svn co svn://114.55.63.141/app/app7（下载app7项目）\n ### NGINX 配置\n    上传vhost.conf到 /phpstudy/server/nginx/conf/vhost/vhost.conf ，并修改相应配置\n    在Navicat中通过SSH方式创建远程连接，并修改连接的配色为红色\n    创建数据库导入项目根目录下的init.sql文件初始化数据库\n###解析域名\n    主机名        记录类型        ip或别名\n     www             A           服务器ip\n      @              A           服务器ip\n      *              A           服务器ip\n    \n ### 配置参数\n    ln -s /phpstudy/server/php/bin/php /usr/sbin/php\n    vi /phpstudy/server/php/etc/php.ini  修改时区 date.timezone = Asia/Shanghai 时区\n    chmod a+x yii\n    ./yii init/app\n    common/config/main-local.php (注意 localhost 改成 127.0.0.1) \n    common/config/ENV.php （false prod）\n### 相关命令\n    phpstudy start|restart|stop （运行|重启|停止） \n    nohup ./yii init/hq & （开启采集数据）\n', '2', '1', '2017-04-12 21:36:48', '15');
INSERT INTO `manual_version` VALUES ('82', '106', '', '1', '1', '2017-04-14 13:43:44', '1');
INSERT INTO `manual_version` VALUES ('83', '107', '', '1', '1', '2017-04-14 13:46:09', '1');
INSERT INTO `manual_version` VALUES ('84', '108', '', '1', '1', '2017-04-14 13:46:19', '1');
INSERT INTO `manual_version` VALUES ('85', '107', '', '4', '1', '2017-04-14 13:46:23', '1');
INSERT INTO `manual_version` VALUES ('86', '109', '', '1', '1', '2017-04-14 13:46:31', '1');
INSERT INTO `manual_version` VALUES ('87', '108', '##一、基本\n1.关注该公众号\n2.开发 -> 基本配置 -> 生成APPSecret\n3.开发 -> 开发者工具 -> web开发者工具 -> 添加开发者的微信号\n##二、网页授权回调\n\n1、设置 -> 公众号设置 -> 功能设置-> 网页授权域名，修改授权回调页面域名。请注意，这里填写的是域名（是一个字符串），而不是URL，因此请勿加 http:// 等协议头； \n\n2、授权回调域名配置规范为全域名，比如需要网页授权的域名为：www.qq.com，配置以后此域名下面的页面http://www.qq.com/music.html 、 http://www.qq.com/login.html 都可以进行OAuth2.0鉴权。但http://pay.qq.com 、 http://music.qq.com 、 http://qq.com无法进行OAuth2.0鉴权 ', '2', '1', '2017-04-14 13:48:55', '1');
INSERT INTO `manual_version` VALUES ('88', '108', '##一、基本\n1.关注该公众号\n2.开发 -> 基本配置 -> 生成APPSecret\n3.开发 -> 开发者工具 -> web开发者工具 -> 添加开发者的微信号\n\n##二、网页授权回调\n1、设置 -> 公众号设置 -> 功能设置-> 网页授权域名，修改授权回调页面域名。请注意，这里填写的是域名（是一个字符串），而不是URL，因此请勿加 http:// 等协议头； \n\n2、授权回调域名配置规范为全域名，比如需要网页授权的域名为：www.qq.com，配置以后此域名下面的页面http://www.qq.com/music.html 、 http://www.qq.com/login.html 都可以进行OAuth2.0鉴权。但http://pay.qq.com 、 http://music.qq.com 、 http://qq.com无法进行OAuth2.0鉴权 ', '2', '1', '2017-04-14 13:49:07', '1');
INSERT INTO `manual_version` VALUES ('89', '108', '##一、基本设置\n1.关注该公众号\n2.开发 -> 基本配置 -> 生成APPSecret\n3.开发 -> 开发者工具 -> web开发者工具 -> 添加开发者的微信号\n\n##二、网页授权回调设置\n1、设置 -> 公众号设置 -> 功能设置-> 网页授权域名，修改授权回调页面域名。请注意，这里填写的是域名（是一个字符串），而不是URL，因此请勿加 http:// 等协议头； \n\n2、授权回调域名配置规范为全域名，比如需要网页授权的域名为：www.qq.com，配置以后此域名下面的页面http://www.qq.com/music.html 、 http://www.qq.com/login.html 都可以进行OAuth2.0鉴权。但http://pay.qq.com 、 http://music.qq.com 、 http://qq.com无法进行OAuth2.0鉴权 ', '2', '1', '2017-04-14 13:49:36', '1');
INSERT INTO `manual_version` VALUES ('90', '110', '', '1', '1', '2017-06-01 23:11:58', '1');
INSERT INTO `manual_version` VALUES ('91', '110', '', '4', '1', '2017-06-01 23:12:01', '1');
INSERT INTO `manual_version` VALUES ('92', '111', '', '1', '1', '2017-06-01 23:12:08', '1');
INSERT INTO `manual_version` VALUES ('93', '111', '> 下载与解压\n    \n    直接从官方下载 \'.tar.gz\' 版本的源码包，上传到服务器\n    执行命令解压：\'tar -zxvf\'\n> 配置\n\n    ./configure \\\n    --prefix=/usr/local/php7 \\\n    --with-config-file-path=/usr/local/php7/etc \\\n    --with-config-file-scan-dir=/usr/local/php7/etc/php.d \\\n    --with-mcrypt=/usr/include \\\n    --enable-mysqlnd \\\n    --with-mysqli \\\n    --with-pdo-mysql \\\n    --enable-fpm  \\\n    --with-gd \\\n    --with-iconv \\\n    --with-zlib \\\n    --enable-xml \\\n    --enable-shmop \\\n    --enable-sysvsem \\\n    --enable-inline-optimization \\\n    --enable-mbregex \\\n    --enable-mbstring \\\n    --enable-ftp \\\n    --enable-gd-native-ttf \\\n    --with-openssl \\\n    --enable-pcntl \\\n    --enable-sockets \\\n    --with-xmlrpc \\\n    --enable-zip \\\n    --enable-soap \\\n    --without-pear \\\n    --with-gettext \\\n    --enable-session \\\n    --with-curl \\\n    --with-jpeg-dir \\\n    --with-freetype-dir \\\n    --enable-opcache \\\n    --with-pcre-regex \\\n    --with-png-dir\n    \n > 安装\n\n    make && make install\n    \n> 复制配置文件\n\n    cp 解压包里/php.ini.development /usr/local/php7/etc/php.ini\n    cp /usr/local/php7/etc/php-fpm.conf.default /usr/local/php7/etc/php-fpm.conf\n    cp /usr/local/php7/etc/php-fpm.d/www.conf.default /usr/local/php7/etc/php-fpm.d/www.conf\n\n\n    \n', '2', '1', '2017-06-01 23:20:40', '1');
INSERT INTO `manual_version` VALUES ('94', '111', '> 前戏\n\n    使用yum下载各种依赖库，具体参考baidu结果\n    此处假定已安装好phpstudy\n\n> 下载与解压\n    \n    直接从官方下载 \'.tar.gz\' 版本的源码包，上传到服务器\n    执行命令解压：\'tar -zxvf\'\n> 配置\n\n    ./configure \\\n    --prefix=/usr/local/php7 \\\n    --with-config-file-path=/usr/local/php7/etc \\\n    --with-config-file-scan-dir=/usr/local/php7/etc/php.d \\\n    --with-mcrypt=/usr/include \\\n    --enable-mysqlnd \\\n    --with-mysqli \\\n    --with-pdo-mysql \\\n    --enable-fpm  \\\n    --with-gd \\\n    --with-iconv \\\n    --with-zlib \\\n    --enable-xml \\\n    --enable-shmop \\\n    --enable-sysvsem \\\n    --enable-inline-optimization \\\n    --enable-mbregex \\\n    --enable-mbstring \\\n    --enable-ftp \\\n    --enable-gd-native-ttf \\\n    --with-openssl \\\n    --enable-pcntl \\\n    --enable-sockets \\\n    --with-xmlrpc \\\n    --enable-zip \\\n    --enable-soap \\\n    --without-pear \\\n    --with-gettext \\\n    --enable-session \\\n    --with-curl \\\n    --with-jpeg-dir \\\n    --with-freetype-dir \\\n    --enable-opcache \\\n    --with-pcre-regex \\\n    --with-png-dir\n > 安装\n\n    make && make install\n> 复制配置文件\n\n    cp 解压包里/php.ini.development /usr/local/php7/etc/php.ini\n    cp /usr/local/php7/etc/php-fpm.conf.default /usr/local/php7/etc/php-fpm.conf\n    cp /usr/local/php7/etc/php-fpm.d/www.conf.default /usr/local/php7/etc/php-fpm.d/www.conf\n> 创建快捷命令与执行\n    \n    ln -s /usr/local/php7/bin/php /usr/sbin/php\n    ln -s /usr/local/php7/sbin/php-fpm /usr/sbin/php-fpm\n    cp 源码包/sapi/fpm/init.d.php-fpm /etc/init.d/php-fpm\n    chmod 755 /etc/init.d/php-fpm\n    chkconfig php-fpm on\n    service php-fpm start \n\n', '2', '1', '2017-06-01 23:36:49', '1');
INSERT INTO `manual_version` VALUES ('95', '105', '###通过 shell 链接 linux 服务器\n###安装 PHPSTUDY\n    wget -c http://lamp.phpstudy.net/phpstudy.bin #下载安装包\n    chmod +x phpstudy.bin    #权限设置\n    ./phpstudy.bin 　　　　#运行安装 \n   \n    \n###安装SVN\n     yum update\n     yum -y install subversion\n ###通过 SVN 下载项目到服务器\n    cd /phpstudy/www #进入www目录\n    svn co svn://114.55.63.141/app/app7（下载app7项目）\n ### NGINX 配置\n    上传vhost.conf到 /phpstudy/server/nginx/conf/vhost/vhost.conf ，并修改相应配置\n    在Navicat中通过SSH方式创建远程连接，并修改连接的配色为红色\n    创建数据库导入项目根目录下的init.sql文件初始化数据库\n###解析域名\n    主机名        记录类型        ip或别名\n     www             A           服务器ip\n      @              A           服务器ip\n      *              A           服务器ip\n    \n ### 配置参数\n    ln -s /phpstudy/server/php/bin/php /usr/sbin/php\n    vi /phpstudy/server/php/etc/php.ini  \n    修改时区 date.timezone = Asia/Shanghai 时区，\n    修改expose_php = Off\n    chmod a+x yii\n    ./yii init/app\n    common/config/main-local.php (注意 localhost 改成 127.0.0.1) \n    common/config/ENV.php （false prod）\n### 相关命令\n    phpstudy start|restart|stop （运行|重启|停止） \n    nohup ./yii init/hq & （开启采集数据）\n', '2', '1', '2017-06-01 23:37:53', '1');
INSERT INTO `manual_version` VALUES ('96', '105', '###通过 shell 链接 linux 服务器\n###安装 PHPSTUDY\n    wget -c http://lamp.phpstudy.net/phpstudy.bin #下载安装包\n    chmod +x phpstudy.bin    #权限设置\n    ./phpstudy.bin 　　　　#运行安装 \n   \n    \n###安装SVN\n     yum update\n     yum -y install subversion\n ###通过 SVN 下载项目到服务器\n    cd /phpstudy/www #进入www目录\n    svn co svn://114.55.63.141/app/app7（下载app7项目）\n ### NGINX 配置\n    上传vhost.conf到 /phpstudy/server/nginx/conf/vhost/vhost.conf ，并修改相应配置\n    修改nginx.conf，在http段中加入 \'server_tokens off;\' 隐藏服务器版本号\n    在Navicat中通过SSH方式创建远程连接，并修改连接的配色为红色\n    创建数据库导入项目根目录下的init.sql文件初始化数据库\n###解析域名\n    主机名        记录类型        ip或别名\n     www             A           服务器ip\n      @              A           服务器ip\n      *              A           服务器ip\n    \n ### 配置参数\n    ln -s /phpstudy/server/php/bin/php /usr/sbin/php\n    vi /phpstudy/server/php/etc/php.ini  \n    修改date.timezone = Asia/Shanghai\n    修改expose_php = Off\n    chmod a+x yii\n    ./yii init/app\n    common/config/main-local.php (注意 localhost 改成 127.0.0.1) \n    common/config/ENV.php （false prod）\n### 相关命令\n    phpstudy start|restart|stop （运行|重启|停止） \n    nohup ./yii init/hq & （开启采集数据）\n', '2', '1', '2017-06-01 23:41:48', '1');
INSERT INTO `manual_version` VALUES ('97', '105', '###通过 shell 链接 linux 服务器\n###安装 PHPSTUDY\n    wget -c http://lamp.phpstudy.net/phpstudy.bin #下载安装包\n    chmod +x phpstudy.bin    #权限设置\n    ./phpstudy.bin 　　　　#运行安装 \n   \n    \n###安装SVN\n     yum update\n     yum -y install subversion\n ###通过 SVN 下载项目到服务器\n    cd /phpstudy/www #进入www目录\n    svn co svn://114.55.63.141/app/app7（下载app7项目）\n ### NGINX 配置\n    上传vhost.conf到 /phpstudy/server/nginx/conf/vhost/vhost.conf ，并修改相应配置\n    修改nginx.conf，在http段中加入 \'server_tokens off;\' 隐藏服务器版本号\n    在Navicat中通过SSH方式创建远程连接，并修改连接的配色为红色\n    创建数据库导入项目根目录下的init.sql文件初始化数据库\n###解析域名\n    主机名        记录类型        ip或别名\n     www             A           服务器ip\n      @              A           服务器ip\n      *              A           服务器ip\n    \n ### 配置参数\n    ln -s /phpstudy/server/php/bin/php /usr/sbin/php\n    vi /phpstudy/server/php/etc/php.ini\n    修改数据库默认密码update user set password=password(\'123\') where user=\'root\'\n    修改date.timezone = Asia/Shanghai\n    修改expose_php = Off\n    chmod a+x yii\n    ./yii init/app\n    common/config/main-local.php (注意 localhost 改成 127.0.0.1) \n    common/config/ENV.php （false prod）\n### 相关命令\n    phpstudy start|restart|stop （运行|重启|停止） \n    nohup ./yii init/hq & （开启采集数据）\n', '2', '1', '2017-07-08 11:32:50', '1');
INSERT INTO `manual_version` VALUES ('98', '105', '###通过 shell 链接 linux 服务器\n###安装 PHPSTUDY\n    wget -c http://lamp.phpstudy.net/phpstudy.bin #下载安装包\n    chmod +x phpstudy.bin    #权限设置\n    ./phpstudy.bin 　　　　#运行安装 \n   \n    \n###安装SVN\n     yum update\n     yum -y install subversion\n ###通过 SVN 下载项目到服务器\n    cd /phpstudy/www #进入www目录\n    svn co svn://114.55.63.141/app/app7（下载app7项目）\n ### NGINX 配置\n    上传vhost.conf到 /phpstudy/server/nginx/conf/vhost/vhost.conf ，并修改相应配置\n    修改nginx.conf，在http段中加入 \'server_tokens off;\' 隐藏服务器版本号\n    在Navicat中通过SSH方式创建远程连接，并修改连接的配色为红色\n    创建数据库导入项目根目录下的init.sql文件初始化数据库\n###解析域名\n    主机名        记录类型        ip或别名\n     www             A           服务器ip\n      @              A           服务器ip\n      *              A           服务器ip\n    \n ### 配置参数\n    ln -s /phpstudy/server/php/bin/php /usr/sbin/php\n    vi /phpstudy/server/php/etc/php.ini\n    修改数据库默认密码\n    update user set password=password(\'123\') where user=\'root\';\n    FLUSH PRIVILEGES;\n    修改date.timezone = Asia/Shanghai\n    修改expose_php = Off\n    chmod a+x yii\n    ./yii init/app\n    common/config/main-local.php (注意 localhost 改成 127.0.0.1) \n    common/config/ENV.php （false prod）\n### 相关命令\n    phpstudy start|restart|stop （运行|重启|停止） \n    nohup ./yii init/hq & （开启采集数据）\n', '2', '1', '2017-07-08 12:03:56', '1');
INSERT INTO `manual_version` VALUES ('99', '105', '###通过 shell 链接 linux 服务器\n###安装 PHPSTUDY\n    wget -c http://lamp.phpstudy.net/phpstudy.bin #下载安装包\n    chmod +x phpstudy.bin    #权限设置\n    ./phpstudy.bin 　　　　#运行安装 \n   \n    \n###安装SVN\n     yum update\n     yum -y install subversion\n ###通过 SVN 下载项目到服务器\n    cd /phpstudy/www #进入www目录\n    svn co svn://114.55.63.141/app/app7（下载app7项目）\n ### NGINX 配置\n    上传vhost.conf到 /phpstudy/server/nginx/conf/vhost/vhost.conf ，并修改相应配置\n    修改nginx.conf，在http段中加入 \'server_tokens off;\' 隐藏服务器版本号\n    在Navicat中通过SSH方式创建远程连接，并修改连接的配色为红色\n    创建数据库导入项目根目录下的init.sql文件初始化数据库\n###解析域名\n    主机名        记录类型        ip或别名\n     www             A           服务器ip\n      @              A           服务器ip\n      *              A           服务器ip\n    \n ### 配置参数\n    ln -s /phpstudy/server/php/bin/php /usr/sbin/php\n    vi /phpstudy/server/php/etc/php.ini\n    修改数据库默认密码\n    update mysql.user set password=password(\'123\') where user=\'root\';\n    FLUSH PRIVILEGES;\n    修改date.timezone = Asia/Shanghai\n    修改expose_php = Off\n    chmod a+x yii\n    ./yii init/app\n    common/config/main-local.php (注意 localhost 改成 127.0.0.1) \n    common/config/ENV.php （false prod）\n### 相关命令\n    phpstudy start|restart|stop （运行|重启|停止） \n    nohup ./yii init/hq & （开启采集数据）\n', '2', '1', '2017-07-08 12:07:15', '1');
INSERT INTO `manual_version` VALUES ('100', '105', '###通过 shell 链接 linux 服务器\n###安装 PHPSTUDY\n    wget -c http://lamp.phpstudy.net/phpstudy.bin #下载安装包\n    chmod +x phpstudy.bin    #权限设置\n    ./phpstudy.bin 　　　　#运行安装 \n   \n    \n###安装SVN\n     yum update\n     yum -y install subversion\n ###通过 SVN 下载项目到服务器\n    cd /phpstudy/www #进入www目录\n    svn co svn://114.55.63.141/app/app7（下载app7项目）\n ### NGINX 配置\n    上传vhost.conf到 /phpstudy/server/nginx/conf/vhost/vhost.conf ，并修改相应配置\n    修改nginx.conf，在http段中加入 \'server_tokens off;\' 隐藏服务器版本号\n    并注释 `server 中localhost的配置` 禁止直接使用IP方式访问服务器ss\n    在Navicat中通过SSH方式创建远程连接，并修改连接的配色为红色\n    创建数据库导入项目根目录下的init.sql文件初始化数据库\n###解析域名\n    主机名        记录类型        ip或别名\n     www             A           服务器ip\n      @              A           服务器ip\n      *              A           服务器ip\n    \n ### 配置参数\n    ln -s /phpstudy/server/php/bin/php /usr/sbin/php\n    vi /phpstudy/server/php/etc/php.ini\n    修改数据库默认密码\n    update mysql.user set password=password(\'123\') where user=\'root\';\n    FLUSH PRIVILEGES;\n    修改date.timezone = Asia/Shanghai\n    修改expose_php = Off\n    chmod a+x yii\n    ./yii init/app\n    common/config/main-local.php (注意 localhost 改成 127.0.0.1) \n    common/config/ENV.php （false prod）\n### 相关命令\n    phpstudy start|restart|stop （运行|重启|停止） \n    nohup ./yii init/hq & （开启采集数据）\n', '2', '1', '2017-08-06 21:29:14', '1');
INSERT INTO `manual_version` VALUES ('101', '111', '> 前戏\n\n    使用yum下载各种依赖库，具体参考baidu结果\n    此处假定已安装好phpstudy\n\n> 下载与解压\n    \n    直接从官方下载 \'.tar.gz\' 版本的源码包，上传到服务器\n    执行命令解压：\'tar -zxvf\'\n> 配置\n\n    ./configure \\\n    --prefix=/usr/local/php7 \\\n    --with-config-file-path=/usr/local/php7/etc \\\n    --with-config-file-scan-dir=/usr/local/php7/etc/php.d \\\n    --with-mcrypt=/usr/include \\\n    --enable-mysqlnd \\\n    --with-mysqli \\\n    --with-pdo-mysql \\\n    --enable-fpm  \\\n    --with-gd \\\n    --with-iconv \\\n    --with-zlib \\\n    --enable-xml \\\n    --enable-shmop \\\n    --enable-sysvsem \\\n    --enable-inline-optimization \\\n    --enable-mbregex \\\n    --enable-mbstring \\\n    --enable-ftp \\\n    --enable-gd-native-ttf \\\n    --with-openssl \\\n    --enable-pcntl \\\n    --enable-sockets \\\n    --with-xmlrpc \\\n    --enable-zip \\\n    --enable-soap \\\n    --without-pear \\\n    --with-gettext \\\n    --enable-session \\\n    --with-curl \\\n    --with-jpeg-dir \\\n    --with-freetype-dir \\\n    --enable-opcache \\\n    --with-pcre-regex \\\n    --with-png-dir\n > 安装\n\n    make && make install\n> 复制配置文件\n\n    cp 解压包里/php.ini.development /usr/local/php7/etc/php.ini\n    cp /usr/local/php7/etc/php-fpm.conf.default /usr/local/php7/etc/php-fpm.conf\n    cp /usr/local/php7/etc/php-fpm.d/www.conf.default /usr/local/php7/etc/php-fpm.d/www.conf\n    修改 www.conf 中运行用户与组为www\n> 创建快捷命令与执行\n    \n    ln -s /usr/local/php7/bin/php /usr/sbin/php\n    ln -s /usr/local/php7/sbin/php-fpm /usr/sbin/php-fpm\n    cp 源码包/sapi/fpm/init.d.php-fpm /etc/init.d/php-fpm\n    chmod 755 /etc/init.d/php-fpm\n    chkconfig php-fpm on\n    service php-fpm start \n\n', '2', '1', '2017-08-07 12:03:43', '1');
INSERT INTO `manual_version` VALUES ('102', '105', '###通过 shell 链接 linux 服务器\n###安装 PHPSTUDY\n    wget -c http://lamp.phpstudy.net/phpstudy.bin #下载安装包\n    chmod +x phpstudy.bin    #权限设置\n    ./phpstudy.bin 　　　　#运行安装 \n   \n    \n###安装SVN\n     yum update\n     yum -y install subversion\n ###通过 SVN 下载项目到服务器\n    cd /phpstudy/www #进入www目录\n    svn co svn://114.55.63.141/app/app7（下载app7项目）\n ### NGINX 配置\n    上传vhost.conf到 /phpstudy/server/nginx/conf/vhost/vhost.conf ，并修改相应配置\n    修改nginx.conf，在http段中加入 \'server_tokens off;\' 隐藏服务器版本号\n    并注释 `server 中localhost的配置` 禁止直接使用IP方式访问服务器ss\n    在Navicat中通过SSH方式创建远程连接，并修改连接的配色为红色\n    创建数据库导入项目根目录下的init.sql文件初始化数据库\n###解析域名\n    主机名        记录类型        ip或别名\n     www             A           服务器ip\n      @              A           服务器ip\n      *              A           服务器ip\n    \n ### 配置参数\n    ln -s /phpstudy/server/php/bin/php /usr/sbin/php\n    ln -s /phpstudy/server/nginx/sbin/nginx /usr/local/bin/nginx\n    vi /phpstudy/server/php/etc/php.ini\n    修改数据库默认密码\n    update mysql.user set password=password(\'123\') where user=\'root\';\n    FLUSH PRIVILEGES;\n    修改date.timezone = Asia/Shanghai\n    修改expose_php = Off\n    chmod a+x yii\n    ./yii init/app\n    common/config/main-local.php (注意 localhost 改成 127.0.0.1) \n    common/config/Env.php （false prod）\n### 相关命令\n    phpstudy start|restart|stop （运行|重启|停止） \n    nohup ./yii init/hq & （开启采集数据）\n', '2', '1', '2017-08-08 11:52:50', '1');
INSERT INTO `manual_version` VALUES ('103', '105', '###通过 shell 链接 linux 服务器\n###安装 PHPSTUDY\n    wget -c http://lamp.phpstudy.net/phpstudy.bin #下载安装包\n    chmod +x phpstudy.bin    #权限设置\n    ./phpstudy.bin 　　　　#运行安装 \n   \n    \n###安装SVN\n     yum update\n     yum -y install subversion\n ###通过 SVN 下载项目到服务器\n    cd /phpstudy/www #进入www目录\n    svn co svn://114.55.63.141/app/app7（下载app7项目）\n ### NGINX 配置\n    上传vhost.conf到 /phpstudy/server/nginx/conf/vhost/vhost.conf ，并修改相应配置\n    修改nginx.conf，在http段中加入 \'server_tokens off;\' 隐藏服务器版本号\n    并注释 `server 中localhost的配置` 禁止直接使用IP方式访问服务器ss\n    在Navicat中通过SSH方式创建远程连接，并修改连接的配色为红色\n    创建数据库导入项目根目录下的init.sql文件初始化数据库\n###解析域名\n    主机名        记录类型        ip或别名\n     www             A           服务器ip\n      @              A           服务器ip\n      *              A           服务器ip\n    \n ### 配置参数\n    ln -s /phpstudy/server/php/bin/php /usr/sbin/php\n    ln -s /phpstudy/server/nginx/sbin/nginx /usr/local/bin/nginx\n    vi /phpstudy/server/php/etc/php.ini\n    修改数据库默认密码\n    update mysql.user set password=password(\'123\') where user=\'root\';\n    FLUSH PRIVILEGES;\n    修改date.timezone = Asia/Shanghai\n    修改expose_php = Off\n    chmod a+x yii\n    ./yii init/app\n    common/config/main-local.php (注意 localhost 改成 127.0.0.1) \n    common/config/Env.php （false prod）\n### 相关命令\n    phpstudy start|restart|stop （运行|重启|停止）所有服务\n    nginx -s reload 重启nginx\n    nohup ./yii init/hq & （开启采集数据）\n', '2', '1', '2017-08-08 11:53:23', '1');
INSERT INTO `manual_version` VALUES ('104', '100', '1.类名全小写，多单词时以 - 号分隔，如：\n`<div class=\"main-nav\"></div>`\n2.每个层级缩进4个空格，每个层级下如果不是标签，则需要换行显示如：\n\n\n    <ul>\n        <li>a</li>\n        <li>\n            <span>b</span>\n        </li>\n    </ul>\n\n3.不允许使用style属性\n4. a标签的href属性设为“#”，否则href设置为 \"javascript:;”', '2', '1', '2017-08-10 16:12:33', '1');
INSERT INTO `manual_version` VALUES ('105', '112', '', '1', '1', '2017-08-18 11:14:08', '1');
INSERT INTO `manual_version` VALUES ('106', '112', '> 安装\n\n    1. yum install -y wget && wget -O install.sh http://download.bt.cn/install/install.sh && sh install.sh\n    2. 下载完成后，输入y，进行安装\n> 配置\n \n    1. 修改面板别名\n    2. 修改端口号为9999（如果服务器在阿里云中，则确保在阿里云控制台中的云ECS安全组中已解锁端口封印）\n    3. 修改登录账号为chiswill\n    4. 修改默认登录密码为标准密码', '2', '1', '2017-08-18 11:43:36', '1');
INSERT INTO `manual_version` VALUES ('107', '112', '> 面板安装\n\n    1. yum install -y wget && wget -O install.sh http://download.bt.cn/install/install.sh && sh install.sh\n    2. 下载完成后，输入y，进行安装\n> 面板配置\n \n    1. 修改面板别名\n    2. 修改端口号为9999（如果服务器在阿里云中，则确保在阿里云控制台中的云ECS安全组中已解锁端口封印）\n    3. 修改登录账号为chiswill\n    4. 修改默认登录密码为标准密码\n > 软件安装\n\n    1. nginx-tengine\n    2. mysql5.7\n    3. php7.0\n> 软件配置\n\n###Nginx\n    1. \n    \n### php\n    1. 修改上传限制为10M\n    2. 修改超时时间为30s\n    3. ', '2', '1', '2017-08-18 12:10:45', '1');
INSERT INTO `manual_version` VALUES ('108', '112', '> 面板安装\n\n    1. yum install -y wget && wget -O install.sh http://download.bt.cn/install/install.sh && sh install.sh\n    2. 下载完成后，输入y，进行安装\n> 面板配置\n \n    1. 修改面板别名\n    2. 修改端口号为9999（如果服务器在阿里云中，则确保在阿里云控制台中的云ECS安全组中已解锁端口封印）\n    3. 修改登录账号为chiswill\n    4. 修改默认登录密码为标准密码\n > 软件安装\n\n    1. nginx-tengine\n    2. mysql5.7\n    3. php7.0\n> 软件配置\n\n####Nginx\n    1. \n    \n#### php\n    1. 修改上传限制为10M\n    2. 修改超时时间为30s\n    3. ', '2', '1', '2017-08-18 12:11:08', '1');
INSERT INTO `manual_version` VALUES ('109', '112', '> 面板安装\n\n    1. yum install -y wget && wget -O install.sh http://download.bt.cn/install/install.sh && sh install.sh\n    2. 下载完成后，输入y，进行安装\n> 面板配置\n \n    1. 修改面板别名\n    2. 修改端口号为9999（如果服务器在阿里云中，则确保在阿里云控制台中的云ECS安全组中已解锁端口封印）\n    3. 修改登录账号为chiswill\n    4. 修改默认登录密码为标准密码\n > 软件安装\n\n    1. nginx-tengine\n    2. mysql5.7\n    3. php7.0\n> PHP配置\n   \n    1. 修改上传限制为10M\n    2. 修改超时时间为30s\n    3. 禁用函数中，取消`exec`,`\n    4. 配置文件中open_basedir设置为 \"/www\"\n    5. 配置文件中save_path设置为 \"/www/session\"\n    \n > 服务器段设置\n\n    1. 创建session目录：mkdir /www/session，设置权限：chmod -R 777 /www/session\n    2. 从xinyu-5中，同步 ~/.bashrc 的文件内容，执行：source ~/.bashrc\n    3. 安装SVN\n    ', '2', '1', '2017-08-18 12:25:35', '1');
INSERT INTO `manual_version` VALUES ('110', '112', '> 面板安装\n\n    1. yum install -y wget && wget -O install.sh http://download.bt.cn/install/install.sh && sh install.sh\n    2. 下载完成后，输入y，进行安装\n> 面板配置\n \n    1. 修改面板别名\n    2. 修改端口号为9999（如果服务器在阿里云中，则确保在阿里云控制台中的云ECS安全组中已解锁端口封印）\n    3. 修改登录账号为chiswill\n    4. 修改默认登录密码为标准密码\n > 软件安装\n\n    1. nginx-tengine\n    2. mysql5.7\n    3. php7.0\n> PHP配置\n   \n    1. 修改上传限制为10M\n    2. 修改超时时间为30s\n    3. 禁用函数中，取消`exec`,`\n    4. 配置文件中open_basedir设置为 \"/www\"\n    5. 配置文件中save_path设置为 \"/www/session\"\n    \n > 服务器端配置\n\n    1. 创建session目录：mkdir /www/session，设置权限：chmod -R 777 /www/session\n    2. 从xinyu-5中，同步 ~/.bashrc 的文件内容，执行：source ~/.bashrc\n    3. 安装SVN： yum update && yum -y install subversion\n    ', '2', '1', '2017-08-18 12:26:32', '1');
INSERT INTO `manual_version` VALUES ('111', '112', '> 面板安装\n\n    1. yum install -y wget && wget -O install.sh http://download.bt.cn/install/install.sh && sh install.sh\n    2. 下载完成后，输入y，进行安装\n> 面板配置\n \n    1. 修改面板别名\n    2. 修改端口号为9999（如果服务器在阿里云中，则确保在阿里云控制台中的云ECS安全组中已解锁端口封印）\n    3. 修改登录账号为chiswill\n    4. 修改默认登录密码为标准密码\n > 软件安装\n\n    1. nginx-tengine\n    2. mysql5.7\n    3. php7.0\n> PHP配置\n   \n    1. 修改上传限制为10M\n    2. 修改超时时间为30s\n    3. 禁用函数中，取消`exec`,`passthru`,`system`,`symlink`\n    4. 配置文件中open_basedir设置为 \"/www\"\n    5. 配置文件中save_path设置为 \"/www/session\"\n    \n > 服务器端配置\n\n    1. 创建session目录：mkdir /www/session，设置权限：chmod -R 777 /www/session\n    2. 从xinyu-5中，同步 ~/.bashrc 的文件内容，执行：source ~/.bashrc\n    3. 安装SVN： yum update && yum -y install subversion\n> 添加网站\n    \n    1. 取消日志记录\n    2. 伪静态规则设置为 \"mvc\"', '2', '1', '2017-08-18 19:13:49', '1');
INSERT INTO `manual_version` VALUES ('112', '112', '> 面板安装\n\n    1. yum install -y wget && wget -O install.sh http://download.bt.cn/install/install.sh && sh install.sh\n    2. 下载完成后，输入y，进行安装\n> 面板配置\n \n    1. 修改面板别名\n    2. 修改端口号为9999（如果服务器在阿里云中，则确保在阿里云控制台中的云ECS安全组中已解锁端口封印）\n    3. 修改登录账号为chiswill\n    4. 修改默认登录密码为标准密码\n > 软件安装\n\n    1. nginx-tengine\n    2. mysql5.7\n    3. php7.0\n> PHP配置\n   \n    1. 修改上传限制为10M\n    2. 修改超时时间为30s\n    3. 禁用函数中，取消`exec`,`passthru`,`system`,`symlink`\n    \n > 服务器端配置\n\n    1. 从xinyu-5中，同步 ~/.bashrc 的文件内容，执行：source ~/.bashrc\n    2. 安装SVN： yum update && yum -y install subversion\n> 添加网站\n    \n    1. 取消日志记录\n    2. 伪静态规则设置为 \"mvc\"', '2', '1', '2017-08-24 18:30:13', '1');
INSERT INTO `manual_version` VALUES ('113', '112', '> 面板安装\n\n    1. yum install -y wget && wget -O install.sh http://download.bt.cn/install/install.sh && sh install.sh\n    2. 下载完成后，输入y，进行安装\n> 面板配置\n \n    1. 修改面板别名\n    2. 修改端口号为9999（如果服务器在阿里云中，则确保在阿里云控制台中的云ECS安全组中已解锁端口封印）\n    3. 修改登录账号为chiswill\n    4. 修改默认登录密码为标准密码\n > 软件安装\n\n    1. nginx-tengine\n    2. mysql5.7\n    3. php7.0\n> PHP配置\n   \n    1. 修改上传限制为10M\n    2. 修改超时时间为30s\n    3. 禁用函数中，取消`exec`,`passthru`,`system`,`symlink`\n    \n > 服务器端配置\n\n    1. 从xinyu-5中，同步 ~/.bashrc 的文件内容，执行：source ~/.bashrc\n    2. 安装SVN： yum update && yum -y install subversion\n    3. svn co svn://114.55.63.141/app/app23（下载app23项目）\n> 添加网站\n    \n    1. 移除 frontend/web/.user.ini 文件：\n    chattr -i .user.ini\n    rm .user.ini\n    2. 伪静态规则设置为 \"mvc\"', '2', '1', '2017-08-24 18:32:14', '1');
INSERT INTO `manual_version` VALUES ('114', '112', '> 面板安装\n\n    1. yum install -y wget && wget -O install.sh http://download.bt.cn/install/install.sh && sh install.sh\n    2. 下载完成后，输入y，进行安装\n> 面板配置\n \n    1. 修改面板别名\n    2. 修改端口号为9999（如果服务器在阿里云中，则确保在阿里云控制台中的云ECS安全组中已解锁端口封印）\n    3. 修改登录账号为chiswill\n    4. 修改默认登录密码为标准密码\n > 软件安装\n\n    1. nginx-tengine\n    2. mysql5.7\n    3. php7.0\n> PHP配置\n   \n    1. 修改上传限制为10M\n    2. 修改超时时间为30s\n    3. 禁用函数中，取消`exec`,`passthru`,`system`,`symlink`\n    \n > 服务器端配置\n\n    1. 从xinyu-5中，同步 ~/.bashrc 的文件内容，执行：source ~/.bashrc\n    2. 安装SVN： yum update && yum -y install subversion\n    3. svn co svn://114.55.63.141/app/app23（下载app23项目）\n> 添加网站\n    \n    1. 设置-网站目录，取消防跨域攻击\n    2. 设置-伪静态，规则设置为 \"mvc\"', '2', '1', '2017-08-24 18:45:23', '1');

-- ----------------------------
-- Table structure for map
-- ----------------------------
DROP TABLE IF EXISTS `map`;
CREATE TABLE `map` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(250) NOT NULL COMMENT '名称',
  `type` mediumint(9) DEFAULT '1' COMMENT '映射类型',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of map
-- ----------------------------
INSERT INTO `map` VALUES ('1', '阿里云', '1000');
INSERT INTO `map` VALUES ('2', '轩聆烨', '1001');
INSERT INTO `map` VALUES ('3', '美猴云', '1000');
INSERT INTO `map` VALUES ('4', 'chiswill', '1001');
INSERT INTO `map` VALUES ('5', '猪八戒', '2000');
INSERT INTO `map` VALUES ('6', '淘宝', '2000');
INSERT INTO `map` VALUES ('7', '其他', '2000');

-- ----------------------------
-- Table structure for migration
-- ----------------------------
DROP TABLE IF EXISTS `migration`;
CREATE TABLE `migration` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `version` varchar(80) NOT NULL,
  `apply_time` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='数据库版本迁移表';

-- ----------------------------
-- Records of migration
-- ----------------------------

-- ----------------------------
-- Table structure for oa_app
-- ----------------------------
DROP TABLE IF EXISTS `oa_app`;
CREATE TABLE `oa_app` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(20) NOT NULL COMMENT '项目代号',
  `name` varchar(50) DEFAULT '' COMMENT '项目名称',
  `server_id` int(11) DEFAULT '0' COMMENT '所在服务器',
  `total_amount` decimal(11,2) DEFAULT '0.00' COMMENT '项目金额',
  `rest_amount` decimal(11,2) DEFAULT '0.00' COMMENT '余款',
  `domain` varchar(50) DEFAULT '' COMMENT '域名',
  `ip` varchar(20) DEFAULT '' COMMENT 'IP',
  `ios_sign` varchar(50) DEFAULT '' COMMENT 'IOS月费',
  `monthly` varchar(50) DEFAULT '' COMMENT '月费',
  `server_rent` varchar(100) DEFAULT '' COMMENT '服务器租赁信息',
  `type` varchar(20) DEFAULT '' COMMENT '项目类型',
  `has_simulater` tinyint(4) DEFAULT '1' COMMENT '是否包含模拟软件',
  `server_info` text COMMENT '服务器信息',
  `wechat_info` text COMMENT '微信信息',
  `pay_info` text COMMENT '支付信息',
  `sms_info` text COMMENT '短信接口信息',
  `requirement_info` text COMMENT '需求信息',
  `process_info` text COMMENT '进度描述',
  `created_at` datetime DEFAULT NULL COMMENT '创建时间',
  `created_by` int(11) DEFAULT NULL COMMENT '创建人',
  `updated_at` datetime DEFAULT NULL COMMENT '最后修改时间',
  `updated_by` int(11) DEFAULT NULL COMMENT '最后修改人',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='项目信息表';

-- ----------------------------
-- Records of oa_app
-- ----------------------------

-- ----------------------------
-- Table structure for oa_bonus
-- ----------------------------
DROP TABLE IF EXISTS `oa_bonus`;
CREATE TABLE `oa_bonus` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL COMMENT '员工ID',
  `score` decimal(11,2) NOT NULL COMMENT '业绩',
  `comment` text NOT NULL COMMENT '备注',
  `created_at` datetime DEFAULT NULL COMMENT '完成时间',
  `created_by` int(11) DEFAULT NULL COMMENT '录入人',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='员工业绩记录表';

-- ----------------------------
-- Records of oa_bonus
-- ----------------------------

-- ----------------------------
-- Table structure for oa_finance
-- ----------------------------
DROP TABLE IF EXISTS `oa_finance`;
CREATE TABLE `oa_finance` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `category_id` int(11) NOT NULL COMMENT '分类',
  `amount` decimal(11,2) NOT NULL COMMENT '金额',
  `type` tinyint(4) DEFAULT '1' COMMENT '收支类型',
  `remark` varchar(200) DEFAULT '' COMMENT '备注',
  `created_at` datetime DEFAULT NULL COMMENT '时间',
  `created_by` int(11) DEFAULT NULL COMMENT '录入人',
  `updated_at` datetime DEFAULT NULL COMMENT '更新时间',
  `updated_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `updated_at` (`updated_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of oa_finance
-- ----------------------------

-- ----------------------------
-- Table structure for oa_finance_category
-- ----------------------------
DROP TABLE IF EXISTS `oa_finance_category`;
CREATE TABLE `oa_finance_category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL COMMENT '名称',
  `type` tinyint(4) DEFAULT '1' COMMENT '收支类型',
  `created_at` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of oa_finance_category
-- ----------------------------
INSERT INTO `oa_finance_category` VALUES ('1', '软件开发', '1', '2017-08-09 15:01:36', '1', '2017-08-09 15:01:36', '1');
INSERT INTO `oa_finance_category` VALUES ('2', '服务器', '1', '2017-08-09 15:03:06', '1', '2017-08-09 15:03:06', '1');
INSERT INTO `oa_finance_category` VALUES ('3', '维护费', '1', '2017-08-09 15:03:16', '1', '2017-08-09 15:03:16', '1');
INSERT INTO `oa_finance_category` VALUES ('4', '人工支出', '2', '2017-08-09 15:03:58', '1', '2017-08-09 15:06:37', '1');
INSERT INTO `oa_finance_category` VALUES ('5', '各类租金', '2', '2017-08-09 15:04:07', '1', '2017-08-09 15:06:42', '1');
INSERT INTO `oa_finance_category` VALUES ('6', '进货采购', '2', '2017-08-09 15:04:21', '1', '2017-08-09 15:06:52', '1');
INSERT INTO `oa_finance_category` VALUES ('7', '水电宽带', '2', '2017-08-09 15:07:05', '1', '2017-08-10 11:57:29', '1');
INSERT INTO `oa_finance_category` VALUES ('8', '活动经费', '2', '2017-08-09 15:08:32', '1', '2017-08-09 15:10:15', '1');
INSERT INTO `oa_finance_category` VALUES ('9', '股东分红', '2', '2017-08-09 15:11:26', '1', '2017-08-10 10:05:20', '1');
INSERT INTO `oa_finance_category` VALUES ('10', '营销广告', '2', '2017-08-10 10:05:29', '1', '2017-08-10 10:29:20', '1');
INSERT INTO `oa_finance_category` VALUES ('11', '注册登记', '2', '2017-08-10 10:29:39', '1', '2017-08-10 10:30:32', '1');
INSERT INTO `oa_finance_category` VALUES ('12', '项目退款', '2', '2017-08-10 10:29:49', '1', '2017-08-10 11:09:11', '1');
INSERT INTO `oa_finance_category` VALUES ('13', '其它', '1', '2017-08-10 11:09:19', '1', '2017-08-10 11:09:19', '1');

-- ----------------------------
-- Table structure for oa_menu
-- ----------------------------
DROP TABLE IF EXISTS `oa_menu`;
CREATE TABLE `oa_menu` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '序号',
  `name` varchar(30) NOT NULL COMMENT '菜单名',
  `pid` int(11) DEFAULT '0' COMMENT '父ID',
  `level` smallint(6) DEFAULT '1' COMMENT '层级',
  `sort` int(11) DEFAULT '1' COMMENT '排序值',
  `url` varchar(250) DEFAULT '' COMMENT '跳转链接',
  `icon` varchar(250) DEFAULT NULL COMMENT '图标',
  `is_show` tinyint(4) DEFAULT '1' COMMENT '是否显示',
  `category` tinyint(4) DEFAULT '1' COMMENT '菜单分类',
  `state` tinyint(4) DEFAULT '1' COMMENT '状态',
  `created_at` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=utf8 COMMENT='菜单表';

-- ----------------------------
-- Records of oa_menu
-- ----------------------------
INSERT INTO `oa_menu` VALUES ('1', '系统管理', '0', '1', '1', 'system', '<i class=\"Hui-iconfont\">&#xe62e;</i>', '1', '1', '1', '2016-08-22 17:54:31', '1', '2017-08-09 14:55:54', '1');
INSERT INTO `oa_menu` VALUES ('2', '系统设置', '1', '2', '2', 'system/setting', '', '1', '1', '-1', '2016-08-22 17:54:58', '1', '2016-08-22 17:54:58', '1');
INSERT INTO `oa_menu` VALUES ('3', '系统菜单', '1', '2', '3', 'system/menu', '', '1', '1', '1', '2016-08-22 17:55:35', '1', '2016-08-22 18:59:43', '1');
INSERT INTO `oa_menu` VALUES ('4', '系统日志', '1', '2', '4', 'system/logList', '', '1', '1', '-1', '2016-08-22 18:42:11', '1', '2016-09-02 11:40:45', '1');
INSERT INTO `oa_menu` VALUES ('5', '员工管理', '0', '1', '2', 'admin', '<i class=\"Hui-iconfont\">&#xe62d;</i>', '1', '1', '1', '2016-08-22 18:43:29', '1', '2017-08-09 14:55:54', '1');
INSERT INTO `oa_menu` VALUES ('6', '员工列表', '5', '2', '1', 'admin/list', '', '1', '1', '1', '2016-08-22 18:46:24', '1', '2017-03-19 14:24:18', '1');
INSERT INTO `oa_menu` VALUES ('7', '角色列表', '5', '2', '2', 'admin/roleList', '', '1', '1', '1', '2016-08-22 18:46:50', '1', '2017-03-19 14:24:18', '1');
INSERT INTO `oa_menu` VALUES ('8', '权限列表', '5', '2', '3', 'admin/permissionList', '', '1', '1', '1', '2016-08-22 18:47:10', '1', '2017-03-19 14:24:18', '1');
INSERT INTO `oa_menu` VALUES ('9', '客户管理', '0', '1', '3', 'user', '<i class=\"Hui-iconfont\">&#xe60d;</i>', '1', '1', '1', '2016-08-22 18:47:49', '1', '2017-08-09 14:55:54', '1');
INSERT INTO `oa_menu` VALUES ('10', '客户列表', '9', '2', '1', 'user/list', '', '1', '1', '1', '2016-08-22 18:48:13', '1', '2017-05-11 15:52:32', '1');
INSERT INTO `oa_menu` VALUES ('11', '项目管理', '0', '1', '4', 'app', '<i class=\"Hui-iconfont\">&#xe616;</i>', '1', '1', '1', '2016-08-22 18:48:55', '1', '2017-08-09 14:55:54', '1');
INSERT INTO `oa_menu` VALUES ('12', '资讯列表', '11', '2', '2', 'article/list', '', '1', '1', '-1', '2016-08-22 18:49:15', '1', '2016-11-07 16:16:43', '1');
INSERT INTO `oa_menu` VALUES ('13', '图片管理', '0', '1', '5', 'picture', '<i class=\"Hui-iconfont\">&#xe613;</i>', '1', '1', '-1', '2016-08-22 18:49:39', '1', '2016-09-01 17:21:03', '1');
INSERT INTO `oa_menu` VALUES ('14', '图片列表', '13', '2', '2', 'picture/list', '', '1', '1', '-1', '2016-08-22 18:49:54', '1', '2016-08-22 18:49:54', '1');
INSERT INTO `oa_menu` VALUES ('15', '产品管理', '0', '1', '6', 'product', '<i class=\"Hui-iconfont\">&#xe620;</i>', '1', '1', '-1', '2016-08-22 18:51:04', '1', '2016-09-01 17:21:03', '1');
INSERT INTO `oa_menu` VALUES ('16', '产品列表', '15', '2', '2', 'product/list', '', '1', '1', '-1', '2016-08-22 18:51:18', '1', '2016-08-22 18:51:18', '1');
INSERT INTO `oa_menu` VALUES ('17', '评论管理', '0', '1', '7', 'comment', '<i class=\"Hui-iconfont\">&#xe622;</i>', '1', '1', '-1', '2016-08-22 18:51:35', '1', '2016-09-01 17:21:03', '1');
INSERT INTO `oa_menu` VALUES ('18', '评论列表', '17', '2', '2', 'comment/list', '', '1', '1', '-1', '2016-08-22 18:52:10', '1', '2016-08-22 18:52:10', '1');
INSERT INTO `oa_menu` VALUES ('19', '系统统计', '0', '1', '9', '', '<i class=\"Hui-iconfont\">&#xe61a;</i>', '1', '1', '-1', '2016-08-22 19:00:05', '1', '2016-09-01 17:40:33', '1');
INSERT INTO `oa_menu` VALUES ('20', '项目列表', '11', '2', '1', 'app/list', '', '1', '1', '1', '2016-11-07 16:16:40', '1', '2016-11-17 17:36:22', '1');
INSERT INTO `oa_menu` VALUES ('21', '员工业绩', '5', '2', '4', 'admin/bonusList', '', '1', '1', '-1', '2017-03-19 14:24:14', '1', '2017-03-19 14:24:18', '1');
INSERT INTO `oa_menu` VALUES ('22', '产品列表', '9', '2', '2', 'user/productList', '', '1', '1', '1', '2017-05-11 15:52:00', '1', '2017-05-11 15:52:32', '1');
INSERT INTO `oa_menu` VALUES ('23', '统计报表', '9', '2', '3', 'user/statisticsList', '', '1', '1', '1', '2017-05-11 15:52:20', '1', '2017-05-11 15:52:32', '1');
INSERT INTO `oa_menu` VALUES ('24', '费用列表', '11', '2', '1', 'app/feeList', '', '1', '1', '1', '2017-06-18 00:10:01', '1', '2017-06-18 00:10:01', '1');
INSERT INTO `oa_menu` VALUES ('25', '任务列表', '11', '2', '1', 'app/taskList', '', '1', '1', '1', '2017-07-24 23:07:40', '1', '2017-07-24 23:07:40', '1');
INSERT INTO `oa_menu` VALUES ('26', '财务管理', '0', '1', '5', 'finance', '<i class=\"Hui-iconfont\"></i>', '1', '1', '1', '2017-08-09 14:52:31', '1', '2017-08-09 14:55:54', '1');
INSERT INTO `oa_menu` VALUES ('27', '员工业绩', '26', '2', '1', 'admin/bonusList', '', '1', '1', '1', '2017-08-09 14:52:50', '1', '2017-08-09 14:56:12', '1');
INSERT INTO `oa_menu` VALUES ('28', '资金明细', '26', '2', '1', 'finance/accountList', '', '1', '1', '1', '2017-08-09 14:53:27', '1', '2017-08-09 14:53:27', '1');
INSERT INTO `oa_menu` VALUES ('29', '类别管理', '26', '2', '1', 'finance/categoryList', '', '1', '1', '1', '2017-08-09 14:55:18', '1', '2017-08-09 14:55:28', '1');
INSERT INTO `oa_menu` VALUES ('30', '服务器列表', '11', '2', '1', 'app/serverList', '', '1', '1', '1', '2017-10-01 22:38:48', '1', '2017-10-01 22:38:48', '1');

-- ----------------------------
-- Table structure for oa_process
-- ----------------------------
DROP TABLE IF EXISTS `oa_process`;
CREATE TABLE `oa_process` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `app_id` int(11) NOT NULL,
  `desc` text NOT NULL COMMENT '进度描述',
  `created_at` datetime DEFAULT NULL COMMENT '发表时间',
  `created_by` int(11) DEFAULT NULL COMMENT '发表人',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='项目进度记录表';

-- ----------------------------
-- Records of oa_process
-- ----------------------------

-- ----------------------------
-- Table structure for oa_product
-- ----------------------------
DROP TABLE IF EXISTS `oa_product`;
CREATE TABLE `oa_product` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL COMMENT '产品名称',
  `desc` varchar(900) DEFAULT '' COMMENT '描述',
  `version` varchar(30) DEFAULT '1.0' COMMENT '版本',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of oa_product
-- ----------------------------

-- ----------------------------
-- Table structure for oa_server
-- ----------------------------
DROP TABLE IF EXISTS `oa_server`;
CREATE TABLE `oa_server` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `server_name` varchar(100) NOT NULL COMMENT '服务器名称',
  `server_ip` varchar(100) NOT NULL COMMENT '服务器IP',
  `quoted_price` decimal(11,2) NOT NULL COMMENT '报价',
  `discount_price` decimal(11,2) NOT NULL COMMENT '折扣价',
  `platform_id` int(11) NOT NULL COMMENT '所属平台',
  `account_id` int(11) NOT NULL COMMENT '账号名',
  `remark` text COMMENT '备注信息',
  `use_state` tinyint(4) DEFAULT '1' COMMENT '使用状态',
  `created_at` datetime DEFAULT NULL COMMENT '创建日期',
  `updated_at` datetime DEFAULT NULL COMMENT '更新日期',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of oa_server
-- ----------------------------

-- ----------------------------
-- Table structure for oa_task
-- ----------------------------
DROP TABLE IF EXISTS `oa_task`;
CREATE TABLE `oa_task` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `app_id` int(11) NOT NULL COMMENT '项目ID',
  `content` text NOT NULL COMMENT '任务内容',
  `hour` float(11,1) NOT NULL COMMENT '小时',
  `user_id` int(11) DEFAULT '0' COMMENT '处理人',
  `urgency_level` tinyint(4) DEFAULT '1' COMMENT '紧急度',
  `task_state` tinyint(4) DEFAULT '1' COMMENT '任务状态：1未处理，2处理中，3已完成',
  `created_at` datetime DEFAULT NULL COMMENT '发布时间',
  `created_by` int(11) DEFAULT NULL COMMENT '发布人',
  `updated_at` datetime DEFAULT NULL COMMENT '处理时间',
  `updated_by` int(11) DEFAULT NULL COMMENT '更新人',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='项目任务表';

-- ----------------------------
-- Records of oa_task
-- ----------------------------

-- ----------------------------
-- Table structure for oa_tips
-- ----------------------------
DROP TABLE IF EXISTS `oa_tips`;
CREATE TABLE `oa_tips` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `target_id` int(11) DEFAULT '0',
  `field` varchar(20) DEFAULT '',
  `type` tinyint(4) DEFAULT '1' COMMENT '提示类别：1项目信息，2客户信息',
  `read_state` tinyint(4) DEFAULT '-1',
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_id` (`user_id`,`target_id`,`field`,`type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of oa_tips
-- ----------------------------

-- ----------------------------
-- Table structure for oa_user
-- ----------------------------
DROP TABLE IF EXISTS `oa_user`;
CREATE TABLE `oa_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '序号',
  `name` varchar(50) DEFAULT '' COMMENT '姓名',
  `type` tinyint(4) DEFAULT '1' COMMENT '客户类型：1普通客户，2专属客户，3开发商，4供货商，5渠道商',
  `product_id` int(11) DEFAULT '0' COMMENT '产品ID',
  `level` tinyint(11) DEFAULT '1' COMMENT '客户等级：1新客户，2潜在客户，3准成交客户，4成交客户，5VIP客户，-1暂无意向客户，-2刺探型客户，-3已放弃客户',
  `amount` decimal(11,2) DEFAULT '0.00' COMMENT '项目金额',
  `is_chat` tinyint(4) DEFAULT '1' COMMENT '是否正在洽谈',
  `tel` varchar(50) DEFAULT '' COMMENT '联系电话',
  `wechat_id` varchar(50) DEFAULT '' COMMENT '微信号',
  `qq` varchar(20) DEFAULT '' COMMENT 'QQ',
  `source` tinyint(4) DEFAULT '1' COMMENT '客户来源',
  `requirement` text COMMENT '需求',
  `contact_time` datetime DEFAULT NULL COMMENT '最后联系时间',
  `created_at` datetime DEFAULT NULL COMMENT '创建时间',
  `created_by` int(11) DEFAULT NULL COMMENT '联系人',
  `updated_at` datetime DEFAULT NULL COMMENT '最后更新时间',
  `updated_by` int(11) DEFAULT NULL COMMENT '最后修改人',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='客户表';

-- ----------------------------
-- Records of oa_user
-- ----------------------------

-- ----------------------------
-- Table structure for oa_user_contact
-- ----------------------------
DROP TABLE IF EXISTS `oa_user_contact`;
CREATE TABLE `oa_user_contact` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `content` text NOT NULL COMMENT '联系内容',
  `created_at` datetime DEFAULT NULL COMMENT '联系时间',
  `created_by` int(11) DEFAULT NULL COMMENT '联系人',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='客户联系记录表';

-- ----------------------------
-- Records of oa_user_contact
-- ----------------------------

-- ----------------------------
-- Table structure for option
-- ----------------------------
DROP TABLE IF EXISTS `option`;
CREATE TABLE `option` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `option_name` varchar(64) NOT NULL DEFAULT '' COMMENT '配置名',
  `option_value` longtext COMMENT '配置值',
  `type` tinyint(4) DEFAULT '1' COMMENT '配置类型',
  `state` tinyint(4) DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `option_name` (`option_name`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COMMENT='参数配置表';

-- ----------------------------
-- Records of option
-- ----------------------------
INSERT INTO `option` VALUES ('1', 'common_settings', 'a:16:{i:0;a:10:{s:2:\"id\";i:1;s:3:\"pid\";s:1:\"0\";s:4:\"name\";s:12:\"网站设置\";s:3:\"var\";N;s:5:\"value\";N;s:4:\"type\";N;s:5:\"alter\";N;s:7:\"comment\";N;s:5:\"level\";i:1;s:7:\"uploads\";a:0:{}}i:1;a:10:{s:2:\"id\";i:9;s:3:\"pid\";s:1:\"0\";s:4:\"name\";s:12:\"微信设置\";s:3:\"var\";N;s:5:\"value\";N;s:4:\"type\";N;s:5:\"alter\";N;s:7:\"comment\";N;s:5:\"level\";i:1;s:7:\"uploads\";a:0:{}}i:2;a:10:{s:2:\"id\";i:2;s:3:\"pid\";s:1:\"1\";s:4:\"name\";s:12:\"公共设置\";s:3:\"var\";N;s:5:\"value\";N;s:4:\"type\";N;s:5:\"alter\";N;s:7:\"comment\";N;s:5:\"level\";i:2;s:7:\"uploads\";a:0:{}}i:3;a:10:{s:2:\"id\";i:5;s:3:\"pid\";s:1:\"1\";s:4:\"name\";s:9:\"SEO设置\";s:3:\"var\";N;s:5:\"value\";N;s:4:\"type\";N;s:5:\"alter\";N;s:7:\"comment\";N;s:5:\"level\";i:2;s:7:\"uploads\";a:0:{}}i:4;a:10:{s:2:\"id\";i:10;s:3:\"pid\";s:1:\"9\";s:4:\"name\";s:12:\"公共参数\";s:3:\"var\";N;s:5:\"value\";N;s:4:\"type\";N;s:5:\"alter\";N;s:7:\"comment\";N;s:5:\"level\";i:2;s:7:\"uploads\";a:0:{}}i:5;a:10:{s:2:\"id\";i:3;s:3:\"pid\";s:1:\"2\";s:4:\"name\";s:12:\"网站名称\";s:3:\"var\";s:8:\"web_name\";s:5:\"value\";s:11:\"Yii2-Origin\";s:4:\"type\";s:4:\"text\";s:5:\"alter\";N;s:7:\"comment\";s:24:\"网站名称，2-6个字\";s:5:\"level\";i:3;s:7:\"uploads\";a:0:{}}i:6;a:10:{s:2:\"id\";i:4;s:3:\"pid\";s:1:\"2\";s:4:\"name\";s:10:\"网站LOGO\";s:3:\"var\";s:8:\"web_logo\";s:5:\"value\";N;s:4:\"type\";s:4:\"file\";s:5:\"alter\";N;s:7:\"comment\";s:10:\"网站LOGO\";s:5:\"level\";i:3;s:7:\"uploads\";a:0:{}}i:7;a:10:{s:2:\"id\";i:6;s:3:\"pid\";s:1:\"5\";s:4:\"name\";s:9:\"SEO开关\";s:3:\"var\";s:10:\"seo_switch\";s:5:\"value\";s:1:\"1\";s:4:\"type\";s:5:\"radio\";s:5:\"alter\";s:34:\"a:2:{i:1;s:3:\"开\";i:0;s:3:\"关\";}\";s:7:\"comment\";s:42:\"是否开启SEO的默认关键字和描述\";s:5:\"level\";i:3;s:7:\"uploads\";a:0:{}}i:8;a:10:{s:2:\"id\";i:7;s:3:\"pid\";s:1:\"5\";s:4:\"name\";s:9:\"关键字\";s:3:\"var\";s:7:\"seo_key\";s:5:\"value\";s:0:\"\";s:4:\"type\";s:4:\"text\";s:5:\"alter\";N;s:7:\"comment\";s:21:\"SEO的默认关键字\";s:5:\"level\";i:3;s:7:\"uploads\";a:0:{}}i:9;a:10:{s:2:\"id\";i:8;s:3:\"pid\";s:1:\"5\";s:4:\"name\";s:6:\"描述\";s:3:\"var\";s:8:\"seo_desc\";s:5:\"value\";s:0:\"\";s:4:\"type\";s:8:\"textarea\";s:5:\"alter\";N;s:7:\"comment\";s:18:\"SEO的默认描述\";s:5:\"level\";i:3;s:7:\"uploads\";a:0:{}}i:10;a:10:{s:2:\"id\";i:11;s:3:\"pid\";s:1:\"2\";s:4:\"name\";s:12:\"短信签名\";s:3:\"var\";s:8:\"web_sign\";s:5:\"value\";s:0:\"\";s:4:\"type\";s:4:\"text\";s:5:\"alter\";N;s:7:\"comment\";s:27:\"短信签名，2-5个汉字\";s:5:\"level\";i:3;s:7:\"uploads\";a:0:{}}i:11;a:10:{s:2:\"id\";i:12;s:3:\"pid\";s:2:\"10\";s:4:\"name\";s:5:\"AppId\";s:3:\"var\";s:8:\"wx_appid\";s:5:\"value\";N;s:4:\"type\";s:4:\"text\";s:5:\"alter\";N;s:7:\"comment\";s:23:\"微信公众号的AppId\";s:5:\"level\";i:3;s:7:\"uploads\";a:0:{}}i:12;a:10:{s:2:\"id\";i:13;s:3:\"pid\";s:2:\"10\";s:4:\"name\";s:9:\"AppSecret\";s:3:\"var\";s:12:\"wx_appsecret\";s:5:\"value\";N;s:4:\"type\";s:4:\"text\";s:5:\"alter\";N;s:7:\"comment\";s:27:\"微信公众号的AppSecret\";s:5:\"level\";i:3;s:7:\"uploads\";a:0:{}}i:13;a:10:{s:2:\"id\";i:14;s:3:\"pid\";s:2:\"10\";s:4:\"name\";s:5:\"MCHID\";s:3:\"var\";s:8:\"wx_mchid\";s:5:\"value\";N;s:4:\"type\";s:4:\"text\";s:5:\"alter\";N;s:7:\"comment\";s:17:\"微信商户号ID\";s:5:\"level\";i:3;s:7:\"uploads\";a:0:{}}i:14;a:10:{s:2:\"id\";i:15;s:3:\"pid\";s:2:\"10\";s:4:\"name\";s:3:\"KEY\";s:3:\"var\";s:6:\"wx_key\";s:5:\"value\";N;s:4:\"type\";s:4:\"text\";s:5:\"alter\";N;s:7:\"comment\";s:15:\"微信秘钥KEY\";s:5:\"level\";i:3;s:7:\"uploads\";a:0:{}}i:15;a:10:{s:2:\"id\";i:16;s:3:\"pid\";s:1:\"2\";s:4:\"name\";s:9:\"Copyright\";s:3:\"var\";s:13:\"web_copyright\";s:5:\"value\";s:0:\"\";s:4:\"type\";s:8:\"textarea\";s:5:\"alter\";N;s:7:\"comment\";s:12:\"版权信息\";s:5:\"level\";i:3;s:7:\"uploads\";a:0:{}}}', '1', '1');
INSERT INTO `option` VALUES ('2', 'console_settings', 'a:0:{}', '1', '1');
INSERT INTO `option` VALUES ('3', 'apiConfig', 'a:2:{s:3:\"one\";a:2:{s:3:\"url\";s:18:\"http://cpcp008.com\";s:4:\"jump\";s:1:\"0\";}s:3:\"two\";a:2:{s:3:\"url\";s:0:\"\";s:4:\"jump\";s:0:\"\";}}', '2', '1');

-- ----------------------------
-- Table structure for test
-- ----------------------------
DROP TABLE IF EXISTS `test`;
CREATE TABLE `test` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `mobile` char(11) DEFAULT NULL,
  `title` char(20) DEFAULT NULL,
  `message` text,
  `reg_date` date DEFAULT NULL,
  `state` tinyint(4) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='测试表（表结构可以随意调整）';

-- ----------------------------
-- Records of test
-- ----------------------------

-- ----------------------------
-- Table structure for user
-- ----------------------------
DROP TABLE IF EXISTS `user`;
CREATE TABLE `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(20) NOT NULL COMMENT '用户名',
  `password` varchar(80) NOT NULL COMMENT '密码',
  `mobile` char(11) DEFAULT '' COMMENT '手机号',
  `nickname` varchar(20) DEFAULT '' COMMENT '昵称',
  `account` decimal(11,2) DEFAULT '0.00' COMMENT '账户余额',
  `sex` tinyint(4) DEFAULT '1' COMMENT '性别：1男，2女',
  `area` varchar(100) DEFAULT '' COMMENT '地区',
  `vip` tinyint(4) DEFAULT '0' COMMENT 'VIP等级',
  `face` varchar(80) DEFAULT '' COMMENT '头像',
  `state` tinyint(4) DEFAULT '1' COMMENT '状态',
  `created_at` datetime DEFAULT NULL COMMENT '注册时间',
  `created_by` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='用户表';

-- ----------------------------
-- Records of user
-- ----------------------------
INSERT INTO `user` VALUES ('1', 'ChisWill', '$2y$13$a8vBCI7Ah7MNoXV7O6gokuGYmv1FHLZx7amWEFSn1K4zI8JE9rJpi', '', '', '3.00', '1', '', '0', '', '1', '2016-08-10 14:39:09', '0', '2017-06-19 17:36:07', '0');

-- ----------------------------
-- Table structure for user_charge
-- ----------------------------
DROP TABLE IF EXISTS `user_charge`;
CREATE TABLE `user_charge` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL COMMENT '用户ID',
  `trade_no` varchar(250) NOT NULL COMMENT '订单编号',
  `amount` decimal(11,2) NOT NULL COMMENT '充值金额',
  `rest_amount` decimal(11,2) NOT NULL COMMENT '余额',
  `fee` decimal(11,2) DEFAULT '0.00' COMMENT '手续费',
  `charge_type` tinyint(4) DEFAULT '1' COMMENT '充值方式',
  `charge_state` tinyint(4) DEFAULT '1' COMMENT '充值状态：1待付款，2成功，3失败',
  `created_at` datetime DEFAULT NULL COMMENT '充值时间',
  `updated_at` datetime DEFAULT NULL COMMENT '到账时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='充值记录表';

-- ----------------------------
-- Records of user_charge
-- ----------------------------
