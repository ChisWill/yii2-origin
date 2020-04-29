<?php

namespace admin\controllers;

use Yii;
use common\helpers\Hui;
use common\helpers\Html;
use common\helpers\Third;
use common\helpers\Inflector;
use common\helpers\FileHelper;
use common\helpers\ArrayHelper;
use common\helpers\StringHelper;
use common\modules\setting\models\Setting;
use common\modules\rbac\models\AuthItem;
use common\models\LogSqlTask;
use admin\models\Log;
use admin\models\Trace;
use admin\models\AdminMenu;
use admin\models\AdminAction;

/**
 * @author ChisWill
 */
class SystemController extends \admin\components\Controller
{
    /**
     * @authname 查看系统设置
     */
    public function actionSetting()
    {
        if (req()->isAjax) {
            return $this->ajaxDisplay();
        } else {
            return $this->render('setting', ['settings' => Setting::getWebSettings()]);
        }
    }

    protected function ajaxDisplay()
    {
        $settings = Setting::getWebSettings(true);

        $nowTopId = get('nowTopId');

        return success($this->renderPartial('_setting', compact('settings', 'nowTopId')));
    }

    /**
     * @authname 添加系统设置
     */
    public function actionAddSetting()
    {
        $setting = new Setting(['scenario' => post('addSetting') ? 'addSetting' : Setting::SCENARIO_DEFAULT]);
        $setting->load();
        if ($setting->validate()) {
            $setting->add();
            if (!empty($_POST['refresh'])) {
                return $this->ajaxDisplay();
            } else {
                return success($setting->id);
            }
        } else {
            return error($setting);
        }
    }

    /**
     * @authname 修改系统设置
     */
    public function actionSaveSetting()
    {
        $setting = new Setting;

        if ($setting->save()) {
            return success($setting->uploads);
        } else {
            return error($setting);
        }
    }

    /**
     * @authname 删除系统设置
     */
    public function actionDeleteSetting()
    {
        $setting = new Setting;

        $setting->delete(post('id'));
        if (get('nowTopId')) {
            return $this->ajaxDisplay();
        } else {
            return success();
        }
    }

    /**
     * @authname 更改系统设置属性
     */
    public function actionUpdateSetting()
    {
        $setting = new Setting;

        if ($setting->updateSetting(post('id'), post('field'), post('value'))) {
            return $this->ajaxDisplay();
        } else {
            return error($setting);
        }
    }

    /**
     * @authname 系统菜单
     */
    public function actionMenu()
    {
        $query = AdminMenu::find();

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
