<?php

namespace es\controllers;

use common\classes\Faker;
use Yii;

class InsertController extends \es\components\Controller
{
    public $layout = 'main';

    public function init()
    {
        parent::init();

        $this->view->title = '插入 - ' . $this->view->title;
    }

    public function actionIndex()
    {
        $list = [
            'user' => '插入用户',
        ];
        return $this->render('index', compact('list'));
    }

    public function actionUser()
    {
        $faker = new Faker;
        $params = [
            'index' => 'faker',
            'body'  => [
                'chinese name' => $faker->getName(),
                'english name' => $faker->getEname(),
                'age' => mt_rand(10, 80),
                'email' => $faker->getEmail(),
                'mobile' => $faker->getMobile()
            ]
        ];
        $response = $this->client->index($params);
        test($response);
    }
}
