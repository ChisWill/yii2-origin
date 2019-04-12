<?php
namespace admin\models;

use Yii;
use common\helpers\System;
use common\helpers\FileHelper;

class LoginForm extends \common\components\Model
{
    public $username;
    public $password;
    public $captcha;
    public $rememberMe = true;

    private $_user;

    public function rules()
    {
        return [
            [['username', 'password'], 'required'],
            ['rememberMe', 'boolean'],
            ['password', 'validatePassword'],
            ['captcha', 'captcha', 'skipOnEmpty' => !session('requireCaptcha') || System::skipCaptcha()],
            ['rememberMe', 'boolean']
        ];
    }

    public function attributeLabels()
    {
        return [
            'username' => '账号',
            'password' => '密码'
        ];
    }

    public function validatePassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();
            if (!$user || !$user->validatePassword($this->password)) {
                $this->addError($attribute, '错误的用户名或密码.');
            }
        }
    }

    public function login()
    {
        if ($this->validate()) {
            $user = $this->getUser();
            if (!defined('YII_LOGIN')) {
                $user->login_time = date('Y-m-d H:i:s');
                $user->last_ip = $user->login_ip;
                $user->login_ip = req()->getUserIP();
                $user->update();
                AdminAction::add(AdminAction::TYPE_SELECT, 'admin_user', $user->id);
            }
            return user()->login($user, $this->rememberMe ? 3600 * 24 * 30 : 0);
        } else {
            return false;
        }
    }

    protected function getUser()
    {
        if ($this->_user === null) {
            $this->_user = \admin\components\AdminWebUser::findByUsername($this->username);
        }
        return $this->_user;
    }
}
