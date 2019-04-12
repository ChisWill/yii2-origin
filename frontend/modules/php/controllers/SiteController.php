<?php

namespace php\controllers;

use Yii;
use php\models\User;
use php\models\XR;

class SiteController extends \php\components\Controller
{
    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionXr()
    {
        $data = XR::batchEncode();
    }

    public function actionRegister()
    {
        $model = new User(['scenario' => 'register']);

        if ($model->load()) {
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
}
