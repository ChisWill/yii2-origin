<?php

namespace common\models;

use Yii;

/**
 * 这是表 `article_menu` 的模型
 */
class ArticleMenu extends AbstractMenu
{
    const CATEGORY_NEWS = 1;
    const CATEGORY_LIST = 2;
    const CATEGORY_CONT = 3;

    public function rules()
    {
        return [
            [['name'], 'required'],
            [['pid', 'level', 'sort', 'child_num', 'is_show', 'category', 'state', 'created_by', 'updated_by'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['name'], 'string', 'max' => 50],
            [['code', 'url'], 'string', 'max' => 250]
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => '菜单名',
            'pid' => 'Pid',
            'level' => '层级',
            'code' => '从属排序值',
            'sort' => '排序值',
            'child_num' => '子集数',
            'url' => '链接',
            'is_show' => '是否显示',
            'category' => '菜单分类',
            'state' => '状态',
            'created_at' => '创建时间',
            'created_by' => '创建人',
            'updated_at' => '修改时间',
            'updated_by' => '修改人',
        ];
    }

    /****************************** 以下为设置关联模型的方法 ******************************/

    public function getParent()
    {
        return $this->hasOne(self::className(), ['id' => 'pid']);
    }

    /****************************** 以下为公共显示条件的方法 ******************************/

    public function search()
    {
        $this->setSearchParams();

        return self::find()
            ->filterWhere([
                'articleMenu.id' => $this->id,
                'articleMenu.pid' => $this->pid,
                'articleMenu.level' => $this->level,
                'articleMenu.sort' => $this->sort,
                'articleMenu.child_num' => $this->child_num,
                'articleMenu.is_show' => $this->is_show,
                'articleMenu.category' => $this->category,
                'articleMenu.state' => $this->state,
                'articleMenu.created_by' => $this->created_by,
                'articleMenu.updated_by' => $this->updated_by,
            ])
            ->andFilterWhere(['like', 'articleMenu.name', $this->name])
            ->andFilterWhere(['like', 'articleMenu.code', $this->code])
            ->andFilterWhere(['like', 'articleMenu.url', $this->url])
            ->andFilterWhere(['like', 'articleMenu.created_at', $this->created_at])
            ->andFilterWhere(['like', 'articleMenu.updated_at', $this->updated_at])
            ->andTableSearch()
        ;
    }

    /****************************** 以下为公共操作的方法 ******************************/

    public function checkPasswd()
    {
        if ($this->passwd === '') {
            return true;
        }
        $passwdList = session('articlePasswdList') ?: [];

        user()->setReturnUrl(url());

        return isset($passwdList[$this->id]);
    }

    public function prepare()
    {
        switch ($this->category) {
            case self::CATEGORY_NEWS:
                $view = 'news';
                $page = 10;
                break;
            case self::CATEGORY_LIST:
                $view = 'list';
                $page = 10;
                break;
            case self::CATEGORY_CONT:
                $view = 'detail';
                $page = 1;
                break;
            default:
                $view = 'list';
                $page = 10;
                break;
        }
        $subMenu = $this;
        $list = Article::find()->where(['menu_id' => $subMenu->id, 'state' => Article::STATE_VALID])->paginate($page);

        return [$view, compact('list', 'subMenu')];
    }

    /****************************** 以下为字段的映射方法和格式化方法 ******************************/

    // Map method of field `category`
    public static function getCategoryMap($prepend = false)
    {
        $map = [
            self::CATEGORY_NEWS => '新闻',
            self::CATEGORY_LIST => '列表',
            self::CATEGORY_CONT => '内容'
        ];

        return self::resetMap($map, $prepend);
    }

    // Format method of field `category`
    public function getCategoryValue($value = null)
    {
        return $this->resetValue($value);
    }
}