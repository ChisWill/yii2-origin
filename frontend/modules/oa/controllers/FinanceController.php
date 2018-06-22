<?php

namespace oa\controllers;

use Yii;
use oa\models\OaUser;
use oa\models\OaBonus;
use oa\models\OaProduct;
use oa\models\OaUserContact;
use oa\models\AdminUser;
use oa\models\OaFinance;
use oa\models\OaFinanceCategory;
use common\helpers\Hui;
use common\helpers\Html;
use common\helpers\ArrayHelper;

/**
 * @author ChisWill
 */
class FinanceController extends \oa\components\Controller
{
    /**
     * @authname 资金明细列表
     */
    public function actionAccountList()
    {
        $query = (new OaFinance)->accountListQuery();
        $incomeQuery = clone $query;
        $spendQuery = clone $query;
        $humanQuery = clone $query;
        $shareQuery = clone $query;
        $count = $query->sum('amount') ?: 0;
        $income = $incomeQuery->andWhere(['oaFinance.type' => OaFinance::TYPE_INCOME])->sum('amount') ?: 0;
        $spend = $spendQuery->andWhere(['oaFinance.type' => OaFinance::TYPE_SPEND])->sum('amount') ?: 0;
        $human = $humanQuery->andWhere(['oaFinance.category_id' => OaFinance::CATEGORY_HUMAN])->sum('amount') ?: 0;
        $share = $shareQuery->andWhere(['oaFinance.category_id' => OaFinance::CATEGORY_SHARE])->sum('amount') ?: 0;

        $html = $query->getTable([
            'created_at' => ['width' => '100px', 'value' => function ($row) {
                return substr($row->created_at, 0, 10);
            }],
            'type' => ['width' => '80px'],
            'amount' => function ($row) {
                if ($row->type == OaFinance::TYPE_SPEND) {
                    return Html::errorSpan($row->amount);
                } else {
                    return Html::successSpan($row->amount);
                }
            },
            'remark' => ['type' => 'text'],
            'category_id' => ['type' => 'select', 'header' => '分类'],
            'adminUser.realname' => '录入人'
        ], [
            'paging' => 15,
            'addBtn' => ['addIncome' => '收入', 'addSpend' => function () {
                return Hui::dangerBtn('支出', ['addSpend'], ['class' => 'view-fancybox fancybox.iframe']);
            }],
            'ajaxReturn' => [
                'count' => $count,
                'income' => $income,
                'spend' => $spend,
                'human' => $human,
                'share' => $share
            ],
            'searchColumns' => [
                'month' => ['header' => '月份'],
                'remark',
                'type' => 'select',
                'category.name' => ['header' => '分类'],
                'date' => 'dateRange'
            ]
        ]);

        return $this->render('accountList', compact('html', 'count', 'income', 'spend', 'human', 'share'));
    }

    /**
     * @authname 录入收入明细
     */
    public function actionAddIncome()
    {
        $type = OaFinance::TYPE_INCOME;

        return $this->add($type);
    }

    /**
     * @authname 录入支出明细
     */
    public function actionAddSpend()
    {
        $type = OaFinance::TYPE_SPEND;

        return $this->add($type);
    }

    public function add($type)
    {
        $model = new OaFinance;
        $items = OaFinanceCategory::find()->where(['type' => $type])->map('id', 'name');
        $model->created_at = self::$date;

        if ($model->load()) {
            $model->type = $type;
            if ($model->validate()) {
                if ($type == OaFinance::TYPE_SPEND) {
                    $model->amount *= -1;
                }
                $model->updated_at = $model->created_at;
                $model->insert(false);
                return success();
            } else {
                return error($model);
            }
        }

        return $this->render('add', compact('model', 'type', 'items'));
    }

    /**
     * @authname 类别管理
     */
    public function actionCategoryList()
    {
        $query = (new OaFinanceCategory)->search();

        $html = $query->getTable([
            'id',
            'name' => ['type' => 'text'],
            'type' => ['type' => 'select']
        ], [
            'paging' => false,
            'addBtn' => ['addCategory' => '添加分类'],
            'searchColumns' => [
                'name',
                'type' => 'select'
            ]
        ]);

        return $this->render('categoryList', compact('html'));
    }

    /**
     * @authname 添加分类
     */
    public function actionAddCategory()
    {
        $model = new OaFinanceCategory;
        $model->type = OaFinance::TYPE_INCOME;

        if ($model->load()) {
            if ($model->save()) {
                return success();
            } else {
                return error($model);
            }
        }

        return $this->render('addCategory', compact('model'));
    }
}
