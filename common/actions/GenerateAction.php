<?php

namespace common\actions;

use Yii;
use common\components\ARModel;
use common\helpers\Cookie;
use common\helpers\Inflector;
use common\helpers\FileHelper;

class GenerateAction extends \common\components\Action
{
    /**
     * 调用各生成生成器模块
     */
    public function run()
    {
        $action = post('action');

        if (method_exists($this, $action)) {
            return call_user_func([$this, $action]);
        } else {
            die('Access Denied');
        }
    }

    /**
     * 生成项目
     */
    protected function generateApp()
    {
        $generator = $this->loadGenerator('app');
        if ($generator->hasErrors()) {
            return error($generator);
        }
        // 设置项目别名
        $generator->setAppAlias();
        // 获取项目目录
        $appPath = Yii::getAlias('@' . $generator->appName);
        try {
            ob_start();
            // 复制整个项目目录
            FileHelper::copyDirectory(Yii::getAlias('@common/modules/wizard/views/generate/app/'), $appPath);
            // 所有需要修改的模板路径
            $list = [
                'assets' => ['AppAsset', 'UserAsset'],
                'components' => ['Controller', 'WebUser'],
                'config' => ['main'],
                'controllers' => ['SiteController', 'UserController'],
                'models' => ['User']
            ];
            // 渲染视图的参数
            $params = [
                'appName' => $generator->appName
            ];
            // 修改所有模板
            foreach ($list as $space => $sub) {
                foreach ($sub as $class) {
                    $params['namespace'] = "{$generator->appName}\\$space";
                    $path = Yii::getAlias("@{$generator->appName}/{$space}/{$class}.php");
                    $content = $this->renderPartial("app/{$space}/{$class}", $params);
                    file_put_contents($path, $content);
                }
            }
            // 修改视图中布局文件中的命名空间名称
            $layoutPath = Yii::getAlias('@' . $generator->appName . '/views/layouts/main.php');
            $namespace = $generator->appName . '\\assets';
            file_put_contents($layoutPath, str_replace('$namespace', $namespace . '\\AppAsset', file_get_contents($layoutPath)));
            // 成功的返回
            return success('项目 ' . $generator->appName . ' 生成成功~！');
        } catch (\Exception $e) {
            ob_end_clean();
            // 重置所做的操作
            FileHelper::removeDirectory($appPath);
            $generator->revertAppAlias();
            // 失败的返回
            return error([
                '项目 ' . $generator->appName . ' 生成失败~！',
                '原因：' . $e->getMessage()
            ]);
        }
    }

    /**
     * 生成模块
     */
    protected function generateModule()
    {
        // 获取module生成器
        $generator = $this->loadGenerator('module');
        if ($generator->hasErrors()) {
            return error($generator);
        }
        // 获取模块目录
        $basePath = Yii::getAlias('@' . trim(str_replace('\\', '/', $generator->moduleNamespace), '/')) . '/' . $generator->moduleName;
        if ($isCurrentApp = $generator->moduleApp === FileHelper::getCurrentApp()) {
            $baseNs = $generator->moduleName;
        } else {
            $baseNs = $generator->moduleNamespace . '\\' . $generator->moduleName;
        }
        try {
            ob_start();
            // 复制整个项目目录
            FileHelper::copyDirectory(Yii::getAlias('@common/modules/wizard/views/generate/module/'), $basePath);
            // 所有需要修改的模板路径
            $list = [
                'assets' => ['Asset'],
                'components' => ['Controller'],
                'controllers' => ['SiteController'],
            ];
            // 渲染视图的参数
            $params = [
                'baseNs' => $baseNs,
                'moduleName' => $generator->moduleName,
                'sourcePath' => '@' . str_replace('\\', '/', $baseNs) . '/static'
            ];
            // 修改所有模板
            foreach ($list as $space => $sub) {
                foreach ($sub as $class) {
                    $params['namespace'] = "{$baseNs}\\$space";
                    $path = Yii::getAlias("$basePath/{$space}/{$class}.php");
                    $content = $this->renderPartial("module/{$space}/{$class}", $params);
                    file_put_contents($path, $content);
                }
            }
            // 修改视图中布局文件中的命名空间名称
            $layoutPath = $basePath . '/views/layouts/main.php';
            file_put_contents($layoutPath, str_replace('$namespace', $baseNs . '\\assets\\Asset', file_get_contents($layoutPath)));
            // 修改Module类的命名空间
            $modulePath = $basePath . '/Module.php';
            file_put_contents($modulePath, $this->renderPartial("module/Module", $params));
            // 当前项目下，增加模块的路径别名
            if ($isCurrentApp) {
                $alias ="Yii::setAlias('{$generator->moduleName}', dirname(__DIR__) . '/modules/{$generator->moduleName}');";
                $content = preg_replace('/(Yii::setAlias\(\'@?admin\',.*\);)/U', '$1' . PHP_EOL . $alias, file_get_contents(Yii::getAlias('@' . $generator->moduleApp . '/config/bootstrap.php')));
                file_put_contents(Yii::getAlias('@' . $generator->moduleApp . '/config/bootstrap.php'), $content);
            }
            // 修改对应项目的main.php文件
            $generator->updateConfig($baseNs);
            // 成功的返回
            return success('模块 ' . $generator->moduleName . ' 生成成功~！');
        } catch (\Exception $e) {
            ob_end_clean();
            // 重置所做的操作
            FileHelper::removeDirectory($basePath);
            // 失败的返回
            return error([
                '模块 ' . $generator->moduleName . ' 生成失败~！',
                '原因：' . $e->getMessage()
            ]);
        }
    }

