<?php

namespace php\models;

use Yii;
use frontend\components\WebUser;

class User extends \frontend\models\User
{
    public function rules()
    {
        return array_merge(parent::rules(), [
            ['username', 'email'],
        ]);
    }

    public function scenarios()
    {
        return array_merge(parent::scenarios(), [
            'register' => ['username', 'password', 'cfmPassword', 'captcha'],
        ]);
    }

    public function attributeLabels()
    {
        return array_merge(parent::attributeLabels(), [
            'username' => t('Email'),
            'password' => t('Password'),
            'cfmPassword' => t('Confirm password'),
            'captcha' => t('Captcha'),
        ]);
    }

    protected function beforeLogin()
    {
        if (!$this->username) {
            $this->addError('username', t('Please input an email.'));
        }
        if (!$this->password) {
            $this->addError('password', t('Please input password.'));
        }
        if ($this->hasErrors()) {
            return false;
        }
        $identity = $this->getIdentity();
        if (!$identity || !$identity->validatePassword($this->password)) {
            $this->addError('password', t('Wrong email or password.'));
            return false;
        } else {
            return true;
        }
    }
}
