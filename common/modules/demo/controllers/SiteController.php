<?php
// 命名空间往往和实际文件路径一致
namespace common\modules\demo\controllers;

// 声明本文件使用到的类，一般是三种类型：Yii、助手类、模型类，只要当前文件用到，就得在此声明。除此之外的类，可以直接在代码中使用完整类名调用
use Yii;
use common\helpers\Url; // 这是助手类中的一员，助手类是解决一类问题的工具型方法，和业务本身没有关系，就像 PHP 内置函数一样
use common\helpers\Curl; // 相同类型的类放在一起 
use common\helpers\ArrayHelper;
use common\helpers\StringHelper; // 出于美观度的考虑，类名长的放在后面
use common\modules\demo\models\Test; // 这是模型类中的一员，每个模型根据所属模块，放在各自模块的 models 文件夹下
use common\models\UserCharge;

/**
 * 代码演示模块，代码格式与类库使用，都可参阅本模块所示内容
 */
class SiteController extends \common\modules\demo\components\Controller
{
    // 框架定义的属性，表示视图层布局，使用`views/layouts/main.php`
    public $layout = 'main';
    // 这是自定义的公共属性
    public $title;
    public $hint;

    /**
     * 代替PHP原生构造方法，使用时必须先调用`parent::init()`
     */
    public function init()
    {
        parent::init();
        // 这是控制网页的`title`，通过`$this->view`访问视图层
        $this->view->title = '新手教程 - 基础代码示例';
    }

    /**
     * 调用每个具体的action前，会先执行该方法，使用时必须遵从如下逻辑结构
     */
    public function beforeAction($action)
    {
        if (!parent::beforeAction($action)) {
            return false;
        } else {
            // 这里一般写访问的限制条件，比如是否登录，如果未登录，可以重定向到登录页面，并 `return false`
            return true;
        }
    }

    /**
     * 代码基础规范
     */
    public function actionIndex()
    {
        $this->title = '框架简介与代码规范：空格、缩进、换行与命名规则';
        $this->hint = '此章节具体内容请直接参阅本文件源码，位置：common/modules/demo/controllers/SiteController.php';
        // 变量赋值的格式
        $bool = true; // 诸如此类的关键字全部使用小写
        // `if`的格式
        if ($bool === true) {
            // 一维数组定义
            $array = ['a', 'b', 'c']; // 数组使用中括号定义
            // `for`的格式
            for ($i = 0; $i < count($array); $i++) {
                // 重要的事情说三遍
                $importThing = '变量名使用英文，根据意义、用常见词取名；多个单词用驼峰法命名；类名首字母大写。常量全大写，多个单词以下划线分割。'; 
            }
        } else { // `else`的格式
            // 二维数组定义格式
            $list = [
                ['id' => 1, 'name' => 'a'],
                ['id' => 2, 'name' => 'b'],
                ['id' => 3, 'name' => 'c'] // 数组最后元素不要加逗号
            ];
            // `foreach`的格式
            foreach ($list as $key => $item) { // 此处如果循环一维数组，可以把`$item`取名为`$value`
                foreach ($item as $k => $v) { // 在嵌套的循环中，一般用缩写表示键和值，用来和第一维区分
                    // `switch`的格式
                    switch ($k) {
                        case 'name':
                            // 拼接多个变量到字符串时，尽量使用`sprintf()`函数
                            $list[$key][$k] = sprintf('当前序号：%d，当前字段：%s，值为%s', $key, $k, $v);
                            break;
                        default:
                            break;
                    }
                }
            }
            // 调试函数三剑客
            tes($list); // 比`print_r()`输出的结果好看，数组和对象会换行显示，能看得更清楚
            test($list); // 功能同上，但是会中断程序执行
            dump($bool); // 当想查看变量具体类型，使用这个，结果结果能换行显示，会中断程序执行
            // 重定向页面的方法，所有表示地址的参数格式都如下，比如`url()`函数，主要用来生成 a标签的 href。注意第一个参数是数组
            // 此处意为定向到当前模块的 site 控制器的 actionIndex 方法，也就是本方法。所以如果让代码执行到这里，会导致重复跳转本页面最终使浏览器页面崩溃出错
            return $this->redirect(['site/index']); // 故而必须注意代码逻辑，避免发生如此情形
        }
        // 使用匿名函数定义变量，并引入外部变量`$bool`，格式如下
        $getSummary = function () use ($bool) {
            if ($bool) {
                // 多行长字符串使用如下方式定义
                return <<<TEXT
Q：我需要先了解什么？
A：熟练掌握PHP基础语法，尤其是面向对象相关语法；已掌握 Yii2 框架基本使用方式（官方文档：https://www.yiichina.com/doc/guide/2.0）。
Q：Yii2 文档内容很多，先看哪几个章节？
A：入门篇（其中Gii只需了解概念，实际开发使用其他方式替代），应用结构篇（其中过滤器、小部件、扩展初期不用看；前端资源了解概念即可，实际使用很简单），配合数据库工作中的前三个章节（内容很多，只需了解概念和常用功能即可）
Q：本文档和 Yii2 官方文档的区别是？
A：实际开发中我们使用的是定制化的 Yii2 版本，为了方便开发在原版基础上增加并调整了一些功能和用法。所以本文档旨在介绍如何使用这些功能完成常见功能的开发，展示的示例也尽可能列举了可能遇见的场景。
Q：我该怎么学习本站内容？
A：上述要求的基础知识掌握之后，可以按章节顺序学习，不要放过代码中的任意一行注释；多动手，所有示例内容都应亲手实践一遍，熟能生巧；每章底部都有特别提示信息。
TEXT;
            }
        };
        // 执行匿名函数
        $summary = $getSummary();
        // 渲染视图文件，视图层相关格式，打开视图文件层进行查看
        return $this->render('index', compact('summary')); // 视图名称与方法名称一致，通过compact方法传递控制器的变量到视图层
    }

