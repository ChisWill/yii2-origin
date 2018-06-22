<?php

namespace admin\components;

use Yii;

/**
 * @author ChisWill
 */
class Controller extends \common\components\WebController
{
    public $layout = 'frame';

    public function afterAction($action, $result)
    {
        $result = parent::afterAction($action, $result);

        return $result;
    }

    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => \yii\filters\VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                    'delete-all' => ['post']
                ]
            ],
            'access' => [
                'class' => \yii\filters\AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['error', 'upload', 'captcha', 'ajax-update']
                    ],
                    [
                        'allow' => true,
                        'matchCallback' => function ($rule, $action) {
                            if ($action->controller->id === 'site') {
                                return true;
                            }
                            $actionName = $action->controller->id . '/' . lcfirst(str_replace('action', '', $action->actionMethod));
                            return u()->can($actionName);
                        }
                    ],
                ],
                'denyCallback' => function ($rule, $action) {
                    if (req()->isAjax) {
                        return error('您没有操作权限~!');
                    } else {
                        throwex('您没有操作权限~!');
                    }
                }
            ],
        ];
    }
}
