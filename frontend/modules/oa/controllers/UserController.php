<?php

namespace oa\controllers;

use Yii;
use oa\models\OaUser;
use oa\models\OaBonus;
use oa\models\OaProduct;
use oa\models\OaUserContact;
use oa\models\AdminUser;
use common\helpers\Hui;
use common\helpers\Html;
use common\helpers\ArrayHelper;

/**
 * @author ChisWill
 */
class UserController extends \oa\components\Controller
{
    /**
     * @authname 客户列表
     */
    public function actionList()
    {
        $query = (new OaUser)->listQuery();
        $html = $query->getTable([
            'id' => ['width' => '35px'],
            'name' => ['width' => '50px'],
            'type' => ['width' => '60px', 'type' => 'select', 'header' => '客户类型'],
            'product_id' => ['width' => '60px', 'type' => 'select', 'header' => '项目类型'],
            'level' => ['width' => '60px', 'type' => 'select', 'header' => '客户等级'],
            'is_chat' => ['width' => '45px', 'type' => 'select'],
            'tel',
            'wechat_id',
            'qq',
            'requirement' => ['width' => '300px'],
            'amount' => ['width' => '60px'],
            // 'contact_time' => ['width' => '130px', 'options' => ['class' => 'text-c', 'style' => ['position' => 'relative']], 'value' => function ($row) {
            //     $tips = $row->isNewTips('contact') ? '<i class="tips"></i>' : '';
            //     return Hui::primaryBtn('查看联系记录', ['recordList', 'id' => $row->id], ['class' => ['info-fancybox fancybox.ajax', 'mt-5', 'mb-5']]) . '<br>' . Html::warningSpan($row->contact_time) . $tips;
            // }],
            'adminUser.realname' => ['width' => '50px', 'header' => '业务员'],
            'source' => ['type' => 'select'],
            'created_at' => ['width' => '130px'],
            ['type' => ['edit' => 'saveUser'], 'width' => '70px', 'value' => function ($row) {
                // return Hui::successBtn('录入联系记录', ['record', 'id' => $row->id], ['class' => 'info-fancybox fancybox.iframe']);
            }]
        ], [
            'addBtn' => ['saveUser' => '添加客户'],
            'export' => '客户关系表',
            'ajaxUpdateAction' => 'ajaxUpdateOaUser',
            'searchColumns' => [
                'name',
                'type' => 'select',
                'product_id' => 'select',
                'level' => 'select',
                'is_chat' => 'select',
                // 'tel',
                'wechat_id',
                // 'qq',
                'source' => 'select',
                'created_by' => ['type' => 'select', 'header' => '业务员']
            ]
        ]);

        return $this->render('list', compact('html'));
    }

    /**
     * @authname 客户产品列表
     */
    public function actionProductList()
    {
        $query = OaProduct::find();
        $html = $query->getTable([
            'id',
            'name' => ['type' => 'text'],
            'desc' => ['type' => 'text'],
            'version' => ['type' => 'text']
        ], [
            'addBtn' => ['addProduct' => '添加新产品'],
            'paging' => false
        ]);

        return $this->render('productList', compact('html'));
    }

