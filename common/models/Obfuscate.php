<?php

namespace common\models;

use Yii;
use Exception;
use common\helpers\FileHelper;
use common\helpers\StringHelper;
use ZipArchive;

class Obfuscate extends \common\components\Model
{
    // 虚拟字段
    public $file;
    // 配置参数
    public $savePath = '@console/runtime/obfuscate';
    public $config = [];
    public $input;
    public $output;
    public $sourceName;
    public $outputPath;
    // 内部使用
    protected $command;
    protected $zip;
    private $_options;

    public function __construct($input = null, $config = [])
    {
        $this->input = $input;
        $this->outputPath = Yii::getAlias($this->savePath . '/' . uniqid('o', true) . '/');
        parent::__construct($config);
    }

    public function __get($name)
    {
        try {
            parent::__get($name);
        } catch (Exception $e) {
            if (!isset($this->config[$name])) {
                if ($this->_options === null) {
                    $this->_options = $this->getOptions();
                }
                $this->config[$name] = $this->_options[$name];
            }
            return $this->config[$name];
        }
    }

    public function __set($name, $value)
    {
        try {
            parent::__set($name, $value);
        } catch (Exception $e) {
            $this->config[$name] = $value;
        }
    }

    public function init()
    {
        parent::init();

        $this->command = Yii::getAlias('@vendor/yakpro-po/yakpro-po.php');
        $this->sourceName = $this->sourceName ?: basename($this->input);
    }

    public function rules()
    {
        return [
            [['obfuscate_constant_name', 'obfuscate_variable_name', 'obfuscate_function_name', 'obfuscate_class_name', 'obfuscate_interface_name', 'obfuscate_trait_name', 'obfuscate_class_constant_name', 'obfuscate_property_name', 'obfuscate_method_name', 'obfuscate_namespace_name', 'obfuscate_string_literal'], 'boolean', 'message' => 'Don\'t Hack.'],
            [['t_ignore_constants', 't_ignore_functions', 't_ignore_classes', 't_ignore_namespaces'], 'string', 'max' => 500, 'tooLong' => t('Ignore List is too much.')],
            ['user_comment', 'string', 'max' => 100, 'tooLong' => t('Comment is too long.')],
            ['file', 'file', 'skipOnEmpty' => false, 'uploadRequired' => t('Please upload a file.'), 'checkExtensionByMimeType' => false, 'maxSize' => 2 * 1024 * 1024, 'tooBig' => t('The file is too big.'), 'extensions' => ['php', 'zip'], 'wrongExtension' => t('Extension must be "php" or "zip".')]
        ];
    }

    public function attributeLabels()
    {
        return [
            'obfuscate_constant_name' => t('Constant Name'),
            'obfuscate_variable_name' => t('Variable Name'),
            'obfuscate_function_name' => t('Function Name'),
            'obfuscate_class_name' => t('Class Name'),
            'obfuscate_interface_name' => t('Interface Name'),
            'obfuscate_trait_name' => t('Trait Name'),
            'obfuscate_class_constant_name' => t('Class Constant Name'),
            'obfuscate_property_name' => t('Property Name'),
            'obfuscate_method_name' => t('Method Name'),
            'obfuscate_namespace_name' => t('Namespace Name'),
            'obfuscate_string_literal' => t('String Literal'),
            't_ignore_constants' => t('Ignore Constant List'),
            't_ignore_functions' => t('Ignore Function List'),
            't_ignore_classes' => t('Ignore Class List'),
            't_ignore_namespaces' => t('Ignore Namespace List'),
            'user_comment' => t('Your Comment'),
            'file' => ''
        ];
    }

    public function getOptions()
    {
        return [
            'obfuscate_constant_name' => 0,
            'obfuscate_variable_name' => 1,
            'obfuscate_function_name' => 0,
            'obfuscate_class_name' => 0,
            'obfuscate_interface_name' => 0,
            'obfuscate_trait_name' => 0,
            'obfuscate_class_constant_name' => 0,
            'obfuscate_property_name' => 0,
            'obfuscate_method_name' => 0,
            'obfuscate_namespace_name' => 0,
            'obfuscate_string_literal' => 1,
            't_ignore_constants' => '',
            't_ignore_functions' => '',
            't_ignore_classes' => '',
            't_ignore_namespaces' => '',
            'user_comment' => 'Powered by ChisWill',
        ];
    }

    public function run()
    {
        if ($this->isZipFile($this->sourceName)) {
            $zip = new ZipArchive;
            if ($zip->open($this->input) === true) {
                $dirs = $this->outputPath . 'tmp';
                $zip->extractTo($dirs);
                $zip->close();
                $this->exec($dirs);
                FileHelper::removeDirectory($dirs);
            } else {
                return false;
            }
        } elseif ($this->isPhpFile($this->sourceName) || is_dir($this->input)) {
            $this->exec($this->input);
        } else {
            return false;
        }
        
        if ($this->isPhpFile($this->sourceName)) {
            return $this->output;
        } elseif ($this->isZipFile($this->sourceName)) {
            $this->zip = $zip = new ZipArchive;
            if ($zip->open($this->outputPath . $this->sourceName, ZipArchive::CREATE) === true) {
                $this->output = $this->outputPath . 'yakpro-po/obfuscated';
                FileHelper::removeDirectory($this->output . '/__MACOSX');
                $this->addFileToZip($this->output);
                $zip->close(); 
            } else {
                return false;
            }
            return $this->outputPath . $this->sourceName;
        } elseif (is_dir($this->input)) {
            return $this->outputPath . 'yakpro-po/obfuscated';
        }
    }

