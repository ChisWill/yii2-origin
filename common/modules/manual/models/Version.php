<?php

namespace common\modules\manual\models;

use Yii;
use common\helpers\Html;

/**
 * 这是表 `manual_version` 的模型
 */
class Version extends \common\components\ARModel
{
    const EVENT_CREATE_VERSION = 'createVersion';

    const ACTION_CREATE = 1;
    const ACTION_UPDATE = 2;
    const ACTION_REVERT = 3;
    const ACTION_DELETE = 4;

    public function init()
    {
        $this->on(self::EVENT_CREATE_VERSION, [$this, 'createVersion']);
    }

    public static function tableName()
    {
        return '{{%manual_version}}';
    }

    public function rules()
    {
        return [
            [['menu_id'], 'required'],
            [['menu_id', 'state', 'created_by'], 'integer'],
            [['content'], 'string'],
            [['created_at'], 'safe'],
            [['content'], 'default', 'value' => '']
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'menu_id' => '文章ID',
            'content' => '内容',
            'action' => '操作类型：1创建，2更新，3恢复，4删除',
            'state' => 'State',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
        ];
    }

    /****************************** 以下为设置关联模型的方法 ******************************/

    public function getUser()
    {
        return $this->hasOne('common\models\User', ['id' => 'created_by'])->select(['id', 'nickname', 'face', 'username']);
    }

    public function getArticle()
    {
        return $this->hasOne(Menu::className(), ['id' => 'menu_id'])->select(['id', 'name']);
    }

    /****************************** 以下为公共显示条件的方法 ******************************/

    public function search()
    {
        $this->setSearchParams();

        return self::find()
            ->filterWhere([
                'version.id' => $this->id,
                'version.menu_id' => $this->menu_id,
                'version.action' => $this->action,
                'version.state' => $this->state,
                'version.created_by' => $this->created_by,
                ])
            ->andFilterWhere(['like', 'version.content', $this->content])
            ->andFilterWhere(['like', 'version.created_at', $this->created_at])
        ;
    }

    /****************************** 以下为公共操作的方法 ******************************/


    public function createVersion($event)
    {
        $this->menu_id = $event->menuId;
        $this->content = $event->content;
        $this->action = $event->action;
        $this->insert();
    }

    /****************************** 以下为字段的映射方法和格式化方法 ******************************/

    // Map method of field `action`
    public static function getActionMap($prepend = false)
    {
        $map = [
            self::ACTION_CREATE => '创建文章',
            self::ACTION_UPDATE => '更新文章',
            self::ACTION_REVERT => '恢复文章',
            self::ACTION_DELETE => '删除文章',
        ];

        return self::resetMap($map, $prepend);
    }

    // Format method of field `action`
    public function getActionValue($value = null)
    {
        $result = $this->resetValue($value);
        switch ($this->action) {
            case self::ACTION_CREATE:
                return Html::successSpan($result);
            case self::ACTION_UPDATE:
                return Html::finishSpan($result);
            case self::ACTION_REVERT:
                return Html::warningSpan($result);
            case self::ACTION_DELETE:
                return Html::errorSpan($result);
        }
    }
}
