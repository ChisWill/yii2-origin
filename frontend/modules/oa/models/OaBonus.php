<?php

namespace oa\models;

use Yii;

/**
 * 这是表 `oa_bonus` 的模型
 */
class OaBonus extends \common\components\ARModel
{
    public function rules()
    {
        return [
            [['user_id', 'score', 'comment'], 'required'],
            [['user_id', 'created_by'], 'integer'],
            [['score'], 'number'],
            [['comment'], 'default', 'value' => ''],
            [['created_at'], 'safe']
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => '员工ID',
            'score' => '业绩',
            'comment' => '备注',
            'created_at' => '完成时间',
            'created_by' => '录入人',
        ];
    }

    /****************************** 以下为设置关联模型的方法 ******************************/

    public function getUser()
    {
        return $this->hasOne(AdminUser::className(), ['id' => 'user_id']);
    }

    /****************************** 以下为公共显示条件的方法 ******************************/

    public function search()
    {
        $this->setSearchParams();

        return self::find()
            ->filterWhere([
                'oaBonus.id' => $this->id,
                'oaBonus.user_id' => $this->user_id,
                'oaBonus.score' => $this->score,
                'oaBonus.created_by' => $this->created_by,
            ])
            ->andFilterWhere(['like', 'oaBonus.comment', $this->comment])
            ->andFilterWhere(['like', 'oaBonus.created_at', $this->created_at])
            ->andTableSearch()
        ;
    }

    /****************************** 以下为公共操作的方法 ******************************/

    public function listQuery()
    {
        return $this->detailQuery()
            ->select(['SUM(score) AS score', 'oaBonus.created_at', 'user_id'])
            ->groupBy(self::dbExpression('user_id, DATE_FORMAT(oaBonus.created_at, "%Y-%m")'));
    }

    public function detailQuery()
    {
        return $this->search()
            ->joinWith(['user'])
            ->andWhere(u()->getIsMaster(function () {
                return u()->power >= 9999;
            }) ?: ['oaBonus.user_id' => u()->id])
            ->andFilterWhere(['>=', 'oaBonus.created_at', $this->start_date])
            ->andFilterWhere(['<', 'oaBonus.created_at', $this->end_date ? date('Y-m-d', strtotime($this->end_date) + 3600 * 24) : null])
            ->orderBy('oaBonus.id DESC');
    }

    /****************************** 以下为字段的映射方法和格式化方法 ******************************/

    // Map method of field `user_id`
    public static function getUserIdMap($prepend = false)
    {
        $map = AdminUser::find()->where(['state' => AdminUser::STATE_VALID])->map('id', 'realname');

        return self::resetMap($map, $prepend);
    }

    // Format method of field `user_id`
    public function getUserIdValue($value = null)
    {
        return $this->resetValue($value);
    }
}
