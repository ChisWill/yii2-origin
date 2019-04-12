<?php

namespace oa\models;

use Yii;

/**
 * 这是表 `oa_bonus` 的模型
 */
class OaBonus extends \common\components\ARModel
{
    const TYPE_BONUS = 1;
    const TYPE_PERFORMANCE = 2;

    public function rules()
    {
        return [
            [['user_id', 'score', 'comment'], 'required'],
            [['user_id', 'type', 'created_by'], 'integer'],
            [['score'], 'number'],
            [['comment'], 'default', 'value' => ''],
            [['created_at'], 'safe']
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => '员工',
            'score' => '业绩',
            'comment' => '备注',
            'type' => '积分类型',
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
                'oaBonus.type' => $this->type,
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
            ->andWhere(['type' => self::TYPE_BONUS])
            ->groupBy(self::dbExpression('user_id, DATE_FORMAT(oaBonus.created_at, "%Y-%m")'));
    }

    public function detailQuery()
    {
        return $this->search()
            ->joinWith(['user'])
            ->andWhere(['type' => self::TYPE_BONUS])
            ->andWhere(u()->getIsMaster(function () {
                return u()->power >= 9999;
            }) ?: ['oaBonus.user_id' => u()->id])
            ->andFilterWhere(['>=', 'oaBonus.created_at', $this->start_date])
            ->andFilterWhere(['<', 'oaBonus.created_at', $this->end_date ? date('Y-m-d', strtotime($this->end_date) + 3600 * 24) : null])
            ->active('user.state')
            ->orderBy('oaBonus.id DESC');
    }

    public function performanceQuery()
    {
        
    }

    /****************************** 以下为字段的映射方法和格式化方法 ******************************/
}
