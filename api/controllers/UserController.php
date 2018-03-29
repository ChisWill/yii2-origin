<?php

namespace api\controllers;

use Yii;
use common\helpers\Html;
use common\helpers\FileHelper;
use common\helpers\ArrayHelper;
use api\models\User;

class UserController extends \api\components\Controller
{
    public $modelClass = 'api\models\ApiUser';

    public function init()
    {
        parent::init();
    }

    // GET,HEAD users
    public function actionIndex()
    {
        // return 'this is GET, list all data, user-id:' . Yii::$app->user->id;
        empty($_GET['page']) && $_GET['page'] = 10;
        empty($_GET['sort']) && $_GET['sort'] = '';
        $page = (int) $_GET['page'];
        $sort = $_GET['sort'];
        return \api\models\ApiUser::find()->getData($page, $sort);
    }

    // DELETE users/<id>
    public function actionDelete($id)
    {
        return 'this is DELETE,id =' .$id;
    }

    // GET,HEAD users/<id>
    public function actionView($id)
    {
        return 'this is GET, id='.$id;
        return User::findOne($id);
    }

    // POST users
    public function actionCreate()
    {
        $appId = post('app_id');
        return 'this is POST' . serialize($_POST);
    }

    // users/<id>
    public function actionOptions($id = '')
    {
        return 'this is OPTIONS, id:' . $id;
    }

    // PUT,PATCH users/<id>
    public function actionUpdate($id)
    {
        $data = serialize($_FILES);
        return 'this is PUT, id:' . $id . 'data:'.$data;
    }
}
