<?php

namespace admin\controllers;

use Yii;
use admin\models\Article;
use admin\models\ArticleMenu;
use common\helpers\Html;

class ArticleController extends \admin\components\Controller
{
    /**
     * @authname 资讯列表
     */
    public function actionList()
    {
        $query = (new Article)->listQuery();

        $html = $query->getTable([
            'id',
            'cover' => function ($row) {
                return $row->cover ? Html::img($row->cover, ['style' => ['width' => '120px']]) : '无';
            },
            'title' => ['type' => 'text'],
            'menu.name' => ['header' => '所属菜单'],
            ['type' => ['edit' => 'saveArticle', 'delete']]
        ], [
            'addBtn' => ['saveArticle' => '添加文章'],
            'searchColumns' => [
                'id',
                'title',
                'content',
                'menu.pid' => ['type' => 'select', 'header' => '所属分类', 'items' => [Article::className(), 'getMenuIdMap']]
            ]
        ]);

        return $this->render('list', compact('html'));
    }

    /**
     * @authname 添加/编辑文章
     */
    public function actionSaveArticle($id = null)
    {
        $model = Article::findModel($id);
        $model->categoryType = post('categoryType');
        if ($model->load()) {
            if ($model->validate()) {
                if ($model->categoryType == ArticleMenu::CATEGORY_NEWS && $model->file) {
                    $model->file->move();
                    $model->cover = $model->file->filePath;
                }
                $model->save(false);
                return success();
            } else {
                return error($model);
            }
        }

        return $this->render('saveArticle', compact('model'));
    }


    /**
     * @authname 栏目菜单
     */
    public function actionMenu()
    {
        $query = ArticleMenu::find();

        $html = $query->getLinkage([
            'id',
            'name' => ['type' => 'text'],
            u()->isMe ? 'url' : '' => ['type' => 'text'],
            'category' => ['type' => 'select', 'value' => function ($row) {
                return $row->pid ? $row->getCategoryValue() : '';
            }]
        ], [
            'maxLevel' => 2
        ]);

        return $this->render('menu', compact('html'));
    }
}
