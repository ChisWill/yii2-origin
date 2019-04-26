<?php

namespace common\modules\demo\controllers;

use Yii;
use common\helpers\Url; // 这是助手类中的一员，助手类是解决一类问题的工具型方法，和业务本身没有关系，就像 PHP 内置函数一样
use common\helpers\Curl; // 相同类型的类放在一起 
use common\helpers\ArrayHelper;
use common\helpers\StringHelper; // 出于美观度的考虑，类名长的放在后面
use common\modules\demo\models\Test; // 这是模型类中的一员，每个模型根据所属模块，放在各自模块的 models 文件夹下
use common\models\UserCharge;

/**
 * 更多复杂场景的演示
 */
class MoreController extends \common\modules\demo\components\Controller
{
    public $layout = 'main';

    public $title;
    public $hint;
    /**
     * 表单场景的更多演示
     */
    public function actionForm($id = null)
    {
        $this->title = '单表提交与常用表单元素';
        $this->hint = '每种生成表单元素的方法名是国际统一的，需要开发者自行记忆，方可使用自如';

        // admin/table 页面将会以弹窗形式打开本页面，故更换没有头尾内容的布局
        if (get('iframe')) {
            $this->layout = 'empty';
        }

        try {
            // 该方法有三个效果，如果不传或参数值为null，则返回 `new Test`；传入参数，则根据参数条件进行查询，查到则返回结果；否则抛出404异常
            $model = Test::findModel($id);
            // 编辑复选框时，需要准备好数组形式的数据，显示当前已选中的状态
            if ($model->tags) {
                $model->tags = explode(',', $model->tags);
            }
        } catch (\yii\web\NotFoundHttpException $e) { // 此处捕获抛出的404异常
            // 通过如下方式跳转链接设置参数
            return $this->redirect(['more/form', 'error' => 'ID不存在']);
        }
        
        // 加载表单数据，如果返回true，意味着处理表单提交
        if ($model->load()) { // 调用 `load()` 方法后，表单提交的内容会被自动填充
            // 复选框的处理
            if ($model->tags) {
                $model->tags = implode(',', $model->tags);
            }
            // 此处判断只是为了演示没有文件上传的表单时，可以将表单处理简化成如下形式。实际情况只需选择以下一种写法即可
            if (empty($_FILES)) {
                // `save()`方法默认会验证表单，如果不通过返回false
                if ($model->save()) { // 特别指出，表中的`created_at`、`created_by`、`updated_at`、`updated_by`，这些字段如果存在，会被自动赋值
                    return success(); // ajax请求时， `success()`方法表示成功的返回，最多可以设置 2 个参数传递回前端
                } else {
                    return error($model); // 表单提交失败，返回失败原因
                }
            } else {
                // `validate()` 方法验证表单，验证规则写在模型中，具体参阅 Yii2 的表单验证文档
                if ($model->validate()) {
                    if ($model->file->move()) { // 保存上传文件
                        $model->face = $model->file->filePath; // 获取上传文件的路径，并保存到字段中
                    }
                    // 当`$model`是通过 new 获得的，则插入新数据；当`$model`是查询获得的，则更新当前记录；
                    $model->save(false); // 另外，`state` 字段往往表示逻辑删除状态，如果存在该字段，则调用 `delete()`方法时，会被系统自动修改
                    return success();
                } else {
                    return error($model);
                }
            }
        }
        // 获取get方式传递的参数，如果获取不到，则返回自定义的值，此处是空字符串
        $error = get('error', '');

        return $this->render('form', compact('model', 'error'));
    }
}
