<?php

namespace oa\controllers;

use Yii;
use common\helpers\Hui;
use common\helpers\ArrayHelper;
use oa\models\OaMenu;
use oa\models\OaNotice;
use common\models\AdminUser;

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

    /**
     * @authname 公告管理
     */
    public function actionNotice()
    {
        if (req()->isPost) {
            if (!post('content')) {
                return error('请输入群发内容');
            }
            if (!post('position')) {
                return error('至少选择一个群组');
            }
            $uids = AdminUser::find()->where(['position' => post('position')])->andWhere(['<>', 'id', u()->id])->active()->map('id', 'id');
            OaNotice::notify($uids, post('content'));
            return success();
        }

        $query = (new OaNotice)->listQuery();
        $map = AdminUser::getPositionMap();
        $html = $query->getTable([
            'id',
            'title',
            'origin_name',
            'download_num',
            'adminUser.username' => ['header' => '发布人'],
            'created_at' => ['header' => '发布时间'],
            ['type' => ['edit' => 'saveNotice', 'delete'], 'width' => '250px', 'value' => function ($row) {
                $btns[] = Hui::primaryBtn('查看公告', ['lookNotice', 'id' => $row->id], ['class' => 'view-fancybox fancybox.iframe']);
                if ($row->attach) {
                    $btns[] = Hui::successBtn('下载附件', ['downloadAttach', 'id' => $row->id], ['target' => '_blank']);
                }
                return implode('&nbsp;&nbsp;', $btns);
            }],
        ], [
            'addBtn' => ['saveNotice' => '发布公告'],
            'searchColumns' => [
                'id',
                'title',
                'origin_name',
                'created_at' => ['header' => '发布时间', 'type' => 'dateRange'],
            ]
        ]);

        return $this->render('notice', compact('html', 'map'));
    }

    /**
     * @authname 添加/编辑公告
     */
    public function actionSaveNotice($id = null)
    {
        $model = OaNotice::findModel($id);

        if ($model->load()) {
            if ($model->validate()) {
                if ($model->file) {
                    $model->file->move();
                    $model->origin_name = $model->file->name;
                    $model->attach = $model->file->filePath;
                }
                if ($model->isNewRecord) {
                    $content = u()->realname . '发布了新公告：' . $model->title;
                } else {
                    $content = u()->realname . '更新了公告：' . $model->title;;
                }
                $uids = AdminUser::find()->andWhere(['<>', 'id', u()->id])->active()->map('id', 'id');
                OaNotice::notify($uids, $content);
                $model->save(false);
                return success();
            } else {
                return error($model);
            }
        }
        
        return $this->render('saveNotice', compact('model'));
    }

    /**
     * @authname 下载附件
     */
    public function actionDownloadAttach($id)
    {
        $model = OaNotice::findOne($id);

        OaNotice::updateAllCounters(['download_num' => 1], ['id' => $id]);
        $this->download(Yii::getAlias('@webroot') . $model->attach, $model->origin_name);
    }

    /**
     * @authname 查看公告
     */
    public function actionLookNotice($id)
    {
        $model = OaNotice::findOne($id);

        return $this->render('lookNotice', compact('model'));
    }
}
