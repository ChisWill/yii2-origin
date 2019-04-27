<?php

namespace admin\controllers;

use Yii;
use admin\models\Article;
use admin\models\ArticleMenu;
use common\helpers\Hui;
use common\helpers\Html;
use common\helpers\ArrayHelper;
use common\helpers\Security;

class ArticleController extends \admin\components\Controller
{
    /**
     * @authname 资讯列表
     */
    public function actionList()
    {
        $menuQuery = ArticleMenu::find();

        $menuHtml = $menuQuery->getLinkage([
            'name' => ['type' => 'checkbox', 'header' => Html::a('全部分类', null, ['class' => 'categoryItem', 'data' => ['id' => '']]), 'value' => function ($row) {
                return Html::a($row->name, null, ['class' => 'categoryItem', 'data' => ['id' => $row->id]]);
            }],
        ], [
            'layout' => '{items}',
            'enableOperate' => false,
            'dragSort' => false
        ]);

        $articleQuery = (new Article)->listQuery();

        $articleHtml = $articleQuery->getTable([
            'id',
            'menu.name' => ['header' => '分类'],
            'cover' => function ($row) {
                return $row->cover ? Html::img($row->cover, ['style' => ['width' => '120px']]) : '无';
            },
            'title' => ['type' => 'text', 'width' => '220px'],
            'summary' => ['type' => 'text', 'width' => '300px'],
            'template' => ['type' => 'text', 'width' => '120px'],
            ['type' => ['edit' => function ($row) {
                return url(['saveArticle', 'id' => $row->id, 'pid' => $row->menu->id]);
            }, 'delete']]
        ], [
            'addBtn' => ['saveArticle' => '添加文章'],
            'searchColumns' => [
                'id',
                'title',
                'content',
                'menu.id' => ['type' => 'select', 'header' => '所属分类', 'items' => ArrayHelper::map(ArticleMenu::getAllMenuQuery()->getTree()->getItems('name'), 'key', 'text')]
            ]
        ]);

        return $this->render('list', compact('menuHtml', 'articleHtml'));
    }

    /**
     * @authname 添加/编辑文章
     */
    public function actionSaveArticle($id = null)
    {
        $model = Article::findModel($id);
        $model->menu_id = get('pid') ?: null;
        $categorySelect = ArticleMenu::getAllMenuQuery()->getTree(['header' => false, 'optionAttrs' => ['category', 'pid']])->dropDownList('name', $model->menu_id, ['class' => 'input-text', 'id' => 'categorySelect']);

        if ($model->load()) {
            if ($model->validate()) {
                if ($model->file) {
                    $model->file->move();
                    $model->cover = $model->file->filePath;
                }
                $model->save(false);
                return success();
            } else {
                return error($model);
            }
        }

        return $this->render('saveArticle', compact('model', 'categorySelect'));
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
            'template' => ['type' => 'text'],
        ]);

        return $this->render('menu', compact('html'));
    }

    /**
     * @authname 快捷修改
     */
    public function actionAjaxUpdate()
    {
        return parent::actionAjaxUpdate();
    }
}