    /**
     * 同步数据库迁移
     */
    protected function generateMigrate()
    {
        // 获取migrate生成器
        $generator = $this->loadGenerator('migrate');
        // 执行所有待更新迁移文件
        list($successNum, $err) = $generator->syncAll();
        $successInfo = $successNum === 0 ? '没有记录被更新。' : "成功同步 {$successNum} 条数据。";
        $errInfo = $err ? "\n遭遇一个错误\n" . implode("\n", $err) : '';
        if ($successNum) {
            return success($successInfo);
        } else {
            return error($successInfo . $errInfo);
        }
    }

    /**
     * 生成字段的格式化方法
     */
    protected function generateField()
    {
        // 获取field生成器
        $generator = $this->loadGenerator('field');
        if ($generator->hasErrors()) {
            return error($generator);
        }
        // 获取文件内容
        $fileContent = file_get_contents($generator->modelPath);
        // 设置视图的参数
        $params = [
            'field' => $generator->fieldName,
            'methodName' => Inflector::camelize($generator->fieldName)
        ];
        // 获取渲染的代码
        $content = $this->renderPartial('field', $params);
        // 替换源文件的内容
        $newContent = preg_replace('/{([\s\S]*)}/i', "{" . '$1' . $content . "}", $fileContent);
        // 写入文件
        if (@file_put_contents($generator->modelPath, $newContent)) {
            return success($generator->fullModelName . ' 的字段 ' . $generator->fieldName . ' 的格式化方法生成成功~！');
        } else {
            return error($generator->fullModelName . ' 的字段 ' . $generator->fieldName . ' 的格式化方法生成失败！！文件无法写入！！');
        }
    }

