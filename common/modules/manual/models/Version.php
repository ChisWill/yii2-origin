<?php

namespace common\modules\manual\models;

use Yii;
use common\helpers\Html;

/**
 * 这是表 `hsh_manual_version` 的模型
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
        return $this->hasOne('common\models\AdminUser', ['id' => 'created_by'])->select(['id', 'realname']);
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

    public static function getFaceSrc()
    {
        return 'data:image/jpg;base64,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';
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
