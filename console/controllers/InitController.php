<?php

namespace console\controllers;

use Yii;
use common\helpers\System;
use common\helpers\FileHelper;

class InitController extends \common\components\ConsoleController
{
    // 初始化文件夹权限（首次执行）
    public function actionApp()
    {
        $dirs = [
            'frontend' => [
                'runtime',
                'web/assets',
                'web/uploadfile'
            ],
            'console' => [
                'runtime'
            ],
            'api' => [
                'runtime'
            ]
        ];
        foreach ($dirs as $app => $items) {
            $bathPath = Yii::getAlias('@' . $app);
            foreach ($items as $dir) {
                $path = $bathPath . '/' . $dir;
                FileHelper::mkdir($path);
                exec('chmod -R 777 ' . $path);
            }
        }
    }

    // 初始化数据库和表结构，数据库名默认和项目名相同（首次执行）
    public function actionSql()
    {
        try {
            $path = System::isWindowsOs() ? '' : './';
            for ($i = 0; $i <= 1; $i++) {
                exec(sprintf('%syii init/exec-sql %d', $path, $i), $return);
                echo $return[$i] . "\n";
            }
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
    }

    public function actionExecSql($key)
    {
        $tableName = basename(path('@base'));
        $config = [[
                'sql' => 'CREATE DATABASE IF NOT EXISTS `' . $tableName . '` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;',
                'dsn' => 'mysql:host=127.0.0.1;',
                'info' => '成功创建数据库'
            ], [
                'sql' => file_get_contents(path('@base/init.sql')),
                'dsn' => 'mysql:host=127.0.0.1;dbname=' . $tableName,
                'info' => '初始化表完毕'
            ]
        ];
        Yii::$app->db->dsn = $config[$key]['dsn'];
        self::db($config[$key]['sql'])->execute();
        echo $config[$key]['info'];
    }    
}
