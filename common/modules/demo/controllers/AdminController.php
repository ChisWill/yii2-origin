<?php

namespace common\modules\demo\controllers;

use Yii;
use common\helpers\Html;
use common\modules\demo\models\Test;

/**
 * 后台常用功能演示
 *
 * 按业务模块划分控制器是常见的做法，不要都写在一个控制器中
 */
class AdminController extends \common\modules\demo\components\Controller
{
    // 因为当前演示代码是写在模块中，必须显式声明布局文件，否则默认会使用 frontend/views/layouts/main.php
    public $layout = 'main';

    public $title;
    public $hint;

    public function init()
    {
        parent::init();

        $this->view->title = '新手教程 - 后台常用功能';
    }

    /**
     * 数据表格
     */
    public function actionTable()
    {
        $this->title = '表格的常用功能';
        $this->hint = '并非是前台不能用，只是前台一般用不到，而在后台很常用，后台模块位置：frontend/modules/admin';
        // 获取查询对象，具体查询条件参看 Test::listQuery()，非主要条件应放在调用处
        $query = (new Test)->listQuery()->orderBy('id DESC');
        // 此处演示一个反面典型，如果没有在模型中定义字段的 map 方法，则需要复制代码到每个需要用到的地方，所以这是极为不好的做法
        $tags = ['1' => '谦虚', '2' => '勤奋', '3' => '包容'];
        $html = $query->getTable([
            'id', // 直接填写字段名
            'reg_date' => '注册日期', // 配置列标题
            'face' => function ($row) { // 自定义单元格输出
                return Html::img($row->face);
            },
            // 这是一个完整的格式，包含了所有常用配置项
            'tags' => ['header' => '人物标签', 'width' => '100px', 'value' => function ($row) use ($tags) {
                if ($row->tags) {
                    // 这段写作技巧体会下，有没更好的写法？
                    return implode('<br>', array_map(function ($item) use ($tags) {
                        return $tags[$item];
                    }, explode(',', $row->tags)));
                } else {
                    return Html::errorSpan('还没有标签');
                }
            }],
            'name' => ['type' => 'text'], // 点击单元格编辑效果
            'reg_state' => ['type' => 'select'], // 点击下拉编辑，确保模型中已经有相应字段的 map 方法
            'sex' => function ($row) {
                // 状态应该用常量表示
                if ($row->sex == Test::SEX_MAIL) {
                    // warning 和 success 仅表示颜色，这种方法一般用来显示特定状态的配色，具体参看 common\helpers\Html
                    return Html::warningSpan($row->sexValue);
                } else {
                    return Html::successSpan($row->sexValue);
                }
            },
            // 配置操作栏
            ['type' => ['edit' => function ($row, $key) { // 自定义编辑按钮跳转地址，此处复用已有页面，需要提供额外参数，区分是否是被弹窗显示
                return url(['site/form', 'id' => $key, 'iframe' => true]);
            }, 'delete'], 'width' => '150px'] // 删除按钮使用默认地址
        ], [
            // 此处仅做演示，配置 table 标签的属性，适应前端样式；在实际开发中已经完成样式匹配，一般不需要额外配置
            'tableOptions' => ['class' => 'layui-table'],
            'addBtn' => ['site/form?iframe=1' => '创建新纪录'], // 添加新纪录按钮
            // 配置搜索栏，常规搜索条件是内置的，不需要额外编写代码
            'searchColumns' => [
                'name', // 普通输入框搜索
                'reg_date' => 'dateRange', // 时间区间搜索，需要查询条件中添加相应搜索条件
                'reg_state' => ['type' => 'select', 'header' => '当前状态'], // 下拉框
                'tags' => ['type' => 'radio', 'items' => $tags] // 单选框，使用自定义选项
            ]
        ]);

        return $this->render('table', compact('html'));
    }

    /**
     * 层级列表
     */
    public function actionLinkage()
    {
        $this->title = '层级列表的常用功能';
        $this->hint = '使用前确保表中至少已经包含`pid`，`sort`字段，数据类型都是`int`，默认值都为0';
        // 第一个数据需要直接从数据库添加
        $query = Test::find()->where(['is_linkage' => Test::IS_LINKAGE_YES]);
        $html = $query->getLinkage([
            'id', // 必须要有`id`
            'name' => ['type' => 'text'], // 只有设置'type="text"'，在添加新元素时才可以自定义值
            'sex' => ['type' => 'select'],
            'account' => ['type' => 'password'],
            'reg_state' => ['type' => 'toggle']
        ], [
            'maxLevel' => 2, // 最大只有2个层级
            'beforeAdd' => 'beforeAddItem' // 添加新元素时，自定义额外的操作，'beforeAddItem'是`Test`的非静态方法
        ]);

        return $this->render('linkage', compact('html'));
    }
}
