<?php

namespace console\controllers;

use console\components\Worker;

/**
 * PHPSocket.IO Server base on Workerman.
 * 
 * @author ChisWill
 */
class SocketController extends \common\components\ConsoleController
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
        $this->io = new \PHPSocketIO\SocketIO(config('socketIoPort'));

        $this->io->on('connection', [new \console\servers\SocketIO, 'run']);
        
        $this->io->on('workerStart', function () {
            // 监听一个http端口
            $httpWorker = new Worker('http://0.0.0.0:' . config('httpPushPort'));
            // 当客户端发来数据时触发
            $httpWorker->onMessage = [new \console\servers\Http(['io' => $this->io]), 'run'];
            // 执行监听
            $httpWorker->listen();
        });

        Worker::runAll();
    }

    /**
     * Server-Push Example
     */
    public function actionPush()
    {
        // 指明给谁推送，为空表示向所有在线用户推送
        $toUid = '1';
        // 推送的url地址，上线时改成自己的服务器地址
        $pushApiUrl = config('webDomain') . ':' . config('httpPushPort');

        // 推送数据，必须包含 event 参数，指定处理该消息事件 的方法
        $postData = [
           'content' => '推送测试 Message ~！',
           'to' => $toUid,
           'event' => 'demo'
        ];

        $return = \common\helpers\Curl::post($pushApiUrl, $postData);
       
        var_export($return);
    }
}
