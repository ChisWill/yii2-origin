<?php

namespace admin\controllers;

use Yii;
use common\helpers\Hui;
use common\helpers\Security;
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
            'desc' => ['header' => '备注', 'width' => '150px', 'type' => 'text'],
            'vip' => ['type' => 'text'],
            'created_at',
            'state' => ['search' => 'select'],
            ['type' => ['delete'], 'width' => '120px', 'value' => function ($row) {
                return Hui::secondaryBtn('修改密码', ['editPass', 'id' => $row->id], ['class' => 'editPass']);
            }]
        ]);

        return $this->render('list', compact('html'));
    }

    /**
     * @authname 修改密码
     */
    public function actionEditPass()
    {
        $id = get('id');
        $newpass = post('value');

        $user = User::findModel($id);
        $user->password = Security::generatePasswordHash($newpass);

        if ($user->update()) {
            return success();
        } else {
            return error($user);
        }
    }
}
