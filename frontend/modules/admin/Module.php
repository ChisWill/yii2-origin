<?php

namespace admin;

use Yii;
use admin\models\AdminAction;
use common\helpers\Html;
use common\components\ARModel;
use common\components\ARSaveBehavior;

/**
 * 后台模块启动文件
 *
 * @author ChisWill
 */
class Module extends \common\components\Module
{
    public function init()
    {
        parent::init();

        self::moduleInit();
    }

    public static function moduleInit()
    {
        // 为视图层绑定行为
        Yii::$app->view->attachBehavior('viewBehavior', \admin\behaviors\ViewBehavior::className());
        // 改变错误页路由
        Yii::$app->errorHandler->errorAction = ROUTE_BACKEND . '/site/error';
        // 修改错误日志路径
        Yii::$app->log->targets['system']->logFile = Yii::getAlias('@runtime/logs/admin.log');
        // 修改用户组件配置
        Yii::$app->user->idParam = '__admin';
        Yii::$app->user->identityCookie = ['name' => '_identityAdmin', 'httpOnly' => true];
        Yii::$app->user->enableAutoLogin = true;
        Yii::$app->user->enableSession = true;
        Yii::$app->user->loginUrl = [ROUTE_BACKEND . '/site/login'];
        Yii::$app->user->identityClass = 'admin\components\AdminWebUser';
        // 定制表格样式
        Yii::$container->set('common\widgets\Table', [
            'deleteAllBtn' => true,
            'ajaxLayout' => "{summary}\n<div class=\"table-container\">{items}</div>\n{paging}",
            'summaryOptions' => ['class' => 'summary cl pd-5 mt-10'],
            'tableOptions' => ['class' => 'table table-border table-bordered table-bg table-hover'],
            'beforeSearchRow' => function ($option, $index) {
                $option['type'] = isset($option['type']) ? $option['type'] : 'text';
                $option['options'] = isset($option['options']) ? $option['options'] : [];
                switch ($option['type']) {
                    case 'text':
                    case 'date':
                    case 'datetime':
                        $option['options']['class'] = isset($option['options']['class']) ? $option['options']['class'] : 'input-text';
                        break;
                    case 'dateRange':
                        $option['options']['style'] = 'width: 45%';
                        $option['options']['class'] = isset($option['options']['class']) ? $option['options']['class'] : 'input-text';
                        break;
                    case 'timeRange':
                        $option['options']['style'] = 'width: 45%';
                        $option['options']['class'] = isset($option['options']['class']) ? $option['options']['class'] : 'input-text';
                        break;
                    case 'select':
                        $option['options']['class'] = isset($option['options']['class']) ? $option['options']['class'] : 'select';
                        break;
                    default:
                        break;
                }
                return $option;
            }
        ]);
        // 定制表单样式
        Yii::$container->set('common\widgets\ActiveForm', [
            'submitRowOptions' => ['class' => 'row cl text-c']
        ]);
        Yii::$container->set('common\widgets\ActiveField', [
            'showLabel' => true,
            'options' => ['class' => 'row cl'],
            'template' => "<div class='formControls col-sm-9'>{input}</div>\n{hint}\n{error}",
            'labelOptions' => ['class' => 'form-label col-sm-2'],
            'inputOptions' => ['class' => 'input-text']
        ]);
        // 事件绑定
        $event = self::getEvent();
        // AR-Insert-Record
        $event::on(ARModel::className(), ARModel::EVENT_AFTER_INSERT, function ($event) {
            $model = $event->sender;
            $systemAttributes = [ATTR_CREATED_AT, ATTR_CREATED_BY, ATTR_UPDATED_AT, ATTR_UPDATED_BY];
            $fieldMap = [];
            foreach ($model->attributes as $attribute => $value) {
                if ($value !== null && !in_array($attribute, $systemAttributes)) {
                    $fieldMap[] = $attribute;
                }
            }
            AdminAction::add(
                AdminAction::TYPE_INSERT,
                $model::rawTableName(),
                implode(',', $model->getPrimaryKey(true)),
                implode(',', $fieldMap)
            );
        });
        // AR-Update-Record
        $event::on(ARModel::className(), ARModel::EVENT_AFTER_UPDATE, function ($event) {
            $model = $event->sender;
            $systemAttributes = [ATTR_CREATED_AT, ATTR_CREATED_BY, ATTR_UPDATED_AT, ATTR_UPDATED_BY];
            $changedAttributes = array_diff_key($event->changedAttributes, array_flip($systemAttributes));
            $fieldMap = $valueMap = [];
            foreach ($changedAttributes as $attribute => $oldValue) {
                $newValue = $model->$attribute;
                $fieldMap[] = $attribute;
                $valueMap[] = [$oldValue, $newValue];
            }
            if (!$fieldMap || !$valueMap) {
                return;
            }
            AdminAction::add(
                AdminAction::TYPE_UPDATE,
                $model::rawTableName(),
                implode(',', $model->getPrimaryKey(true)),
                [implode(',', $fieldMap) => serialize($valueMap)]
            );
        });
        // AR-Delete-Record
        $event::on(ARModel::className(), ARModel::EVENT_AFTER_DELETE, function ($event) {
            $model = $event->sender;
            AdminAction::add(
                AdminAction::TYPE_DELETE,
                $model::rawTableName(),
                implode(',', $model->getPrimaryKey(true))
            );
        });
    }
}
