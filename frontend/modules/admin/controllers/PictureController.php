<?php

namespace admin\controllers;

use Yii;
use common\helpers\Html;
use admin\models\Picture;

class PictureController extends \admin\components\Controller
{
    /**
     * @authname 轮播图列表
     */
    public function actionList()
    {
        $query = (new Picture)->search()->orderBy('picture.id DESC');

        $html = $query->getTable([
            'id' => ['search' => true],
            'title',
            ['header' => '预览', 'value' => function ($row) {
                return Html::img($row->path, ['style' => ['width' => '50px']]);
            }],
            'url' => ['search' => true],
            'type' => ['search' => 'radio'],
            ['type' => ['edit' => 'savePicture', 'delete' => 'deletePicture']]
        ], [
            'addBtn' => ['savePicture' => '添加图片'],
        ]);

        return $this->render('list', compact('html'));
    }

    /**
     * @authname 添加/编辑图片
     */
    public function actionSavePicture($id = null)
    {
        $model = Picture::findModel($id);

        if ($model->load()) {
            if ($model->validate()) {
                if ($model->file) {
                    $model->file->move();
                    $model->path = $model->file->filePath;
                }
                $model->save(false);
                return success();
            } else {
                return error($model);
            }
        }

        return $this->render('savePicture', compact('model'));
    }

    /**
     * @authname 删除图片
     */
    public function actionDeletePicture()
    {
        $id = post('id');
        $model = post('model');
        $model = $model::findOne($id);
        @unlink(Yii::getAlias('@webroot' . $model->path));
        return parent::actionDelete();
    }
}
