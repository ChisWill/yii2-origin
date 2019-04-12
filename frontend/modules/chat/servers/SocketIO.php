<?php

namespace chat\servers;

use common\helpers\ArrayHelper;

/**
 * SocketIO 服务端
 * 
 * @author ChisWill
 */
class SocketIO extends \console\components\Server
{
    const SOCKET_PORT = 39000;
    /**
     * @var object SocketIO 连接对象
     */
    public $io;
    /**
     * @var array 根据每个socket链接的ID，保存用户ID
     */
    public static $conn = [];
    /**
     * @var array 根据用户ID，保存socket连接
     */
    public static $users = [];
    /**
     * @var array 要绑定的事件列表
     */
    protected $events = [
        'disconnect',
        'login',
        'msg',
        'picture',
        'file'
    ];

    /**
     * 服务执行入口
     * 
     * @param  object $socket SocketIO 连接对象
     */
    public function run($socket)
    {
        $events = ArrayHelper::resetOptions($this->events, ['key' => 'event', 'value' => 'func']);
        foreach ($events as $params) {
            $event = $params['event'];
            $func = ArrayHelper::getValue($event, 'func', [$this, $event]);
            $socket->on($event, function ($data = null) use ($func, $socket) {
                try {
                    call_user_func($func, $socket, $data);
                } catch (\Exception $e) {
                    self::log($e, 'ChatSocketIO/error');
                }
            });
        }
    }

    public function getUserSocket($userId)
    {
        if ($this->isOnline($userId)) {
            return static::$users[$userId];
        } else {
            return false;
        }
    }

    public function isOnline($userId)
    {
        return isset(static::$users[$userId]);
    }

    public function success($type, $value)
    {
        return compact('type', 'value');
    }

    protected function disconnect($socket, $data)
    {
        $this->logout($socket);
    }

    private function logout($socket)
    {
        if (isset(static::$conn[$socket->id])) {
            unset(static::$users[$socket->userId]);
            unset(static::$conn[$socket->id]);
        }
    }

    protected function login($socket, $id)
    {
        // 如果已经有登录，则踢出之前登录的人
        $another = $this->getUserSocket($id);
        if ($another !== false) {
            $this->kick($another);
        }
        if (!isset($socket->userId)) {
            $socket->userId = $id;
            static::$conn[$socket->id] = $id;
            static::$users[$id] = $socket;
        }
    }

    public function kick($socket)
    {
        $this->logout($socket);
        $socket->emit('response', $this->success('system', [
            'event' => 'kick',
            'content' => '已在他处登录'
        ]));
    }

    protected function msg($socket, $data)
    {
        $target = $this->getUserSocket($data['to']);
        if ($target) {
            $target->emit('response', $this->success('msg', [
                'from' => $socket->userId,
                'name' => $data['name'],
                'face' => $data['face'],
                'isGuest' => $data['isGuest'],
                'content' => $data['content'],
                'time' => time()
            ]));
        } else {
            $socket->emit('response', $this->success('system', [
                'from' => $data['to'],
                'event' => 'offline',
                'content' => '对方不在线',
                'time' => time()
            ]));
        }
    }

    protected function picture($socket, $data)
    {
        $target = $this->getUserSocket($data['to']);
        if ($target) {
            $target->emit('response', $this->success('picture', [
                'from' => $socket->userId,
                'name' => $data['name'],
                'face' => $data['face'],
                'isGuest' => $data['isGuest'],
                'content' => $data['content'],
                'time' => time()
            ]));
        } else {
            $socket->emit('response', $this->success('system', [
                'from' => $data['to'],
                'event' => 'offline',
                'content' => '对方不在线',
                'time' => time()
            ]));
        }
    }

    protected function file($socket, $data)
    {
        $target = $this->getUserSocket($data['to']);
        if ($target) {
            $target->emit('response', $this->success('file', [
                'from' => $socket->userId,
                'name' => $data['name'],
                'face' => $data['face'],
                'isGuest' => $data['isGuest'],
                'content' => $data['content'],
                'time' => time(),
                'originName'=> $data['originName'],
                'size'=> $data['size']

            ]));
        } else {
            $socket->emit('response', $this->success('system', [
                'from' => $data['to'],
                'event' => 'offline',
                'content' => '对方不在线',
                'time' => time()
            ]));
        }
    }
}
