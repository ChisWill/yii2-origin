<?php

namespace oa\controllers;

use Yii;
use oa\models\AdminUser;
use admin\models\LoginForm;

/**
 * @author ChisWill
 */
class SiteController extends \oa\components\Controller
{
    public function actionIndex()
    {
        $this->layout = 'main';

        $this->view->title = 'OA系统';

        return $this->render('index');
    }

    public function actionProfile()
    {
        return $this->render('profile');
    }

    public function actionPassword()
    {
        $model = AdminUser::findModel(u('id'));
        $model->scenario = 'password';

        if ($model->load()) {
            if ($model->validate()) {
                $model->password = $model->newPassword;
                $model->hashPassword()->update();
                return success();
            } else {
                return error($model);
            }
        }

        return $this->renderPartial('password', compact('model'));
    }

    public function actionWelcome()
    {
        return $this->render('welcome');
    }

    public function actionLogin()
    {
        $this->view->title = '登录';

        $model = new LoginForm;

        if ($model->load()) {
            if ($model->login()) {
                session('requireCaptcha', false);
                return $this->redirect(['index']);
            } else {
                session('requireCaptcha', true);
                return error($model);
            }
        }

        return $this->render('login', compact('model'));
    }

    public function actionLogout()
    {
        user()->logout(false);

        return $this->redirect(['login']);
    }
}
