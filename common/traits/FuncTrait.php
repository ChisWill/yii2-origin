<?php

namespace common\traits;

use Yii;
use common\helpers\Url;
use common\helpers\Debug;
use common\helpers\Inflector;
use common\helpers\FileHelper;
use common\helpers\ArrayHelper;

/**
 * 一些泛用性的工具方法
 *
 * @author ChisWill
 */
trait FuncTrait
{
    /**
     * @var yii\data\Pagination
     */
    public static $_pager = [];
    /**
     * @var integer paginate() 搜索到的总数
     */
    public static $_totalCount = 0;

    /**
     * 获取本地图片的网络路径
     * 
     * @param  string $path 图片相对路径
     * @return string
     */
    public static function imageUrl($path)
    {
        $url = (THEME_NAME === null ? Yii::getAlias('@web/images/') : Yii::getAlias('@web/themes/' . THEME_NAME . '/images/')) . $path;
        if (config('staticDomain')) {
            return config('staticDomain') . $url;
        } else {
            return $url;
        }
    }

    /**
     * 快速抛出http异常对象
     * 
     * @param  string  $info 异常信息
     * @param  integer $code 状态码
     */
    public static function throwHttpException($info = '', $code = 404)
    {
        if ($info instanceof \Exception) {
            if (YII_DEBUG !== true) {
                $info = $info->getMessage();
            }
        }
        switch ($code) {
            case 403:
                throw new \yii\web\ForbiddenHttpException($info);
            case 404:
                throw new \yii\web\NotFoundHttpException($info);
            case 500:
                throw new \yii\web\ServerErrorHttpException($info);
            default:
                throw new \yii\base\ErrorException($info, $code);
        }
    }

    /**
     * 获取上传组件
     * 
     * @param  array|string $params 配置参数
     * @return object
     */
    public static function getUpload($params = [])
    {
        if (is_string($params)) {
            $uploadName = $params;
            $params = [];
            $params['uploadName'] = $uploadName;
        }

        return new \common\widgets\Upload($params);
    }

    /**
     * 获取事件对象的快捷方法
     * 
     * @param  array  $params 传给执行事件的组件的附加参数
     * @return object         common\components\Event
     */
    public static function getEvent($params = [])
    {
        $params = array_merge($params, [
            'class' => 'common\components\Event'
        ]);

        return Yii::createObject($params);
    }

    /**
     * 获取富文本编辑器
     *
     * @param  object|string $model   yii\db\ActiveRecord 或 html的name属性值
     * @param  string        $field   字段名或输入框中的文本内容
     * @return array         $options  配置选项
     * @return string 编辑器的HTML代码
     */
    public static function getEditor($model, $field = null, $options = [])
    {
        if ($model instanceof \yii\db\ActiveRecord) {
            $default = [
                'model' => $model,
                'attribute' => $field
            ];
        } else {
            $default = [
                'name' => $model,
                'value' => $field
            ];
        }
        $client['clientOptions'] = $options;
        $client = array_merge($default, $client);

        return \common\widgets\UEditor::widget($client);
    }

    /**
     * 获取 markdown 编辑器
     * 
     * @param  object|string $model   yii\db\ActiveRecord 或 html的name属性值
     * @param  string        $field   字段名或输入框中的文本内容
     * @return array         $options 配置选项
     * @return string        编辑器的HTML代码
     */
    public static function getMarkdownEditor($model, $field, $options = [])
    {
        if ($model instanceof \yii\db\ActiveRecord) {
            $defaultOptions = [
                'model' => $model,
                'attribute' => $field
            ];
        } else {
            $defaultOptions = [
                'name' => $model,
                'value' => $field
            ];
        }
        $defaultOptions = array_merge($defaultOptions, [
            'markedOptions' => [
            ],
            'leptureOptions' => [
            ]
        ]);
        $options = array_merge($defaultOptions, $options);

        return \ijackua\lepture\Markdowneditor::widget($options);
    }

    /**
     * 解除默认的全局事件绑定
     *
     * @param array $disabled 需要屏蔽的事件，不填表示全部
     */
    public static function offEvent($disabled = [])
    {
        $map = [];
        if (!empty(Yii::$app->modules['wizard'])) {
            $map['wizard'] = [Yii::$app->getView(), \yii\web\View::EVENT_END_BODY, [Yii::$app->modules['wizard'], 'renderWizardBar']];
        }
        if (!empty(Yii::$app->modules['debug'])) {
            $map['debug'] = [Yii::$app->getView(), \yii\web\View::EVENT_END_BODY, [Yii::$app->modules['debug'], 'renderToolbar']];
        }
        if ($disabled) {
            $map = array_intersect_key($map, array_flip($disabled));
        }
        foreach ($map as $key => $value) {
            if (!empty(Yii::$app->modules[$key])) {
                $class = $value[0];
                $event = $value[1];
                $method = $value[2];
                $class->off($event, $method);
            }
        }
    }

