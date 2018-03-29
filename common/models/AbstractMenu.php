<?php

namespace common\models;

use Yii;

/**
 * 菜单表的抽象类
 */
abstract class AbstractMenu extends \common\components\ARModel
{
    const IS_SHOW_YES = 1;
    const IS_SHOW_NO = -1;

    /****************************** 以下为公共操作的方法 ******************************/

    /**
     * 创建菜单，使用该方法前确保已经对 `name` 和 `pid` 进行赋值
     *
     * @return boolean
     */
    public function createMenu()
    {
        if ($this->pid !== '0') {
            $parentMenu = self::findOne($this->pid);
            $this->level = $parentMenu->level + 1;
        } else {
            $this->level = 1;
        }

        $count = self::find()->where('level = :level AND pid = :pid', [':level' => $this->level, ':pid' => $this->pid])->count();
        if (empty($parentMenu)) {
            $this->code = $count + 1 . '';
        } else {
            $this->code = $parentMenu->code . '-' . ($count + 1);
        }

        if ($this->save()) {
            if ($this->pid !== '0') {
                $parentMenu->updateCounters(['child_num' => 1]);
            }
            $this->sort = $this->id;
            $this->update();

            return true;
        } else {
            return false;
        }
    }

    /**
     * 删除当前菜单，以及其子类
     * 
     * @return boolean
     */
    public function deleteMenu()
    {
        if ($this->delete()) {
            $parent = static::findOne($this->pid);
            if ($parent) {
                $parent->updateCounters(['child_num' => -1]);
            }
            if ($this->code) {
                self::deleteAll('LOCATE("' . $this->code . '", `code`, 1) = 1');
            }
            return true;
        } else {
            return false;
        }
    }

    /**
     * 获取所有菜单
     * 
     * @param  integer $category 分类的ID
     * @return array             分类数据
     */
    public static function getMenuData()
    {
        return static::find()->orderBy('level ASC, sort ASC')->all();
    }

    /****************************** 以下为字段的映射方法和格式化方法 ******************************/

    // Map method of field `is_show`
    public static function getIsShowMap($prepend = false)
    {
        $map = [
            self::IS_SHOW_YES => '显示',
            self::IS_SHOW_NO => '隐藏',
        ];

        return self::resetMap($map, $prepend);
    }

    // Format method of field `is_show`
    public function getIsShowValue($value = null)
    {
        return $this->resetValue($value);
    }
}