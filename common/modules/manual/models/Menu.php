<?php

namespace common\modules\manual\models;

use Yii;
use common\helpers\Html;

/**
 * 这是表 `menu` 的模型
 */
class Menu extends \common\models\AbstractMenu
{
    public static function tableName()
    {
        return '{{%manual_menu}}';
    }

    public function getArticle()
    {
        return $this->hasOne(Article::className(), ['menu_id' => 'id'])->select(['id', 'menu_id', 'content']);
    }

    /**
     * @see common\models\Menu::createMenu()
     */
    public function createMenu()
    {
        if (parent::createMenu()) {
            (new Version)->trigger(Version::EVENT_CREATE_VERSION, self::getEvent(['menuId' => $this->id, 'action' => Version::ACTION_CREATE]));

            return true;
        } else {
            return false;
        }
    }

    /**
     * @see common\models\Menu::deleteMenu()
     */
    public function deleteMenu()
    {
        if (parent::deleteMenu()) {
            // 删除所有菜单下的文章
            Article::deleteAll('menu_id NOT IN (SELECT id FROM {{%manual_menu}})');
            Collection::deleteAll('menu_id NOT IN (SELECT id FROM {{%manual_menu}})');
            // 触发记录日志的事件
            (new Version)->trigger(Version::EVENT_CREATE_VERSION, self::getEvent(['menuId' => $this->id, 'content' => $this->article['content'], 'action' => Version::ACTION_DELETE]));
            
            return true;
        } else {
            return false;
        }
    }

    /**
     * @see common\models\Menu::getMenuData()
     */
    public static function getMenuData()
    {
        return parent::getMenuData();
    }

    /**
     * 获取手册的菜单HTML代码
     * 
     * @param  array   $data 分类数据
     * @param  integer $pid  父ID
     * @return string        菜单的HTML代码
     */
    public static function getManualMenu($data, $pid = 0)
    {
        $html = '<ul class="js-tree-container">';
        foreach ($data as $key => $menu) {
            if ($menu['pid'] === $pid) {
                $html .= $menu->getItem($data);
                unset($data[$key]);
            }
        }
        $html .= '</ul>';
        return $html;
    }

    /**
     * 获取菜单的单个元素
     * 
     * @param  array  $data 分类数据
     * @return string       菜单单个元素的HTML代码
     */
    public function getItem($data = [])
    {
        $href = $this->url ? self::createUrl([$this->url]) : 'javascript:;';
        $image = $this->child_num ? '<i class="js-tree-dropdown"></i>' : '';
        $html = '<li class="js-tree-item" data-id="' . $this->id . '">
                    <div class="js-tree-hover"></div>
                    <div class="js-tree-anchor">
                        ' . $image . '
                        <a href="' . $href . '" class="js-tree-title">' . Html::encode($this->name) . '</a>
                    </div>';
        $html .= self::getManualMenu($data, $this->id);
        $html .= '</li>';
        return $html;
    }
}
