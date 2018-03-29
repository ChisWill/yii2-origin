<?php

namespace oa\controllers;

use Yii;
use common\helpers\ArrayHelper;
use oa\models\OaMenu;

/**
 * @author ChisWill
 */
class SystemController extends \oa\components\Controller
{
    /**
     * @authname 系统菜单
     */
    public function actionMenu()
    {
        $query = OaMenu::find();

        $html = $query->getLinkage([
            'id',
            'name' => ['type' => 'text'],
            'icon' => ['type' => 'text'],
            'url' => ['type' => 'text'],
            'is_show' => ['type' => 'toggle']
        ], [
            'maxLevel' => 2,
            'beforeAdd' => 'beforeAddMenuItem'
        ]);

        return $this->render('menu', compact('html'));
    }
}
