<?php

namespace admin\models;

use Yii;

/**
 * 这是表 `admin_menu` 的模型
 */
class AdminMenu extends \common\models\AbstractMenu
{
    protected static $_categoryMap = null;

    public function rules()
    {
        return [
            [['name'], 'required'],
            [['pid', 'level', 'sort', 'is_show', 'category', 'state', 'created_by', 'updated_by'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['name'], 'string', 'max' => 30],
            [['url', 'icon'], 'string', 'max' => 250],
            ['is_show', 'default', 'value' => self::IS_SHOW_YES]
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => '序号',
            'name' => '菜单名',
            'pid' => '父ID',
            'level' => '层级',
            'sort' => '排序值',
            'url' => '跳转链接',
            'icon' => '图标',
            'is_show' => '是否显示',
            'category' => '菜单分类',
            'state' => '状态',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
            'updated_at' => 'Updated At',
            'updated_by' => 'Updated By',
        ];
    }

    /****************************** 以下为设置关联模型的方法 ******************************/

    // public function getRelation()
    // {
    //     return $this->hasOne(Class::className(), ['foreign_key' => 'primary_key']);
    // }

    /****************************** 以下为公共显示条件的方法 ******************************/

    public function search()
    {
        $this->setSearchParams();

        return self::find()
            ->filterWhere([
                'adminMenu.id' => $this->id,
                'adminMenu.pid' => $this->pid,
                'adminMenu.level' => $this->level,
                'adminMenu.sort' => $this->sort,
                'adminMenu.is_show' => $this->is_show,
                'adminMenu.category' => $this->category,
                'adminMenu.state' => $this->state,
                'adminMenu.created_by' => $this->created_by,
                'adminMenu.updated_by' => $this->updated_by,
            ])
            ->andFilterWhere(['like', 'adminMenu.name', $this->name])
            ->andFilterWhere(['like', 'adminMenu.url', $this->url])
            ->andFilterWhere(['like', 'adminMenu.icon', $this->icon])
            ->andFilterWhere(['like', 'adminMenu.created_at', $this->created_at])
            ->andFilterWhere(['like', 'adminMenu.updated_at', $this->updated_at])
            ->andTableSearch()
        ;
    }

    /****************************** 以下为公共操作的方法 ******************************/

    public static function categoryMap()
    {
        if (self::$_categoryMap === null) {
            self::$_categoryMap = self::find()
                ->andWhere(['pid' => 0, 'state' => self::STATE_VALID])
                ->map('url', 'name');
        }

        return self::$_categoryMap;
    }

    public static function showMenu()
    {
        return self::find()
            ->andWhere(['is_show' => self::IS_SHOW_YES, 'state' => self::STATE_VALID])
            ->orderBy('level, sort')
            ->all();
    }

    public function beforeAddMenuItem()
    {
        $parent = self::findOne($this->pid);
        if ($parent) {
            $this->level = $parent->level + 1;
        }
    }

    /****************************** 以下为字段的映射方法和格式化方法 ******************************/
}