    /**
     * 日志记录
     * 
     * @param  mixed  $messages 任意消息
     * @param  string $category 日志分类
     * @param  array  $params   日志参数
     */
    public static function log($messages, $category = null, $params = [])
    {
        if ($category === null) {
            Yii::info($messages, 'ChisWill');
        } else {
            $file = $category;
            $category = 'cw-' . $category;
            if (empty(Yii::$app->log->targets[$category])) {
                $targetTemplate = [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['info'],
                    'logVars' => [],
                    'maxFileSize' => 1024 * 2,
                    'maxLogFiles' => 20
                ];
                $target = ArrayHelper::merge($targetTemplate, $params, [
                    'logFile' => '@runtime/logs/' . $file . '.log',
                    'categories' => [$category],
                ]);
                Yii::$app->log->targets[$category] = Yii::createObject($target);
            }
            Yii::info($messages, $category);
        }
    }

    /*
     * 记录sql日志到数据库
     */
    public static function logSql($flag = '')
    {
        if (!config('log_sql_switch')) {
            return;
        }
        $uid = user()->isGuest ? 0 : u()->id;
        $uids = implode(',', explode(PHP_EOL, config('log_sql_uids')));
        if ($uids && strpos(',' . $uids . ',', ',' . $uid . ',') === false) {
            return;
        }

        $list = Debug::sqlList();

        $backtrace = debug_backtrace()[1];
        if (isset($backtrace['class'])) {
            $method = $backtrace['class'] . '::' . $backtrace['function'];
        } else {
            $method = $backtrace['function'];
        }

        $task = new \common\models\LogSqlTask;
        $task->method = ($flag ? $flag . '->' : '') . $method;
        $request = Yii::$app->getRequest();
        $task->url = $request->getHostInfo() . $request->getUrl();
        $task->request = $request->getMethod() ?: '-';
        $task->ip = $request->getUserIP() ?: '-';
        $task->user_id = $uid;
        
        if ($task->insert()) {
            foreach ($list as &$row) {
                array_unshift($row, $task->id);
            }
            self::dbInsert('log_sql_list', ['task_id', 'sql', 'category', 'duration', 'diff', 'time', 'trace'], $list);
        } else {
            l($task->errors, 'logSqlList');
        }
    }

    /**
     * yii\widgets\ActiveForm的简写，并固化了默认参数
     *
     * @param array  $options <form>标签的html属性
     */
    public static function beginForm($options = [])
    {
        if (isset($options['showLabel'])) {
            $fieldConfig = ['showLabel' => $options['showLabel']];
        } else {
            $fieldConfig = [];
        }
        return \common\widgets\ActiveForm::begin([
            'id' => ArrayHelper::getValue($options, 'id', Yii::$app->controller->action->id . 'Form'),
            'action' => ArrayHelper::getValue($options, 'action'),
            'method' => ArrayHelper::getValue($options, 'method', 'post'),
            'options' => $options,
            'enableClientScript' => false,
            'enableClientValidation' => false,
            'enableAjaxValidation' => false,
            'fieldClass' => 'common\widgets\ActiveField',
            'fieldConfig' => $fieldConfig
        ]);
    }

    /**
     * @see yii\widgets\ActiveForm::end()
     */
    public static function endForm()
    {
        \common\widgets\ActiveForm::end();
    }

    /**
     * 基于Yii2的分页栏输出
     *
     * @param  array  需要定制的 yii\widgets\LinkPager 的属性
     * @return string 分页栏的HTML代码
     */
    public static function linkPager($params = [])
    {
        if (!isset($params['pagination'])) {
            $params['pagination'] = array_shift(FuncTrait::$_pager);
        }
        return \yii\widgets\LinkPager::widget($params);
    }

    /**
     * 设置当前分页对象的属性
     */
    public static function setPager($params = [])
    {
        $pager = current(FuncTrait::$_pager);
        foreach ($params as $key => $value) {
            $pager->$key = $value;
        }
    }

    /**
     * 当一个页面存在多个分页列表时候，此方法将会自动设置分页参数的名称
     *
     * @param object $pager 要设置的分页参数类
     * @return yii\widgets\LinkPager
     */
    private static function savePager($pager)
    {
        $pagerCount = count(FuncTrait::$_pager);
        // 检查是否设置了依赖
        $definitions = Yii::$container->getDefinitions();
        $definition = ArrayHelper::filter($definitions, ['eq' => ['class' => 'yii\data\Pagination']]);
        if ($definition) {
            $pageParam = ArrayHelper::getValue(current($definition), 'pageParam');
        } else {
            $pageParam = null;
        }

        if (!$pageParam) {
            if ($pagerCount === 0) {
                $pager->pageParam = 'p';
            } else {
                $pager->pageParam = 'p' . ($pagerCount + 1);
            }
        }

        FuncTrait::$_pager[] = $pager;
        
        return $pager;
    }

