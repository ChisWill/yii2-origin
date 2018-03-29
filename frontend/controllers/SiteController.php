<?php

namespace frontend\controllers;

use Yii;
use frontend\models\User;
use common\helpers\Third;

class SiteController extends \frontend\components\Controller
{
    public function actionIndex()
    {
        

        return $this->render('index');
    }

    public function actionRegister()
    {
        $this->view->title = '注册';

        $model = new User(['scenario' => 'register']);
        $model->registerMobile = session('registerMobile');

        if ($model->load()) {
            $model->username = $model->mobile;
            if ($model->validate()) {
                $model->hashPassword()->insert(false);
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
}