    /**
     * 表单基础，表单的插入与更新共用一个方法
     */
    public function actionForm($id = null)
    {
        $this->title = '单表提交与常用表单元素';
        $this->hint = '每种生成表单元素的方法名是国际统一的，需要开发者自行记忆，方可使用自如';

        // admin/table 页面将会以弹窗形式打开本页面，故更换没有头尾内容的布局
        if (get('iframe')) {
            $this->layout = 'empty';
        }

        try {
            // 该方法有三个效果，如果不传或参数值为null，则返回 `new Test`；传入参数，则根据参数条件进行查询，查到则返回结果；否则抛出404异常
            $model = Test::findModel($id);
            // 编辑复选框时，需要准备好数组形式的数据，显示当前已选中的状态
            if ($model->tags) {
                $model->tags = explode(',', $model->tags);
            }
        } catch (\yii\web\NotFoundHttpException $e) { // 此处捕获抛出的404异常
            // 通过如下方式跳转链接设置参数
            return $this->redirect(['site/form', 'error' => 'ID不存在']);
        }
        
        // 加载表单数据，如果返回true，意味着处理表单提交
        if ($model->load()) { // 调用 `load()` 方法后，表单提交的内容会被自动填充
            // 复选框的处理
            if ($model->tags) {
                $model->tags = implode(',', $model->tags);
            }
            // 此处判断只是为了演示没有文件上传的表单时，可以将表单处理简化成如下形式。实际情况只需选择以下一种写法即可
            if (empty($_FILES)) {
                // `save()`方法默认会验证表单，如果不通过返回false
                if ($model->save()) { // 特别指出，表中的`created_at`、`created_by`、`updated_at`、`updated_by`，这些字段如果存在，会被自动赋值
                    return success(); // ajax请求时， `success()`方法表示成功的返回，最多可以设置 2 个参数传递回前端
                } else {
                    return error($model); // 表单提交失败，返回失败原因
                }
            } else {
                // `validate()` 方法验证表单，验证规则写在模型中，具体参阅 Yii2 的表单验证文档
                if ($model->validate()) {
                    if ($model->file->move()) { // 保存上传文件
                        $model->face = $model->file->filePath; // 获取上传文件的路径，并保存到字段中
                    }
                    // 当`$model`是通过 new 获得的，则插入新数据；当`$model`是查询获得的，则更新当前记录；
                    $model->save(false); // 另外，`state` 字段往往表示逻辑删除状态，如果存在该字段，则调用 `delete()`方法时，会被系统自动修改
                    return success();
                } else {
                    return error($model);
                }
            }
        }
        // 获取get方式传递的参数，如果获取不到，则返回自定义的值，此处是空字符串
        $error = get('error', '');

        return $this->render('form', compact('model', 'error'));
    }