    /**
     * @authname 客户统计报表
     */
    public function actionStatisticsList()
    {
        if (!get('search')) {
            $_GET['search']['start_date'] = date('Y-m-d', time() - (date('N') - 1) * 3600 * 24);
            $_GET['search']['end_date'] = date('Y-m-d');
        }
        $result = (new OaUser)->statisticsQuery()->all();
        $data = [];
        foreach ($result as $key => $value) {
            $data[$value['created_by']]['name'] = $value['adminUser']['realname'];
            $data[$value['created_by']] = !empty($data[$value['created_by']]) ? $data[$value['created_by']] : [];
            foreach (['type', 'level'] as $v) {
                $data[$value['created_by']][$v . $value[$v]] = ArrayHelper::getValue($data[$value['created_by']], $v . $value[$v], 0) + 1;
            }
        }

        $result = ArrayHelper::map(self::dbQuery()
            ->from(['sub' => (new OaBonus)
                ->listQuery()
                ->andWhere(['user_id' => array_keys($data)])])
            ->select(['user_id', 'SUM(score) AS score'])
            ->groupBy('user_id')
            ->all(), 'user_id', 'score');
        foreach ($data as $uid => $item) {
            $data[$uid]['score'] = ArrayHelper::getValue($result, $uid, '0.00');
        }

        $html = self::getTable($data, [
            'name' => '名字',
            'score' => '业绩',
            ['header' => OaUser::getTypeMap()[1], 'value' => function ($row) {
                return ArrayHelper::getValue($row, 'type1', 0);
            }],
            ['header' => OaUser::getTypeMap()[2], 'value' => function ($row) {
                return ArrayHelper::getValue($row, 'type2', 0);
            }],
            ['header' => OaUser::getTypeMap()[3], 'value' => function ($row) {
                return ArrayHelper::getValue($row, 'type3', 0);
            }],
            ['header' => OaUser::getTypeMap()[4], 'value' => function ($row) {
                return ArrayHelper::getValue($row, 'type4', 0);
            }],
            ['header' => OaUser::getLevelMap()[1], 'value' => function ($row) {
                return ArrayHelper::getValue($row, 'level1', 0);
            }],
            ['header' => OaUser::getLevelMap()[2], 'value' => function ($row) {
                return ArrayHelper::getValue($row, 'level2', 0);
            }],
            ['header' => OaUser::getLevelMap()[3], 'value' => function ($row) {
                return ArrayHelper::getValue($row, 'level3', 0);
            }],
            ['header' => OaUser::getLevelMap()[4], 'value' => function ($row) {
                return ArrayHelper::getValue($row, 'level4', 0);
            }],
            ['header' => OaUser::getLevelMap()[5], 'value' => function ($row) {
                return ArrayHelper::getValue($row, 'level5', 0);
            }],
        ], [
            'isAjax' => false,
            'isSort' => false,
            'searchColumns' => [
                'created_by' => ['type' => 'select', 'header' => '名字', 'items' => OaUser::getCreatedByMap('选择业务员')],
                'date' => ['type' => 'dateRange']
            ],
            'ajaxReturn' => [
                'jsonData' => json_encode($data)
            ]
        ]);

        $result = OaUser::find()->select(['oaUser.id', 'product_id', 'is_chat', 'level'])->joinWith('product')->asArray()->all();
        $products = [];
        foreach ($result as $key => $value) {
            if ($value['level'] >= OaUser::LEVEL_DEAL) {
                $products[1][$value['product_id']]['product_id'] = $value['product_id'];
                $products[1][$value['product_id']]['name'] = $value['product']['name'];
                $products[1][$value['product_id']]['amount'] = ArrayHelper::getValue($products[1][$value['product_id']], 'amount', 0) + 1;
            }
            $products[0][$value['product_id']]['product_id'] = $value['product_id'];
            $products[0][$value['product_id']]['name'] = $value['product']['name'];
            $products[0][$value['product_id']]['amount'] = ArrayHelper::getValue($products[0][$value['product_id']], 'amount', 0) + 1;
        }
        return $this->render('statisticsList', compact('html', 'data', 'products'));
    }

    /**
     * @authname 添加客户产品
     */
    public function actionAddProduct($id = null)
    {
        $model = OaProduct::findModel($id);

        if ($model->load()) {
            if ($model->save()) {
                return success();
            } else {
                return error($model);
            }
        }
        return $this->render('addProduct', compact('model'));
    }

    /**
     * @authname 添加客户
     */
    public function actionSaveUser($id = null)
    {
        $model = OaUser::findModel($id);
        if ($model->load()) {
            $model->contact_time = $model->contact_time ?: self::$time;
            if ($model->save()) {
                $model->notify(u()->realname . '更新了客户"' . $model->name . '"的信息');
                return success();
            } else {
                return error($model);
            }
        }
        return $this->render('saveUser', compact('model'));
    }

    /**
     * @authname 录入联系记录
     */
    public function actionRecord($id)
    {
        $user = OaUser::findModel($id);
        $list = OaUserContact::find()->with(['adminUser'])->where(['user_id' => $id])->orderBy('id DESC')->all();
        $model = new OaUserContact;
        $model->user_id = $id;
        if ($model->load()) {
            if ($model->save()) {
                $user->contact_time = self::$time;
                $user->update();
                $uids = $user->notify(u()->realname . '录入了客户"' . $user->name . '"的联系记录');
                $user->tips($uids, 'contact');
                return success();
            } else {
                return error($model);
            }
        }
        return $this->render('record', compact('model', 'id', 'user', 'list'));
    }

    /**
     * @authname 查看联系记录
     */
    public function actionRecordList($id)
    {
        $user = OaUser::findModel($id);
        $user->readTips('contact');
        $list = OaUserContact::find()->with(['adminUser'])->where(['user_id' => $id])->orderBy('id DESC')->all();

        return $this->renderPartial('recordList', compact('user', 'list'));
    }

    /**
     * @authname 快捷更新客户信息
     */
    public function actionAjaxUpdateOaUser()
    {
        $response = parent::actionAjaxUpdate();
        if ($response->data['state']) {
            $post = post('params');
            $user = OaUser::findModel($post['key']);
            $user->notify(u()->realname . '更新了客户"' . $user->name . '"的' . $user->label($post['field']));
        }
        return $response;
    }
}
