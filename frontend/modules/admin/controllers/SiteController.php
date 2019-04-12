<?php

namespace admin\controllers;

use Yii;
use admin\models\AdminUser;
use admin\models\LoginForm;

/**
 * @author ChisWill
 */
class SiteController extends \admin\components\Controller
{
    public function actionIndex()
    {
        $this->layout = 'main';

        $this->view->title = config('web_name') ? config('web_name') . ' - 管理系统' : '';

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
                $model->hashPassword();
                $model->old_pass = $model->password;
                $model->update();
                return success();
            } else {
                return error($model);
            }
        }

        return $this->renderPartial('password', compact('model'));
    }

    public function actionUserInfo()
    {
        $model = AdminUser::findModel(u('id'));

        if ($model->load()) {
            if ($model->validate()) {
                if ($model->save()) {
                    $model->update();
                    return success();
                } else {
                    return error($model);
                }
            } else {
                return error($model);
            }
        }

        return $this->renderPartial('userInfo', compact('model'));
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
                return success(url(['index']));
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
