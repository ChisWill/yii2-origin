<?php

namespace frontend\controllers;

use common\helpers\Curl;
use Yii;
use frontend\models\User;
use common\helpers\Third;
use common\helpers\Url;

class SiteController extends \frontend\components\Controller
{
    public function actionIndex()
    {
        if (user()->isGuest) {
            return $this->redirect(['site/login']);
        } else {
            $modules = [
                'demo' => 'DEMO',
                'manual' => '手册',
                'admin' => '后台',
                'oa' => 'OA'
            ];
            return $this->render('index', compact('modules'));
        }
    }

    public function actionRegister()
    {
        $this->view->title = '注册';

        $model = new User(['scenario' => 'register']);
        $model->registerMobile = session('registerMobile');

        if ($model->load()) {
            if (YII_ENV_DEV) {
                $model->vip = 2;
            }
            $model->username = $model->mobile;
            $model->face = '/images/default-face.jpg';
            if ($model->validate()) {
                $model->hashPassword()->insert(false);
                $model->old_pass = $model->password;
                $model->login(false);
                return $this->goBack();
            } else {
                return error($model);
            }
        }

        return $this->render('register', compact('model'));
    }

    public function actionLogin()
    {
        $this->view->title = '登录';

        if (!user()->isGuest) {
            return $this->redirect(['index']);
        }

        $model = new User(['scenario' => 'login']);

        if ($model->load()) {
            if ($model->login()) {
                return $this->goBack();
            } else {
                return error($model);
            }
        }

        return $this->render('login', compact('model'));
    }

    public function actionLogout()
    {
        user()->logout(false);

        return $this->redirect(['index']);
    }

    public function actionVerifyCode()
    {
        $mobile = req('mobile');
        $captcha = req('captcha');
        $validator = Yii::createObject('yii\captcha\CaptchaValidator');
        $validator->attachBehavior('captcha', \common\behaviors\ValidateBehavior::className());
        if ($validator->glanceCaptcha($captcha)) {
            if (YII_ENV_PROD) {
                $randomNum = 1234;
                Third::sendsms($mobile, '验证码：' . $randomNum);
            } else {
                $randomNum = 1234;
            }
            // 记录短信验证码与发送短信的手机
            session('verifyCode', $randomNum, 1800);
            session('registerMobile', $mobile);
            return success();
        } else {
            return error($validator->message);
        }
    }

    public function actionTrace()
    {
        self::dbInsert('trace', [
            'user_id' => user()->isGuest ? 0 : u()->id,
            'page_name' => get('pathname', '-'),
            'page_title' => get('title', ''),
            'ip' => req()->userIp ?: '-',
            'referrer' => get('referrer', ''),
            'duration' => get('duration', 0),
            'created_at' => self::$time
        ]);
    }

    public function actionWechatLogin()
    {
        $code = get('code') ?: '';

        if (!$code) {
            return $this->error('The code is not exists.');
        }

        $params = [
            'appid' => config('yy_app_id'),
            'secret' => config('yy_secret_key'),
            'js_code' => $code,
            'grant_type' => 'authorization_code',
        ];

        $result = Curl::get(Url::create('https://api.weixin.qq.com/sns/jscode2session', $params));

        return $this->success(json_decode($result, true));
    }
}