    /**
     * 查询的一般用法
     */
    public function actionQuery()
    {
        $this->title = '简单查询：条件组装、分组统计、关联他表和分页搜索';
        $this->hint = '数据可以通过《表单提交》章节创建，也可以直接修改数据库的值进行测试';

        // 快速查询方法，如果根据主键查询，则可以直接使用`findOne()`方法，如果不存在，则返回`null`
        $model = Test::findOne(1); // 如果当前方法不存在第二个模型，则可以取名为'$model'，否则将类名首字母小写来命名对象，此处为'$test'
        // 一般在表单编辑情况下，我们还需要判断如果不存在则抛出一个404异常，则可以使用`findModel()`方法，查询参数与结果与`findOne()`一样
        // $model = Test::findModel(1);
        $result = [];
        // 获取 Test 模型的查询对象
        $query = Test::find();
        // 给查询对象追加各种条件方法
        $query
            ->select(['name', 'message', 'reg_date', 'face', 'sex']) // select 指定字段
            ->where('sex = :sex', [':sex' => Test::SEX_MAIL]) // 字符串形式的条件，使用参数绑定的形式
            // 追加第二个 where 条件，得用`andWhere()`方法
            ->andWhere(['reg_state' => Test::REG_STATE_YES, 'age' => 3]) // 数组键值对形式的条件，表示'reg_state=1 and age=3'
            ->andWhere(['like', 'name', 'a']) // 数组列表形式的条件，表示'name like %a%'
            ->orderBy('id DESC')
            ->limit(2)
            ->asArray() // 以数组形式返回结果
            ;
        $result['sql'] = $query->rawsql; // 查看生成的 sql 语句
        $result['one'] = $query->one(); // 返回满足条件的第一条记录；如果没有，返回 `null`
        $result['all'] = $query->all(); // 返回满足条件的所有记录；如果没有，返回空数组 `[]`
        $result['count'] = $query->count(); // 返回满足条件的记录数
        $result['exists'] = $query->exists(); // 返回布尔值，表示是否存在满足条件的记录

        // 分组统计，每个查询对象都得重新使用`find()`方法获取
        $query = Test::find()
            ->select(['SUM(account) account']) // 此处别名最好定义成一个虚拟字段；除非确定本次查询不需要`account`字段的值
            ->where(['>', 'age', 1]) // 表示'age > 1'
            ->asArray()
            ->groupBy('sex') // 根据 'sex' 字段分组
            ;
        // 注意以下两者的区别
        $result['group'] = $query->all(); // 获取每个分组中，`account`的合计值
        $result['sum'] = $query->sum('account') ?: 0; // 对所有分组的`account`字段合计

        // 关联查询，需要先设置关联关系，即`common\models\UserCharge::getUser()`方法，表示一条 UserCharge 记录属于一个 User
        $query = UserCharge::find()
            ->select(['userCharge.user_id', 'userCharge.amount', 'userCharge.created_at']) // 至少需要 select 
            ->joinWith('user') // 关联方法叫`getUser`，所以这里填`user`。如果关联方法叫`getUserCharge`，则这里填`userCharge`
            ->where(['user.vip' => 0, 'userCharge.charge_state' => UserCharge::CHARGE_STATE_WAIT]) // 使用别名消除歧义
            ->orderBy('userCharge.id DESC')
            ->limit(2)
            ->asArray()
            ;
        $result['joinSql'] = $query->rawsql;
        $result['joinAll'] = $query->all();
        $result['paginate'] = $query->paginate(5); // 分页搜索，每页显示5个

        return $this->render('query', compact('result'));
    }

    /**
     * 上传文件，此示例主要演示更一般的场景下，进行文件和数据的提交
     */
    public function actionUploadFile()
    {
        $this->title = 'AJAX上传文件或表单的使用示例';
        $this->hint = '测试进度条效果，可使用谷歌的 network 选项卡， online 按钮（一般位于第一排的最右面）';

        // 如果前端没使用 Yii2 的表单对象，则此处使用 self::getUpload() 方法接收上传文件
        if (req()->isPost) { // 表单提交必须
            // 测试模型，保存测试数据
            $model = new Test;
            // 匹配上传文件的 name 属性，如果要设置文件保存位置或更多其他配置，则此处必须传入数组格式配置项，具体参考 common\widgets\Upload 类
            $upload = self::getUpload('file');
            // 保存上传的文件到指定位置
            if ($upload->move()) {
                $model->title = post('title');
                // 如果一次提交上传多个文件，此处则是数组
                if (is_array($upload->filePath)) {
                    // 此处仅作演示，实际项目根据实际情况处理
                    $model->name = implode(',', $upload->filePath);
                    $model->message = implode(',', $upload->originName);
                } else {
                    $model->name = $upload->filePath;
                    // 获取上传文件的原始名称
                    $model->message = $upload->originName;
                }
                if ($model->insert()) {
                    return success($model->name);
                } else {
                    // 表单保存失败应该删除上传的文件，此处仅做演示，未考虑删除多个图片的情形
                    @unlink(Yii::getAlias('@webroot' . $model->name));
                    // 返回表单保存失败原因
                    return error($model);
                }
            } else {
                // 返回文件保存失败原因
                return error($upload);
            }
        }

        return $this->render('uploadFile');
    }

