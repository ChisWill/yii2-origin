<?php

namespace common\modules\demo\controllers;

use Yii;
use common\modules\demo\models\User;
use common\models\Trace;
use common\models\Test;
use common\models\UserCharge;
use common\models\UserWithdraw;
use common\helpers\ArrayHelper;

/**
 * 进阶功能演示
 */
class AdvanceController extends \common\modules\demo\components\Controller
{
    public $layout = 'main';

    public $title;
    public $hint;

    public function init()
    {
        parent::init();

        $this->view->title = '新手教程 - 进阶功能拓展';
    }

    /**
     * 表单进阶功能，基本逻辑和单表提交相同，主要差异在需要事务保证数据的一致性，以及需要同时返回多表的错误
     */
    public function actionForm($id = null)
    {
        $this->title = '表单提交进阶版 - 多表的创建与更新';
        $this->hint = '任意多表操作，都可按照此逻辑进行编码';

        try {
            $user = User::findModel($id);
            // 特殊字段的处理
            $user->password = '';
        } catch (\yii\web\NotFoundHttpException $e) {
            return $this->redirect(['site/advanceForm', 'error' => 'ID不存在']);
        }
        // 此例中 userCharge 每次都是插入新数据，所以不获取已有的数据库记录
        $userCharge = new UserCharge;
        // 同时对两个模型执行 `load()`方法
        if ($user->load() && $userCharge->load()) {
            // 个别字段处理，尤其`user_id`，在验证规则中是必填的，故预先设置，等用户创建完后再重新赋值
            $userCharge->user_id = 0;
            $userCharge->trade_no = 'no' . time();
            $userCharge->rest_amount = $userCharge->amount;
            // 分别验证各个模型
            $isValid = $user->validate();
            $isValid = $userCharge->validate() && $isValid;
            if ($isValid) {
                // 开启事务
                $transaction = self::dbTransaction();
                try {
                    // 多个文件上传的处理
                    if ($user->file) {
                        $d = '';
                        foreach ($user->file as $file) {
                            if ($file->move()) {
                                $user->desc .= $d . $file->filePath;
                                $d = ',';
                            }
                        }
                    }
                    $user->account = $user->account + $userCharge->amount;
                    $user->hashPassword(); // 对密码加密
                    $user->save(false);
                    $userCharge->user_id = $user->id; // 设置正确的 `user_id`
                    $userCharge->save(false);
                    // 提交事务
                    $transaction->commit();
                    return success();
                } catch (\Exception $e) {
                    // 失败回滚
                    $transaction->rollBack();
                    // 返回异常信息
                    return error($e->getMessage());
                }
            } else {
                // 返回所有错误信息
                return error(array_merge($user->errors, $userCharge->errors));
            }
        }
        $error = get('error', '');

        return $this->render('form', compact('user', 'userCharge', 'error'));
    }

    /**
     * 表单提交进阶，列表模型提交，更新与创建使用同一页面
     */
    public function actionTabular()
    {
        $this->title = '表单提交进阶 - 列表模型的创建与更新';
        $this->hint = '该示例不仅需要注意后端的写法，还需要前端的JS代码配合';
        // 比如要更新一个商品的多项属性，表结构上，就会有商品表和商品属性表
        $models = Test::find()->where(['pid' => 1])->all(); // 此处省略了查询商品表信息，并假定所有`pid=1`的记录都是同一个商品的属性
        if (req()->isPost) {
            $count = count(post('Test')) - count($models); // 提交表单后，创建额外的空模型，储存页面上新增的项目
            for ($i = 0; $i < $count; $i++) {
                $models[] = new Test;
            }
        } elseif (!$models) { // 只有在不是表单提交的情况下，才需要初始化`$models`
            $models[] = new Test;
        }
        if (Test::loadMultiple($models, post())) { // 同时加载多项数据到不同模型中
            if (Test::validateMultiple($models)) { // 验证各个模型的规则
                foreach ($models as $model) {
                    $model->pid = 1;
                    $model->save(false);
                }
                return success();
            } else {
                $errors = [];
                foreach ($models as $model) {
                    // 合并错误，并保留相同字段的不同错误
                    $errors = ArrayHelper::merge($errors, $model->errors);
                }
                return error($errors);
            }
        }
        $error = get('error', '');

        return $this->render('tabluar', compact('models', 'error'));
    }

    /**
     * 删除列表模型的一项
     */
    public function actionDeleteTabular()
    {
        $model = Test::findModel(post('id'));
        if ($model->delete()) {
            return success();
        } else {
            return error($model);
        }
    }

