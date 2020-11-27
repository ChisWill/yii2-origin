<?php

namespace es\controllers;

use Yii;

class UpdateController extends \es\components\Controller
{
    public $layout = 'main';

    public function init()
    {
        parent::init();

        $this->view->title = '更新 - ' . $this->view->title;
    }

    public function actionIndex()
    {
        $list = [
            'fielddata' => '创建fielddata',
        ];
        return $this->render('index', compact('list'));
    }

    public function actionFielddata()
    {
        $params = [
            'index' => '',
            'body' => [
                'settings' => [
                    'number_of_shards' => 3,
                    'number_of_replicas' => 2
                ],
                'mappings' => [
                    '_source' => [
                        'enabled' => true
                    ],
                    'properties' => [
                        'email' => [
                            'type' => 'keyword'
                        ],
                        'age' => [
                            'type' => 'integer'
                        ]
                    ]
                ]
            ]
        ];
        $r = $this->client->indices()->create($params);
        $this->response($r);
    }
}