    /**
     * 常用助手类方法演示
     */
    public function actionHelper()
    {
        $this->title = '常用的助手类方法演示';
        $this->hint = '更多其他演示请查阅本章节的 PHP 代码';

        // ArrayHelper 助手类
        $array = [
            ['id' => '1', 'name' => 'Peter', 'class' => 'x'],
            ['id' => '2', 'name' => 'Mary', 'class' => 'x'],
            ['id' => '3', 'name' => 'Bob', 'class' => 'y'],
        ];
        // 从数组中获取指定键的值，如果获取不到，返回自定义的值，如下例子访问的是 `$array[0]['age']`，很显然不存在，所以返回 18
        $result = ArrayHelper::getValue($array, '0.age', 18); // `$result = 18`
        // 根据指定的键，返回键值对形式的数组
        $result = ArrayHelper::map($array, 'id', 'name'); // `$result = ['1' => 'Peter', '2' => 'Mary', '3' => 'Bob']`
        // 删除数组第一维的键，并返回被删除的值，如果该键不存在，则返回自定义的值
        $result = ArrayHelper::remove($array, '1', []); // `$result = ['id' => '2', 'name' => 'Mary', 'class' => 'x']`

        // StringHelper 助手类
        $string = 'php,,语言';
        // 和 PHP 内置的 `explode()` 函数主要区别在于，能够去除内容为空的项，对比如下
        $result = StringHelper::explode(',', $string); // `$result = ['php', '语言']`
        $result = explode(',', $string); // `$result = ['php', '', '语言']`

        // Curl 助手类
        $url = 'http://www.baidu.com';
        $data = ['id' => 1, 'name' => 'Peter'];
        $result = Curl::get($url); // 以 get 方式访问指定 url
        $result = Curl::get(Url::create($url, $data)); // 以 get 方式代入参数访问指定 url
        $result = Curl::post($url, $data); // 以 post 方式提交参数访问指定 url

        // 生成 url 的方法，因为太过常用，故以封装成函数形式，Yii2 原版是 `Url::to` 方法
        $result = url(['index']); // `$result = '/demo/site/index'`
        $result = url(['site/index']); // `$result = '/demo/site/index'`
        $result = url(['site/index', 'id' => 1, 'name' => 'Peter']); // `$result = '/demo/site/index?id=1&name=Peter'`
        // 获取包含域名的完整 url 地址
        $result = url(['site/index', 'id' => 1, 'name' => 'Peter'], true); // `$result = 'http://域名/demo/site/index?id=1&name=Peter'`

        // session 相关操作方法
        session('key', 'value'); // 设置 session
        session('key', 'value', 5); // 设置带过期时间的 session
        $result = session('key'); // 获取 session
        session('key', null); // 清除 session

        // cookie 相关操作方法
        cookie('key', 'value'); // 设置 cookie
        cookie('key', 'value', 5); // 设置带过期时间的 cookie
        $result = cookie('key'); // 获取 cookie
        cookie('key', null); // 清除 cookie

        // 缓存相关操作方法，默认是文件缓存
        cache('key', 'value'); // 创建缓存
        cache('key', 'value', 5); // 设置带过期时间的缓存
        $result = cache('key'); // 获取缓存
        $result = cache('key', function () { // 获取缓存，如果不存在，则创建，并可设置过期时间
            return mt_rand(1, 10);
        }, 5);
        cache('key', null); // 清除缓存

        return $this->render('helper');
    }

    /**
     * 常用前端方法
     */
    public function actionPlugn()
    {
        $this->title = '常用前端方法';
        $this->hint = '更多方法和介绍在 common/static/ChisWill/js/common.js 中，要学会通过阅读源码来学习';

        if (get('fancybox')) {
            // Ajax方式弹出层，必须使用`renderPatial()`返回不包含布局文件的视图文件
            return $this->renderPartial('_plugn', [ // 子视图一般以下划线开头，命名和主视图一样
                'message' => get('fancybox')
            ]);
        }

        return $this->render('plugn');
    }
} // 纯 PHP 文件最后以空行结尾，不需要 PHP 结束标签
