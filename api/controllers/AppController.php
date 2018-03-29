<?php

namespace api\controllers;

use Yii;
use common\helpers\Html;
use common\helpers\FileHelper;
use common\helpers\ArrayHelper;
use api\models\ApiApp;
use api\models\ApiCallRecord;

class AppController extends \api\components\Controller
{
    public function actionCreate()
    {
        $record = new ApiCallRecord;
        $request = Yii::$app->getRequest();
        $method = $request->getMethod() ?: '-';
        $url = $request->getHostInfo() . $request->getUrl();
        $record->method = $method;
        $record->url = $url;
        $record->ip = $request->getUserIP() ?: '-';
        $record->state = ApiCallRecord::STATE_INVALID;

        $model = ApiApp::find()->where(['id' => post('key'), 'key' => get('access-token')])->one();
        $error = ['state' => false, 'result' => null];
        if (!$model) {
            $record->insert();
            return $error;
        } else {
            $record->app_id = $model->id;
            $record->post_data = serialize(post());
            if ($model->state == ApiApp::STATE_VALID) {
                if ($model->ip && $model->ip != $record->ip) {
                    $return = $error;
                } else {
                    $record->state = ApiCallRecord::STATE_VALID;
                    $return = ['state' => true, 'result' => md5(date('CmWdLy'))];
                }
            } else {
                $return = $error;
            }
            $record->insert();
            return $return;
        }
    }
}
