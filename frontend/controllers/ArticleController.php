<?php

namespace frontend\controllers;

use Yii;
use common\helpers\Inflector;
use common\helpers\ArrayHelper;
use frontend\models\User;
use frontend\models\Form;
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
        $this->url = get('url', 'index');
        $url = lcfirst(Inflector::id2camel($this->url));
        $exceptActions = ['index', 'detail', 'submit', 'search', 'data'];

        if (in_array($url, $exceptActions)) {
            return $this->{$url}();
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
     * 分类页
     */
    public function category()
    {
        $id = get('id') ?: get('aid');
        $url = $this->url;
        $article = Article::findModel($id);
        $self = ArticleMenu::findModel(['url' => $url]);
        $subMenu = $self;
        if ($self->top_id != 0) {//二级菜单
            $menu = $self->top;
        } else {//一级菜单
            $menu = $self;
        }
        if ($id) {
            $this->links[] = ['label' => $subMenu->name, 'url' => url([$menu->url, 'id' => $subMenu->id])];
            $this->links[] = $article->title;
        } else {
            $this->links[] = $subMenu->name;
        }

        $this->view->title = $menu->name;
        $subMenus = ArticleMenu::getSubMenus($menu->id);

        if ($self->template) {
            $articleId = get('aid');
            if ($articleId) {
                $article = Article::findModel($articleId);
                $article->updateCounters(['count' => 1]);
                return $this->renderTemplate($menu->url . '/' . $self->template, compact('self', 'menu', 'subMenus', 'article'));
            } else {
                return $this->renderTemplate($menu->url . '/' . $self->template, compact('self', 'menu', 'subMenus'));
            }
        }
        if (!$id) {
            $id = ArrayHelper::getValue($subMenus, '0.id', 0);
        }
        if ($id) {
            $subMenu = ArticleMenu::findModel($id);
            // 校验密码是否已经输入
            if (!$subMenu->checkPasswd()) {
                return $this->redirect(['checkPasswd', 'id' => $subMenu->id]);
            }
            list($view, $params) = $subMenu->prepare();
            if (count($params['list']) === 1 || $view === 'detail') {
                return $this->redirect(['detail', 'menuId' => $subMenu->id, 'id' => ArrayHelper::getValue($params, 'list.0.id', null)]);
            }
        } else {
            $view = 'list';
            $params['list'] = [];
        }
        $params['menu'] = $menu;
        $params['subMenus'] = $subMenus;

        // if (!empty($subMenu)) {
        //     $this->links[] = ['label' => $menu->name, 'url' => url([$menu->url])];
        //     $this->links[] = $subMenu->name;
        // } else {
        //     $this->links[] = $menu->name;
        // }

        return $this->render($view, $params);
    }

    /**
     * 内容页
     */
    public function detail()
    {
        $id = get('id');
        $menuId = get('mid');
        $article = Article::findModel($id);
        $subMenu = ArticleMenu::findModel($menuId);
        if ($id) {
            $title = $article->title;
        } else {
            $title = $subMenu->name;
        }
        $this->view->title = $title;
        $menu = $subMenu->parent;
        $this->url = $menu->url;
        $subMenus = ArticleMenu::getSubMenus($subMenu['pid']);

        $this->links[] = ['label' => $menu->name, 'url' => url([$menu->url])];
        if ($id) {
            $this->links[] = ['label' => $subMenu->name, 'url' => url([$menu->url, 'id' => $subMenu->id])];
            $this->links[] = $article->title;
        } else {
            $this->links[] = $subMenu->name;
        }

        if ($article->template) {
            $view = 'template/' . $article->template;
        } else {
            $view = 'detail';
        }

        return $this->render($view, compact('menu', 'subMenu', 'subMenus', 'article'));
    }

    /**
     * 表单提交
     */
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

    /**
     * 搜索
     */
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

    public function checkPasswd()
    {
        $id = get('id');
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
