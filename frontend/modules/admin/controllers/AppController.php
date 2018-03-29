<?php

namespace admin\controllers;

use Yii;
use common\helpers\Html;
use common\helpers\ArrayHelper;
use common\helpers\StringHelper;
use admin\models\ApiApp;
use admin\models\ApiUser;
use admin\models\ApiCallRecord;

class AppController extends \admin\components\Controller
{
    /**
     * @authname 客户列表
     */
    public function actionUserList()
    {
        $query = (new ApiUser)->userListQuery();

        $html = $query->getTable([
            'id',
            'username',
            'password',
            'nickname' => ['type' => 'text'],
            'comment',
            ['type' => ['edit' => 'saveUser']]
        ], [
            'addBtn' => ['saveUser' => '添加客户']
        ]);

        return $this->render('userList', compact('html'));
    }

    /**
     * @authname 添加客户
     */
    public function actionSaveUser($id = null)
    {
        $model = ApiUser::findModel($id);

        if ($model->load()) {
            if ($model->save()) {
                return success();
            } else {
                return error($model);
            }
        }

        return $this->render('saveUser', compact('model'));
    }

    /**
     * @authname 应用列表
     */
    public function actionList()
    {
        $query = (new ApiApp)->listQuery();

        $html = $query->getTable([
            'id',
            'app_name',
            'user.nickname',
            'key',
            'rate_limit' => ['value' => function ($row) {
                if ($row->rate_limit >= 1) {
                    return round($row->rate_limit, 2);
                } else {
                    return round(1 / $row->rate_limit, 2);
                }
            }],
            'rest',
            'total',
            'ip',
            'auth_date',
            'state' => ['type' => 'select'],
            ['type' => ['edit' => 'saveApp', 'delete']]
        ], [
            'addBtn' => ['saveApp' => '添加应用']
        ]);

        return $this->render('list', compact('html'));
    }

    /**
     * @authname 添加/修改应用
     */
    public function actionSaveApp($id = null)
    {
        $model = ApiApp::findModel($id);
        $lastTotal = $model->total;

        if ($model->load()) {
            if ($model->isNewRecord) {
                $model->key = StringHelper::random('32', 'w');
                $model->rest = $model->total;
            }
            if ($model->total != $lastTotal) {
                $model->rest = $model->total;
            }
            if ($model->save()) {
                return success();
            } else {
                return error($model);
            }
        }

        return $this->render('saveApp', compact('model'));
    }

    /**
     * @authname 调用记录
     */
    public function actionCallList()
    {
        $query = (new ApiCallRecord)->callListQuery();

        $html = $query->getTable([
            'id',
            'app.app_name',
            'method',
            'url',
            'ip',
            'post_data',
            'state' => function ($row) {
                if ($row->state == $row::STATE_VALID) {
                    return Html::successSpan('成功');
                } else {
                    return Html::errorSpan('失败');
                }
            },
            'created_at',
        ]);

        return $this->render('callList', compact('html'));
    }
}
