<?php

namespace frontend\controllers;

use Yii;
use common\helpers\Inflector;
use common\helpers\ArrayHelper;
use frontend\models\User;
use frontend\models\Form;
use frontend\models\Article;
use frontend\models\ArticleMenu;

/**
 * 资讯类站点统一解决方案，涉及的表为`article_menu`和`article`，概念说明：
 * 1. 资讯站本质分为三类页面，分别是首页、分类页和详情页；分别对应`index()`、`category()`和`detail()`方法。
 * 2. 分类页必须在`article_menu`中设置`url`字段才能访问；如果不设置模板，则默认使用`views/article/category.php`作为视图文件。
 * 3. 叶子节点的分类页无法访问，使用`url($parent['url'], 'id' => $child['id'])`构造分类页的访问链接，其中`$parent`是上级分类，`$child`是当前分类
 * 4. 使用`url('detail', 'id' => $article['id'])`构造详情页的访问链接，其中`$article`是要访问的文章
 *
 * 另外，框架目前一共提供4个常用方法：
 * 1. frontend\models\ArticleMenu::getMenus() ：获取顶级栏目列表
 * 2. frontend\models\ArticleMenu::getSubMenus() ：获取指定栏目id或url的子栏目列表
 * 3. frontend\models\Article::getAllArticleQuery() ：获取指定栏目url下的所有文章的查询条件
 * 4. frontend\models\Article::getArticleQuery() ：获取指定栏目id或url下的文章的查询条件
 * 
 * @author ChisWill
 */
class ArticleController extends \frontend\components\Controller
{
    /****************************** 以下是内置属性与方法，无需修改 ******************************/

    public $layout = 'article';
    public $links = [];
    public $url;

    public function init()
    {
        parent::init();

        Yii::$container->set('yii\widgets\Breadcrumbs', [
            'homeLink' => false
        ]);
    }

    /**
     * 所有 action 统一路由到此
     */
    public function actionIndex()
    {
        $this->url = get('url', 'index');

        if (method_exists($this, $this->url)) {
            return $this->{$this->url}();
        } else {
            return $this->category();
        }
    }

    /**
     * 首页
     */
    public function index()
    {
        $this->view->title = '首页';

        return $this->render('index');
    }

    /**
     * 分类页
     */
    public function category()
    {
        $url = $this->url;
        $parent = ArticleMenu::findModel(['url' => $url, 'state' => ArticleMenu::STATE_VALID]);
        $subMenus = ArticleMenu::getSubMenus($parent->id);
        if (get('id')) {
            $child = current(ArrayHelper::filter($subMenus, ['eq' => ['id' => get('id')]]));
        } else {
            $child = ArrayHelper::getValue($subMenus, '0');
        }
        if ($parent->top_id != 0) {
            $this->links[] = ['label' => $parent->top->name, 'url' => url([$parent->top->url])];
        }
        $this->links[] = ['label' => $parent->name, 'url' => url([$parent->url])];
        if ($child) {
            $this->links[] = $child['name'];
        }
        if ($parent->template) {
            return $this->renderTemplate(ArticleMenu::getTopUrl($parent->url) . '/' . $parent->template, compact('parent', 'child', 'subMenus'));
        } else {
            return $this->render('category', compact('parent', 'child', 'subMenus'));
        }
    }

    /**
     * 详情页
     */
    public function detail()
    {
        $id = get('id');
        $article = Article::findModel(['id' => $id, 'state' => Article::STATE_VALID]);
        $child = $article->menu;
        if ($child->top_id == 0) {
            $parent = $child;
        } else {
            $parent = $child->parent;
        }
        $this->url = $parent->url;
        $subMenus = ArticleMenu::getSubMenus($parent->id);

        if ($parent->top_id != 0) {
            $this->links[] = ['label' => $parent->top->name, 'url' => url([$parent->top->url])];
        }
        if ($parent->id != $child->id) {
            $this->links[] = ['label' => $parent->name, 'url' => url([$parent->url])];
            $this->links[] = ['label' => $child->name, 'url' => url([$parent->url, 'id' => $child->id])];
        } else {
            $this->links[] = ['label' => $parent->name, 'url' => url([$parent->url])];
        }
        $this->links[] = $article->title;

        $template = $article->template ?: ($child->template ?: '');
        if ($template) {
            return $this->renderTemplate(ArticleMenu::getTopUrl($parent->url) . '/' . $template, compact('parent', 'child', 'subMenus', 'article'));
        } else {
            return $this->render('detail', compact('parent', 'child', 'subMenus', 'article'));
        }
    }

    /****************************** 以下是自定义区块 ******************************/

    public function submit()
    {
        $model = new Form;

        if ($model->load()) {
            if ($model->add()) {
                return success();
            } else {
                return error($model);
            }
        } else {
            throwex();
        }
    }

    public function search()
    {
        $keywords = get('keywords', '');
        if ($keywords) {
            $article = Article::find()
                ->joinWith(['menu'])
                ->where(['=', 'passwd', ''])
                ->andWhere(['article.state' => Article::STATE_VALID, 'menu.state' => ArticleMenu::STATE_VALID])
                ->andFilterWhere(['or', ['like', 'title', $keywords], ['like', 'content', $keywords]])
                ->one();
        } else {
            $article = null;
        }
        if ($article) {
            $menu = ArticleMenu::findOne($article->menu_id);
            $parent = $menu->parent;
            return $this->redirect([$parent->url, 'mid' => $menu->id]);
        } else {
            // 搜索不到时的处理
            // return $this->actionInfo();
        }
    }

    public function data()
    {
        $data = Article::find()
            ->joinWith(['menu'])
            ->where(['menu.id' => get('id')])
            ->active()
            ->asArray()
            ->all();
            
        return success($data);
    }
}
