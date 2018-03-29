<?php

namespace php\controllers;

use Yii;
use php\models\User;

class AccountController extends \php\components\Controller
{
    public function beforeAction($action)
    {
        if (!parent::beforeAction($action)) {
            return false;
        } else {
            if (user()->isGuest) {
                user()->loginRequired();
                return false;
            } else {
                return true;
            }
        }
    }

    public function actionIndex()
    {
        $user = User::findOne(u()->id);

        return $this->render('index', compact('user'));
    }
}