    /**
     * 模型生成
     */
    protected function generateModel()
    {
        // 获取model生成器
        $generator = $this->loadGenerator('model');
        if ($generator->hasErrors()) {
            return error($generator);
        }
        // 获取数据库连接
        $db = $generator->getDbConnection();
        // 获取完整表名
        $fullTableName = $db->tablePrefix . $generator->tableName;
        // 获取模型名
        $modelClassName = $generator->generateClassName($generator->alias);
        // 获取表信息
        $tableSchema = $db->getTableSchema($fullTableName);
        // 获取当前请求的模块关系
        $controller = $this->controller;
        $isModule = !$controller->module instanceof \yii\web\Application;
        if ($isModule) {
            $currentModule = $controller->module;
            $parentModule = $currentModule->module;
            $currentModelNs = $this->ctrlNs2ModelNs($currentModule->controllerNamespace);
            // 获取父类模型命名空间
            // $parentModelNs = $this->ctrlNs2ModelNs($parentModule->controllerNamespace);
            $parentModelNs = $generator->mainNamespace;
        } else {
            $module = $controller->module;
            $currentModelNs = $this->ctrlNs2ModelNs($module->controllerNamespace);
            $parentModelNs = $generator->mainNamespace;
        }
        // 如果选择 `继承`， 表示在公共、和当前项目一起生成模型，否则仅生成指定项目或命名空间的模型
        if ($generator->isExtend == ARModel::STATE_VALID) {
            $modelPath = Yii::getAlias('@' . str_replace('\\', '/', $parentModelNs));
            $namespace = $parentModelNs;
            $subNamespaces = [$currentModelNs];
        } elseif ($generator->isExtend == ARModel::STATE_INVALID) {
            $modelPath = Yii::getAlias('@' . str_replace('\\', '/', $currentModelNs));
            $namespace = $currentModelNs;
            $subNamespaces = [];
        } else {
            $modelPath = Yii::getAlias('@' . str_replace('\\', '/', $generator->modelNamespace));
            $namespace = $generator->modelNamespace;
            $subNamespaces = [];
        }
        // 设置视图的参数
        $mainParams = [
            'generator' => $generator,
            'namespace' => $namespace,
            'tableName' => $fullTableName,
            'className' => $modelClassName,
            'alias' => lcfirst($modelClassName),
            'labels' => $generator->generateLabels($tableSchema),
            'rules' => $generator->generateRules($tableSchema),
            'compares' => $generator->generateCompares($tableSchema)
        ];
        if (!file_exists($modelPath)) {
            FileHelper::mkdir($modelPath);
        }
        // 生成代码并写入文件
        $filename = $modelPath . '/' . $modelClassName . '.php';
        if (!file_exists($filename)) {
            if (@file_put_contents($filename, $this->renderPartial('model', $mainParams))) {
                $msg = [$namespace . '\\' . $modelClassName . ' 生成成功~！'];
            } else {
                $msg = [$namespace . '\\' . $modelClassName . ' 生成失败~！'];
            }
        } else {
            // 当文件存在时，只替换指定内容
            $oldContent = file_get_contents($filename);
            $newContent = $this->renderPartial('model', $mainParams);
            $replaceMethods = ['rules', 'attributeLabels', 'search'];
            array_walk($replaceMethods, function ($method) use (&$oldContent, $newContent) {
                $pattern = '/public\s*function\s*' . $method . '\(\).*{.*}/Uis';
                preg_match($pattern, $newContent, $match);
                $oldContent = preg_replace($pattern, $match[0], $oldContent);
            });
            if (@file_put_contents($filename, $oldContent)) {
                $msg = [$namespace . '\\' . $modelClassName . ' 覆盖成功~！'];
            } else {
                $msg = [$namespace . '\\' . $modelClassName . ' 覆盖失败~！'];
            }
        }
        // 生成附属的模型
        foreach ($subNamespaces as $subNs) {
            $subPath = Yii::getAlias('@' . str_replace('\\', '/', $subNs));
            if (!file_exists($subPath)) {
                FileHelper::mkdir($subPath);
            }
            $fileName = $subPath . '/' . $modelClassName . '.php';
            if (file_exists($fileName)) {
                continue;
            }
            $subNamespace = str_replace('/', '\\', $subNs);
            $subParams = [
                'generator' => $generator,
                'namespace' => $subNamespace,
                'tableName' => $fullTableName,
                'className' => $modelClassName,
                'parentClass' => '\\' . $namespace . '\\' . $modelClassName
            ];
            if (@file_put_contents($fileName, $this->renderPartial('subModel', $subParams))) {
                $msg[] = $subNamespace . '\\' . $modelClassName . ' 生成成功~！';
            } else {
                $msg[] = $subNamespace . '\\' . $modelClassName . ' 生成失败~！';
            }
        }
        return success($msg);
    }

    /**
     * Loads the generator with the specified ID.
     * @param string $id the ID of the generator to be loaded.
     * @return the loaded generator
     * @throws NotFoundHttpException
     */
    protected function loadGenerator($id)
    {
        $wizard = Yii::$app->getModule('wizard');
        if (isset($wizard->generators[$id])) {
            $generator = Yii::createObject($wizard->generators[$id]);
            $generator->attributes = post('Wizard');
            $generator->validate();
            return $generator;
        } else {
            throwex("Code generator not found: $id");
        }
    }

    private function ctrlNs2ModelNs($ctrlNs)
    {
        $pieces = explode('\\', $ctrlNs);
        $pieces[count($pieces) - 1] = 'models';
        return implode('\\', $pieces);
    }

    private function renderPartial($view, $params = [])
    {
        $view = '@common/modules/wizard/views/generate/' . $view;
        return $this->controller->renderPartial($view, $params);
    }
}