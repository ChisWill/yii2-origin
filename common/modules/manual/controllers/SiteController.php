<?php

namespace common\modules\manual\controllers;

use Yii;
use common\modules\manual\models\Menu;
use common\modules\manual\models\Article;
use common\modules\manual\models\Collection;
use common\modules\manual\models\Version;
use common\helpers\StringHelper;
use common\helpers\Html;

/**
 * @author ChisWill
 */
class SiteController extends \common\components\WebController
{
    public $layout = 'main';

    public function actionIndex()
    {
        $this->view->title = '阅读模式 - ChisWill';
        
        return $this->render('index');
    }

    public function actionEdit()
    {
        $this->view->title = '编辑模式 - ChisWill';

        return $this->render('edit');
    }

    public function actionCreateMenu()
    {
        $menu = new Menu;
        $menu->name = post('menuName');
        $menu->pid = post('pid');
        if ($menu->createMenu()) {
            return success($menu->getItem());
        } else {
            return error($menu);
        }
    }

    public function actionEditMenu()
    {
        if (!($menu = Menu::findOne(post('id')))) {
            return error('该菜单不存在~！');
        }
        $menu->name = post('menuName');
        if ($menu->save()) {
            return success(Html::encode($menu->name));
        } else {
            return error($menu);
        }
    }

    public function actionSortMenu()
    {
        $idList = get('idList');

        foreach ($idList as $sort => $id) {
            Menu::updateAll(['sort' => $sort], 'id = :id', [':id' => $id]);
        }

        return success();
    }

    public function actionDeleteMenu()
    {
        if (!($menu = Menu::findOne(post('id')))) {
            return error('该菜单不存在~！');
        }

        if (u('id') != 1) {
            return error('您没有删除权限~！');
        }
        
        if ($menu->deleteMenu()) {
            return success();
        } else {
            return error('删除失败~！');
        }
    }

    public function actionUpdateArticle()
    {
        $menuId = post('menuId');
        if (!($article = Article::find()->where('menu_id = :menu_id', [':menu_id' => $menuId])->one())) {
            $article = new Article;
            $article->menu_id = $menuId;
        }        
        $article->content = post('content');
        if ($article->saveArticle()) {
            return success();
        } else {
            return error($article);
        }
    }

    public function actionViewArticle()
    {
        $menuId = get('menuId');
        $keyword = get('keyword', '');
        $article = Article::find()->where('menu_id = :menu_id', [':menu_id' => $menuId])->one();
        
        $isCollect = Collection::find()->where('menu_id = :menu_id AND user_id = :user_id', [
            ':menu_id' => $menuId,
            ':user_id' => u('id')
        ])->exists();

        if ($article) {
            if ($keyword) {
                // $article->content = StringHelper::highlight($article->content, $keyword);
            }
            return success('获取文章内容成功~！', ['content' => $article->content, 'isCollect' => $isCollect]);
        } else {
            return error('该文章不存在~！', ['isCollect' => $isCollect]);
        }
    }

    public function actionSearch()
    {
        $article = new Article;

        $data = $article->keywordSearch(get('keyword'))->asArray()->all();

        return success($data);
    }

    public function actionCollect()
    {
        $isCancel = post('cancel');
        $menuId = post('menuId');
        if ($isCancel) {
            $collection = Collection::find()->where('menu_id = :menu_id AND user_id = :user_id', [
                ':menu_id' => $menuId,
                ':user_id' => u('id')
            ])->one();

            if ($collection) {
                return success($collection->delete());
            } else {
                return error('取消收藏失败~！');
            }
        } else {
            $collection = new Collection;
            $collection->menu_id = $menuId;
            $collection->user_id = u('id');

            if ($collection->save()) {
                return success();
            } else {
                return error($collection);
            }
        }
    }

    public function actionCollectList()
    {
        $data = Collection::find()->with('menu')->where('user_id = :user_id', [':user_id' => u('id')])->asArray()->all();

        success($data);
    }

    public function actionHistory()
    {
        self::offEvent();

        $menuId = get('id');

        if (!$menuId) {
            throw new \yii\base\InvalidParamException('请先创建一个文章目录，再来查看历史记录~！');
        }

        $history = Version::find()->with(['user', 'article'])->where('menu_id = :menu_id', [':menu_id' => $menuId])->orderBy('id DESC')->all();

        return $this->render('history', compact('history'));
    }

    public function actionUploadImage()
    {
        $upload = self::getUpload('image');

        if ($upload->move()) {
            return success($upload->filePath);
        } else {
            return error($upload);
        }
    }

    public function actionDiff()
    {
        $versionId = post('id');
        $menuId = post('menuId');

        $data = Version::find()->where('menu_id = :menu_id AND id <= :id', [':menu_id' => $menuId, ':id' => $versionId])->orderBy('created_at DESC')->limit(2)->all();

        if (count($data) === 1) {
            $oldContent = '';
        } else {
            $oldContent = $data[1]->content;
        }
        $newContent = $data[0]->content;
        foreach (['old', 'new'] as $v) {
            $var = $v . 'Content';
            if ($$var === '') {
                $$v = [];
            } else {
                $$v = explode("\n", $$var);
            }
        }

        $diff = self::createDiff($old, $new);

        return success($diff);
    }

    public function actionRevert()
    {
        $versionId = post('id');

        $toVersion = Version::findOne($versionId);

        $article = Article::find()->where('menu_id = :menu_id', [':menu_id' => $toVersion->menu_id])->one();
        $article->content = $toVersion->content;
        if ($article->revertArticle($toVersion)) {
            return success();
        } else {
            return error($article);
        }
    }
}
