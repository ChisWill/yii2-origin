<?php

namespace console\controllers;

use Yii;
use console\components\Worker;

/**
 * 将该文件复制到 console/controller 下
 * 
 * @author ChisWill
 */
class ChatController extends \common\components\ConsoleController
{
    /**
     * @var object SocketIO 连接对象
     */
    protected $io;

    public function init()
    {
        parent::init();
    }

    /**
     * 设置 Workerman 的启动参数
     */
    public function optionAliases()
    {
        return [
            'd' => 1
        ];
    }

    /**
     * Start Workerman Server
     */
    public function actionIndex()
    {
        Yii::setAlias('chat', path('@base') . '/frontend/modules/chat');

        $this->io = new \PHPSocketIO\SocketIO(\chat\servers\SocketIO::SOCKET_PORT);
        $this->io->on('connection', [new \chat\servers\SocketIO(['io' => $this->io]), 'run']);

        Worker::runAll();
    }
}