    /**
     * 查询进阶功能，展示一些更复杂的查询场景
     */
    public function actionQuery()
    {
        $this->title = '查询进阶 - 更复杂的查询场景';
        $this->hint = '复制 Sql 语句到 Navicat 中，美化格式后可以看得更清楚';

        $result = [];
        // 案例一，Trace 的一行记录，表示一次用户访问，以下查询是统计每日访问人数（同一个IP只算一次）
        $subQuery = Trace::find()
            ->select(['count(1) AS count', 'DATE_FORMAT(created_at, "%Y-%m-%d") AS created_at'])
            // 框架内部会自动对表名和字段名加引号，但当你的表达式不希望被框架加引号，则使用`self::dbExpression()`，将会保留你的原始输入
            ->groupBy(self::dbExpression('ip, DATE_FORMAT(created_at, "%Y-%m-%d")'));
        // `self::dbQuery()` 创建一个与模型无关的查询对象
        $query = self::dbQuery()
            ->select(['count(1) AS count', 'created_at'])
            ->from(['sub' => $subQuery]) // from 一个查询对象
            ->groupBy('created_at')
            ->orderBy('created_at ASC')
            ;
        $result['case1'] = $query->rawsql;

        // 案例二，将充值提现记录融合到一起显示
        $withdrawQuery = UserWithdraw::find()
            ->select(['user_id', 'amount', 'created_at', self::dbExpression('"提现" AS type')]); // 对提现记录进行标记
        $unionQuery = UserCharge::find()
            ->select(['user_id', 'amount', 'created_at', self::dbExpression('"充值" AS type')]) // 对充值记录进行标记
            ->union($withdrawQuery) // 联合一个子查询
            ->asArray()
            ;
        // 单纯的合并两个表的内容到一起
        $result['case2'] = $unionQuery->rawsql;
        // 案例三，对案例二的结果分页排序显示
        $query = self::dbQuery()
            ->from(['sub' => $unionQuery])
            ->orderBy('created_at DESC')
            ;
        $result['case3'] = $query->paginate(3);

        return $this->render('query', compact('result'));
    }

    /**
     * 进阶- 上传文件，此示例主要演示更一般的场景下，进行文件和数据的提交
     */
    public function actionUploadFile()
    {
        $this->title = 'AJAX上传文件或表单的使用示例';
        $this->hint = '测试进度条效果，可使用谷歌的 network 选项卡， online 按钮（一般位于第一排的最右面）';

        // 如果前端没使用 Yii2 的表单对象，则此处使用 self::getUpload() 方法接收上传文件
        if (req()->isPost) { // 表单提交必须
            // 测试模型，保存测试数据
            $model = new Test;
            // 匹配上传文件的 name 属性，如果要设置文件保存位置或更多其他配置，则此处必须传入数组格式配置项，具体参考 common\widgets\Upload 类
            $upload = self::getUpload('file');
            // 保存上传的文件到指定位置
            if ($upload->move()) {
                $model->title = post('title');
                // 如果一次提交上传多个文件，此处则是数组
                if (is_array($upload->filePath)) {
                    // 此处仅作演示，实际项目根据实际情况处理
                    $model->name = implode(',', $upload->filePath);
                    $model->message = implode(',', $upload->originName);
                } else {
                    $model->name = $upload->filePath;
                    // 获取上传文件的原始名称
                    $model->message = $upload->originName;
                }
                if ($model->insert()) {
                    return success($model->name);
                } else {
                    // 表单保存失败应该删除上传的文件，此处仅做演示，未考虑删除多个图片的情形
                    @unlink(Yii::getAlias('@webroot' . $model->name));
                    // 返回表单保存失败原因
                    return error($model);
                }
            } else {
                // 返回文件保存失败原因
                return error($upload);
            }
        }

        return $this->render('uploadFile');
    }

    /**
     * 进阶 - 前端使用演示
     */
    public function actionPlugn()
    {
        $this->title = '前端插件 - 各类前端插件功能演示';
        $this->hint = '框架封装了基本用法，更丰富的功能可以通过查询相关插件的官方文档，以配置参数的形式进行调用';

        // 下拉提示框的后端代码示例
        if (get('type') == 'bindHint') {
            $keyword = get('keyword');
            if (!$keyword) {
                return success([]);
            }
            return success(User::find()
                // 后端需要返回`id`，`value`，`html`三个字段，其中`id`表示实际提交的值，`value`表示输入框中显示的值，`html`表示下拉框中显示的值
                ->select(['id', 'username AS value', 'CONCAT(id, "-", username) AS html']) // `value`可省略，则使用`html`的值替代
                ->where(['like', 'username', $keyword])
                ->asArray()
                ->all()
            );
        }

        return $this->render('plugn');
    }
}
