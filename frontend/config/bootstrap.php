<?php
// 后台作为一个子模块，嵌入项目内，缩略命名空间长度
Yii::setAlias('admin', dirname(__DIR__) . '/modules/admin');
Yii::setAlias('wxmp', dirname(__DIR__) . '/modules/wxmp');
Yii::setAlias('es', dirname(__DIR__) . '/modules/es');
Yii::setAlias('shudu', dirname(__DIR__) . '/modules/shudu');
Yii::setAlias('chat', dirname(__DIR__) . '/modules/chat');
Yii::setAlias('pay', dirname(__DIR__) . '/modules/pay');
Yii::setAlias('php', dirname(__DIR__) . '/modules/php');
Yii::setAlias('oa', dirname(__DIR__) . '/modules/oa');
// 前台项目常量配置
const ROUTE_OA = 'oa';
const ROUTE_BACKEND = 'admin';
const ROUTE_PHP = 'php';
