<?php

namespace common\modules\demo\components;

use Yii;

/**
 * demo 控制器的基类
 */
class Controller extends \common\components\WebController
{
    // 定义控制器公共属性
    public $menu = [
        'site/index' => '基本规范',
        'site/form' => '表单基础',
        'site/query' => '查询基础',
        'site/plugn' => '常用前端方法',
        'site/upload-file' => '上传文件', // 对应的是 site 控制器 `actionUploadFile` 方法。
        'site/helper' => '常用助手方法',
        'admin/table' => '后台 - 数据表格',
        'admin/linkage' => '后台 - 层级列表',
        'advance/form' => '进阶 - 多表提交',
        'advance/tabular' => '进阶 - 列表模型',
        'advance/query' => '进阶 - 多表查询',
        'advance/plugn' => '进阶 - 更多前端插件'
    ];

    public function init()
    {
        parent::init();
    }
    
    public function beforeAction($action)
    {
        if (!parent::beforeAction($action)) {
            return false;
        } else {
            return true;
        }
    }
}
