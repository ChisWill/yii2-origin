<?php

namespace admin\controllers;

use Yii;
use common\helpers\Hui;
use common\helpers\ArrayHelper;
use admin\models\User;

class UserController extends \admin\components\Controller
{
    /**
     * @authname 会员列表
     */
    public function actionList()
    {
        $query = (new User)->search()->orderBy('user.id DESC');

        $html = $query->getTable([
            'id' => ['search' => true],
            'username' => ['search' => true],
            'mobile' => ['search' => true],
            'nickname' => ['search' => true],
            'created_at',
            'state' => ['search' => 'select']
        ]);

        return $this->render('list', compact('html'));
    }
}
