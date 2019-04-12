<?php

namespace common\modules\demo\models;

use Yii;

class Test extends \common\models\Test
{
    // 定义虚拟字段，除了表中实际不存在外，其他和真实字段的操作方式一样
    public $file;
    public $start_reg_date;
    public $end_reg_date;

    public function rules()
    {
        return array_merge(parent::rules(), [
            [['file'], 'file', 'extensions' => 'jpg,png,gif', 'maxSize' => 2048 * 1000]
        ]);
    }

    public function scenarios()
    {
        return array_merge(parent::scenarios(), [
            // 'scenario' => ['field1', 'field2'],
        ]);
    }

    public function attributeLabels()
    {
        return array_merge(parent::attributeLabels(), [
            // 'field1' => 'description1',
            // 'field2' => 'description2',
        ]);
    }

    public function beforeAddItem()
    {
        $this->is_linkage = self::IS_LINKAGE_YES;
    }

    public function listQuery()
    {
        // `search()` 方法已经添加了所有字段搜索时需要的常规条件，如 =、in 和 like，除此外的条件需要手动添加
        return $this->search() // 往往和 `$query->getTable()` 配合使用
            // 添加时间区间条件，start 和 end 两个变量，系统会自动填充值，表示用户选择的开始和结束时间，第三个参数是要搜索的字段
            ->andDateRange($this->start_reg_date, $this->end_reg_date, 'reg_date')
            // 本质是添加 `state=1` 的添加，但封装成方法调用更能够体现代码逻辑
            ->active()
        ;
    }
}
