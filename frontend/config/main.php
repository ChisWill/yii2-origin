<?php
$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);

return [
    'id' => 'frontend',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'frontend\controllers',
    'defaultRoute' => 'site',
    'bootstrap' => ['log'],
    'modules' => [
        ROUTE_OA => 'oa\Module',
        ROUTE_BACKEND => 'admin\Module',
        ROUTE_PHP => 'php\Module'
    ],
    'components' => [
        'user' => [
            'loginUrl' => ['site/login'],
            'identityClass' => 'frontend\components\WebUser'
        ],
        'urlManager' => [
            'rules' => [
                // 企业资讯站路由规则
                'article/generate' => 'article/generate',
                'article/<url:[\w-]*>' => 'site/index',
                // 支付回调路由规则
                '<module:[\w/]*>/notify/<type:[\d]+>' => '<module>/notify'
            ]
        ],
        'log' => [
            'targets' => [
                'system' => [
                    'class' => 'yii\log\FileTarget',
                    'logFile' => '@runtime/logs/frontend.log',
                    'levels' => ['error', 'warning'],
                    'except' => [
                        'yii\web\HttpException:403',
                        'yii\web\HttpException:404'
                    ]
                ]
            ]
        ],
        'errorHandler' => [
            'errorAction' => 'site/error'
        ],
    ],
    'params' => $params
];