    /**
     * common\widgets\Table 组件的快捷调用
     * 
     * @param  array $data    表格输出数据
     * @param  array $columns 列的配置参数
     * @param  array $params  其他杂项配置参数
     * @return string         表格的HTML内容         
     */
    public static function getTable($data = [], $columns = [], $params = [])
    {
        $params = array_merge($params, [
            'data' => $data,
            'columns' => $columns
        ]);

        return \common\widgets\Table::widget($params);
    }

    /**
     * 基于Yii2的分页显示搜索
     * 
     * @param  object|string $query    分页搜索的条件，$query为yii\db\query或sql语句
     * @param  integer       $pageSize 每页显示个数
     * @return array                   当前页的数据
     */
    public static function paginate($query, $pageSize = PAGE_SIZE)
    {
        if ($query instanceof \yii\db\Query) {
            self::$_totalCount = $query->count();
            $pager = self::savePager(Yii::createObject(['class' => 'yii\data\Pagination', 'totalCount' => self::$_totalCount]));
            $pager->defaultPageSize = $pageSize;
            
            if ($query instanceof \yii\db\ActiveQuery && $query->sql !== null) {
                $query->sql .= ' LIMIT :offset, :limit';
                $query->params = [
                    ':offset' => $pager->offset,
                    ':limit' => $pageSize
                ];
                return $query->all();
            } else {
                return $query
                    ->offset($pager->offset)
                    ->limit($pageSize)
                    ->all();
            }
        } elseif (is_string($query)) {
            $countSql = "SELECT COUNT(1) FROM ({$query}) AS sub";

            self::$_totalCount = self::db($countSql)->queryScalar();
            $pager = self::savePager(Yii::createObject(['class' => 'yii\data\Pagination', 'totalCount' => self::$_totalCount]));
            $pager->defaultPageSize = $pageSize;

            $command = self::db($query . ' LIMIT :offset, :limit');
            $command->bindValues([
                ':offset' => $pager->offset,
                ':limit' => $pageSize
            ]);

            return $command->queryAll();
        }
    }
    
    /**
     * Ajax响应，以json格式输出到浏览器端
     *
     * @param  bool|array   $state 消息状态，或自定义的复杂类型数据
     * @param  string|array $info  返回的消息，如果为数组，则会用换行符转换为字符串
     * @param  mixed               任意类型的数据
     * @return object
     */
    public static function ajaxReturn($state, $info = '', $data = null)
    {
        $response = res();

        $response->format = \yii\web\Response::FORMAT_JSON;
        if (is_array($state)) {
            $response->data = $state;
        } else {
            $response->data = [
                'state' => $state,
                'info' => $info,
                'data' => $data
            ];
        }
        $response->send();

        return $response;
    }

    /**
     * @see yii\helpers\BaseUrl::to()
     */
    public static function createUrl($url = '', $scheme = false)
    {
        if (is_string($url) && strpos($url, '@') === false) {
            $url = (array) $url;
        }
        if (is_array($url) && $scheme === false) {
            $url[0] = Inflector::camel2id($url[0]);
        }

        return Url::to($url, $scheme);
    }

    /**
     * @see yii\helpers\BaseUrl::current()
     */
    public static function currentUrl(array $params = [], $scheme = false)
    {
        return Url::current($params, $scheme);
    }

    /**
     * 启用Faker插件
     *
     * 具体用法参看插件中的说明
     */
    public static function createFaker()
    {
        return new \common\classes\Faker;
    }

    /**
     * 字符串对比插件
     * 
     * @param  array $a 旧版的文本，数组的每个元素是待对比的一行文本
     * @param  array $b 新版的文本
     * @return string   标注版本差异的HTML
     */
    public static function createDiff($a, $b, $options = [])
    {
        require_once Yii::getAlias('@vendor/phpspec/php-diff/lib/Diff.php');
        require_once Yii::getAlias('@vendor/phpspec/php-diff/lib/Diff/Renderer/Html/Inline.php');

        $diff = new \Diff($a, $b, $options);
        $renderer = new \Diff_Renderer_Html_Inline;

        return $diff->render($renderer);
    }

    /**
     * PHPExcel插件
     * 
     * @return object
     */
    public static function createExcel()
    {
        require_once Yii::getAlias('@vendor/PHPExcel/Classes/PHPExcel.php');

        return new \PHPExcel();
    }
}
