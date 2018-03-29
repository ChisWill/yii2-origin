<?php

namespace frontend\controllers;

use Yii;
use common\helpers\Inflector;
use frontend\models\User;
use frontend\models\Article;
use frontend\models\ArticleMenu;

class ArticleController extends \frontend\components\Controller
{
    public $layout = 'article';
    public $links = [];
    public $url;

    public function init()
    {
        parent::init();
    }

    public function beforeAction($action)
    {
        if (!parent::beforeAction($action)) {
            return false;
        } else {
            return true;
        }
    }

    /**
     * 所有 action 会统一路由到此
     */
    public function actionIndex()
    {
        $this->url = lcfirst(Inflector::id2camel(get('url', 'index')));
        $exceptActions = ['index', 'detail'];

        if (in_array($this->url, $exceptActions)) {
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

        return $this->render('index', compact(''));
    }

    /**
     * 分类信息页
     */
    public function category()
    {
        $id = get('id', 0);
        $url = $this->url;

        $menu = ArticleMenu::findModel(['url' => $url]);
        $this->view->title = $menu->name;
        $subMenus = ArticleMenu::find()->where(['pid' => $menu->id, 'state' => ArticleMenu::STATE_VALID])->orderBy('sort')->all();

        if (!$id && $subMenus) {
            $id = $subMenus[0]['id'];
        }
        if ($id) {
            $subMenu = ArticleMenu::findModel($id);
            // 校验密码是否已经输入
            if (!$subMenu->checkPasswd()) {
                return $this->redirect(['checkPasswd', 'id' => $subMenu->id]);
            }
            list($view, $params) = $subMenu->prepare();
            if (count($params['list']) === 1) {
                return $this->redirect(['detail', 'id' => $params['list'][0]['id']]);
            }
        } else {
            $view = 'list';
            $params['list'] = [];
        }
        $params['menu'] = $menu;
        $params['subMenus'] = $subMenus;

        $this->links[] = ['label' => $menu->name, 'url' => url([$menu->url])];
        if (!empty($subMenu)) {
            $this->links[] = $subMenu->name;
        }

        return $this->render($view, $params);
    }

    /**
     * 文章详情页
     */
    public function detail()
    {
        $article = Article::findModel(get('id', 0));
        $this->view->title = $article->title;
        $subMenu = $article->menu;
        $menu = $subMenu->parent;
        $this->url = $menu->url;
        $subMenus = ArticleMenu::find()->where(['pid' => $subMenu['pid'], 'state' => ArticleMenu::STATE_VALID])->orderBy('sort')->all();

        $this->links[] = ['label' => $menu->name, 'url' => url([$menu->url])];
        $this->links[] = ['label' => $subMenu->name, 'url' => url([$menu->url, 'id' => $subMenu->id])];
        $this->links[] = $article->title;

        return $this->render('detail', compact('menu', 'subMenu', 'subMenus', 'article'));
    }

    /**
     * 搜索
     */
    public function actionSearch($keywords)
    {
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

    public function actionCheckPasswd()
    {
        $id = req('id');
        $this->view->title = '输入密码';
        $subMenu = Menu::findOne($id);
        $menu = $subMenu->parent;
        $this->url = $menu->url;
        $menus = Menu::find()->where(['pid' => $subMenu['pid']])->orderBy('sort')->all();

        if (req()->isPost) {
            $passwd = post('passwd');
            if ($passwd === $subMenu->passwd) {
                $passwdList = session('articlePasswdList') ?: [];
                $passwdList[$subMenu->id] = true;
                session('articlePasswdList', $passwdList, 3600 * 10);
                return $this->goBack();
            } else {
                return error('密码错误');
            }
        }

        return $this->render('checkPasswd', compact('menus', 'menu', 'subMenu'));
    }
}
