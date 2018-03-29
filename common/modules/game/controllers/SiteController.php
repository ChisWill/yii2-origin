<?php

namespace common\modules\game\controllers;

use Yii;
use common\modules\manual\models\Menu;
use common\helpers\StringHelper;
use common\helpers\Html;

/**
 * @author ChisWill
 */
class SiteController extends \common\components\WebController
{
    public $layout = 'main';

    public $gameList = [
        'shuhui' => '数回'
    ];

    public function actionIndex()
    {
        $this->view->title = 'Game Center - ChisWill';

        $gameList = $this->gameList;
        
        return $this->render('index', compact('gameList'));
    }

    public function actionShuhui()
    {
        $this->view->title = $this->gameList['shuhui'];

        return $this->render('shuhui');
    }
}
