<?php

namespace frontend\models;

use Yii;
use frontend\components\WebUser;

class User extends \common\models\User
{
    // 虚拟字段
    public $oldPassword;
    public $newPassword;
    public $cfmPassword;
    public $registerMobile;
    public $rememberMe;
    public $verifyCode;
    public $captcha;

    protected $_identity;

    public function rules()
    {
        return array_merge(parent::rules(), [
            // 密码规则，注册和修改密码时复用同一个规则
            [['password', 'newPassword'], 'match', 'pattern' => '/[a-z0-9~!@#$%^]{6,}/Ui', 'on' => ['register', 'password'], 'message' => '{attribute}至少6位'],
            // 注册场景的基础验证
            [['cfmPassword', 'verifyCode'], 'required', 'on' => 'register'],
            // 注册场景密码和确认密码的验证
            [['password'], 'compare', 'compareAttribute' => 'cfmPassword', 'on' => 'register'],
            // 注册场景验证短信手机号和实际手机号的验证
            [['mobile'], 'compare', 'compareAttribute' => 'registerMobile', 'on' => 'register'],
            // 修改密码场景的基础验证
            [['oldPassword', 'newPassword', 'cfmPassword'], 'required', 'on' => 'password'],
            // 修改密码验证旧密码
            [['oldPassword'], 'validateOldPassword'],
            // 修改密码场景新密码与验证密码的验证
            [['newPassword'], 'compare', 'compareAttribute' => 'cfmPassword'],
            // 短信验证码
            [['verifyCode'], 'verifyCode'],
            // 验证码
            [['captcha'], 'captcha'],
            // 其他规则
        ]);
    }

    public function scenarios()
    {
        return array_merge(parent::scenarios(), [
            'register' => ['username', 'password', 'nickname', 'cfmPassword', 'captcha', 'mobile', 'verifyCode'],
            'login' => ['username', 'password', 'rememberMe'],
            'password' => ['oldPassword', 'newPassword', 'cfmPassword'],
        ]);
    }

    public function attributeLabels()
    {
        return array_merge(parent::attributeLabels(), [
            'oldPassword' => '旧密码',
            'newPassword' => '新密码',
            'cfmPassword' => '确认密码',
            'rememberMe' => '记住我',
            'verifyCode' => '短信验证码',
            'captcha' => '验证码',
            'registerMobile' => '验证手机号'
        ]);
    }

    public function verifyCode()
    {
        if ($this->verifyCode != session('verifyCode')) {
            $this->addError('verifyCode', '短信验证码不正确');
        }
    }

    public function validateOldPassword()
    {
        if (!u()->validatePassword($this->oldPassword)) {
            $this->addError('oldPassword', '旧密码不正确');
        }
    }

    protected function beforeLogin()
    {
        if (!$this->username) {
            $this->addError('username', '请输入用户名');
        }
        if (!$this->password) {
            $this->addError('password', '请输入密码');
        }
        if ($this->hasErrors()) {
            return false;
        }
        $identity = $this->getIdentity();
        if (!$identity || !$identity->validatePassword($this->password)) {
            $this->addError('password', '用户名或密码错误');
            return false;
        } else {
            return true;
        }
    }

    protected function getIdentity()
    {
        if ($this->_identity === null) {
            $this->_identity = WebUser::findByUsername($this->username);
        }

        return $this->_identity;
    }

    public function login($runValidation = true)
    {
        if ($runValidation && !$this->beforeLogin()) {
            return !$this->hasErrors();
        }

        return user()->login($this->getIdentity(), $this->rememberMe ? 3600 * 24 * 30 : 0);
    }
}