    protected function getPhpCommandPath()
    {
        $pieces = StringHelper::explode(' ', exec('whereis php'));
        $result = array_filter($pieces, function ($item) {
            return strpos($item, '/bin/') !== false || strpos($item, '/sbin/') !== false;
        });
        return current($result);
    }

    protected function exec($target)
    {
        FileHelper::mkdir($this->outputPath);
        if ($this->isPhpFile($this->sourceName)) {
            $this->output = $this->outputPath . 'output.php';
        } else {
            $this->output = rtrim($this->outputPath, '/');
        }
        if (!$this->config) {
            $this->config = $this->getOptions();
        }
        $extraOption = ' --config-file ' . $this->outputPath . 'config.cnf';
        $this->createConfigFile($this->config);
        exec($this->getPhpCommandPath() . ' ' . $this->command . ' ' . $target . ' -o ' . $this->output . $extraOption);
    }

    protected function createConfigFile($config)
    {
        foreach ($config as $key => $value) {
            if (strpos($key, 't_ignore') === 0) {
                if (!$value) {
                    $config[$key] = 'null';
                } else {
                    $config[$key] = var_export(preg_split('/,|，/', $config[$key]), true);
                }
            } elseif (is_bool($value)) {
                $config[$key] = $config[$key] == 1 ? 'true' : 'false';
            } elseif (is_array($value)) {
                $config[$key] = var_export($config[$key], true);
            } elseif (is_string($value)) {
                $config[$key] = "'{$config[$key]}'";
            }
        }
        extract($config);
        file_put_contents($this->outputPath . 'config.cnf', <<<CONFIG
<?php
\$conf->t_ignore_pre_defined_classes     = 'all';
\$conf->t_ignore_constants               = {$t_ignore_constants};
\$conf->t_ignore_variables               = null;
\$conf->t_ignore_functions               = {$t_ignore_functions};
\$conf->t_ignore_class_constants         = null;
\$conf->t_ignore_methods                 = null;
\$conf->t_ignore_properties              = null;
\$conf->t_ignore_classes                 = {$t_ignore_classes};
\$conf->t_ignore_interfaces              = null;
\$conf->t_ignore_traits                  = null;
\$conf->t_ignore_namespaces              = {$t_ignore_namespaces};
\$conf->t_ignore_labels                  = null;
\$conf->t_ignore_constants_prefix        = null;
\$conf->t_ignore_variables_prefix        = null;
\$conf->t_ignore_functions_prefix        = null;
\$conf->t_ignore_class_constants_prefix  = null;
\$conf->t_ignore_properties_prefix       = null;
\$conf->t_ignore_methods_prefix          = null;
\$conf->t_ignore_classes_prefix          = null;
\$conf->t_ignore_interfaces_prefix       = null;
\$conf->t_ignore_traits_prefix           = null;
\$conf->t_ignore_namespaces_prefix       = null;
\$conf->t_ignore_labels_prefix           = null;
\$conf->scramble_mode                    = 'identifier';
\$conf->scramble_length                  = 5;
\$conf->t_obfuscate_php_extension        = array('php');
\$conf->obfuscate_constant_name          = {$obfuscate_constant_name};
\$conf->obfuscate_variable_name          = {$obfuscate_variable_name};
\$conf->obfuscate_function_name          = {$obfuscate_function_name};
\$conf->obfuscate_class_name             = {$obfuscate_class_name};
\$conf->obfuscate_interface_name         = {$obfuscate_interface_name};
\$conf->obfuscate_trait_name             = {$obfuscate_trait_name};
\$conf->obfuscate_class_constant_name    = {$obfuscate_class_constant_name};
\$conf->obfuscate_property_name          = {$obfuscate_property_name};
\$conf->obfuscate_method_name            = {$obfuscate_method_name};
\$conf->obfuscate_namespace_name         = {$obfuscate_namespace_name};
\$conf->obfuscate_label_name             = true;
\$conf->obfuscate_if_statement           = true;
\$conf->obfuscate_loop_statement         = true;
\$conf->obfuscate_string_literal         = {$obfuscate_string_literal};
\$conf->shuffle_stmts                    = true;
\$conf->shuffle_stmts_min_chunk_size     = 1;
\$conf->shuffle_stmts_chunk_mode         = 'fixed';
\$conf->shuffle_stmts_chunk_ratio        = 20;
\$conf->strip_indentation                = true;
\$conf->abort_on_error                   = true;
\$conf->confirm                          = true;
\$conf->silent                           = false;
\$conf->source_directory                 = null;
\$conf->target_directory                 = null;
\$conf->t_keep                           = null;
\$conf->t_skip                           = null;
\$conf->user_comment                     = {$user_comment};
\$conf->extract_comment_from_line        = null;
\$conf->extract_comment_to_line          = null;
CONFIG
        );
    }

    protected function addFileToZip($path)
    {
        $zip = $this->zip;
        $handler = opendir($path);
        while (($file = readdir($handler)) !== false) {
            if ($file !== '.' && $file !== '..') {
                $filePath = $path . '/' . $file;
                if (is_dir($filePath)) {
                    $this->addFileToZip($filePath);
                } else {
                    $zip->addFile($filePath, strtr($filePath, [$this->output => '']));
                }
            }
        }
        @closedir($handler);
    }

    protected function isPhpFile($file)
    {
        return substr($file, -4, 4) === '.php';
    }

    protected function isZipFile($file)
    {
        return substr($file, -4, 4) === '.zip';
    }
}
