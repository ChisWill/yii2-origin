<?php

namespace common\components;

use Yii;
use common\helpers\Hui;
use common\helpers\Security;
use common\helpers\ArrayHelper;
use common\helpers\StringHelper;
use yii\validators\Validator;

/**
 * 前后台AR模型的基类
 *
 * @author ChisWill
 */
class ARModel extends \yii\db\ActiveRecord
{
    use \common\traits\ChisWill;
    use \common\traits\ModelTrait;

    // 模型的公共常量
    const STATE_VALID = 1;
    const STATE_INVALID = -1;
    // 公共虚拟字段
    public $start_date;
    public $end_date;
    // 不进行验证的属性、规则列表
    protected $_exceptRules = [];

    public function init()
    {
        parent::init();
        // 非生产环境下的操作
        if (!YII_ENV_PROD) {
            $publicVars = Yii::getObjectVars($this);
            foreach ($this->attributes() as $field) {
                if (method_exists($this, $method = 'get' . ucfirst($field))) { // 检测模型中的方法和字段对应的getter方法是否重名
                    throw new \yii\base\Exception("{$this::className()}::{$method}() 与字段 $field 的 getter 方法命名重复，请更改方法名或字段名！");
                } elseif (array_key_exists($field, $publicVars)) { // 检测属性名和字段名是否重复
                    throw new \yii\base\Exception("{$this::className()}中的公共属性 \${$field} 和字段名重复，请修改属性名或字段名！");
                }
            }
        }
    }
    
    public function behaviors()
    {
        return [
            // AR模型的 插入/更新 前的行为，将会自动填充 created, updated 等字段
            \common\behaviors\ARSaveBehavior::className()
        ];
    }

    /****************************** 以下是公共字段的映射定义和格式化输出范例 ******************************/

    public static function getStateMap($prepend = false)
    {
        $map = [
            self::STATE_VALID => '有效',
            self::STATE_INVALID => '无效'
        ];

        return self::resetMap($map, $prepend);
    }

    public function getStateValue($value = null)
    {
        return $this->resetValue($value);
    }

    /****************************** 以下是公共基础方法 ******************************/

    /**
     * 设置不进行验证的属性以及对应的规则，以下为使用示例：
     * ```php
     * $model->exceptRules = ['required' => ['mobile', 'username'], 'string' => 'password'];
     * ```
     * 
     * @param  array $exceptRules 需要排除验证的规则与属性
     * @return $this
     */
    public function setExceptRules(array $exceptRules = [])
    {
        $this->_exceptRules = $exceptRules;

        return $this;
    }

    /**
     * 覆写父类方法，增加了对排除规则的判断
     *
     * @see yii\base\Model::createValidators()
     */
    public function createValidators()
    {
        $validators = new \ArrayObject;
        foreach ($this->rules() as $rule) {
            if ($rule instanceof Validator) {
                $validators->append($rule);
            } elseif (is_array($rule) && isset($rule[0], $rule[1])) { // attributes, validator type
                $attributes = (array) $rule[0];
                if (isset($this->_exceptRules[$rule[1]])) {
                    $attributes = array_diff($attributes, (array) $this->_exceptRules[$rule[1]]);
                    if (!$attributes) {
                        continue;
                    }
                }
                $validator = Validator::createValidator($rule[1], $this, $attributes, array_slice($rule, 2));
                $validators->append($validator);
            } else {
                throw new \yii\base\InvalidConfigException('Invalid validation rule: a rule must specify both attribute names and validator type.');
            }
        }
        return $validators;
    }

    /**
     * 在更新记录的情况下，默认只验证修改过的字段
     * 
     * @see yii\base\Model::validate()
     */
    public function validate($attributeNames = null, $clearErrors = true)
    {
        if (!$this->isNewRecord && $attributeNames === null) {
            if ($this->scenario !== 'default') {
                $attributeNames = $this->scenarios()[$this->scenario];
            } elseif ($this->dirtyAttributes) {
                $attributeNames = array_keys($this->dirtyAttributes);
            }
        }
        return parent::validate($attributeNames, $clearErrors);
    }

    /**
     * 覆写父类方法，目的在于改为实例化自定义的 common\componets\ARQuery，并设置表的别名
     */
    public static function find()
    {
        $modelClass = get_called_class();

        return Yii::createObject(ARQuery::className(), [$modelClass])->from([static::getModelAlias($modelClass) => $modelClass::tableName()]);
    }

    /**
     * 覆写父类方法，目的在于设定关联表的别名，设定为方法名，且优先调用当前项目下对应的模型
     */
    public function hasOne($class, $link)
    {
        // 获取调用的方法
        $method = debug_backtrace()[1]['function'];
        // 获取关联名
        preg_match('/^get(.*)$/U', $method, $res);
        // 获取别名
        $alias = lcfirst($res[1]);
        // 获取关联类名
        $className = StringHelper::basename($class);
        // 获取调用的类名
        $callClass = get_called_class();
        // 获取调用类的命名空间
        $namespace = strstr($callClass, StringHelper::basename($callClass), true);
        // 优先选取当前命名空间下存在的类进行关联
        if (class_exists($namespace . $className)) {
            $class = $namespace . $className;
        }

        return parent::hasOne($class, $link)->from([$alias => $class::tableName()]);
    }

    /**
     * 覆写父类方法，目的在于设定关联表的别名，设定为方法名，且优先调用当前项目下对应的模型
     */
    public function hasMany($class, $link)
    {
        // 获取调用的方法
        $method = debug_backtrace()[1]['function'];
        // 获取关联名
        preg_match('/^get(.*)$/U', $method, $res);
        // 获取别名
        $alias = lcfirst($res[1]);
        // 获取关联类名
        $className = StringHelper::basename($class);
        // 获取调用的类名
        $callClass = get_called_class();
        // 获取调用类的命名空间
        $namespace = strstr($callClass, StringHelper::basename($callClass), true);
        // 优先选取当前命名空间下存在的类进行关联
        if (class_exists($namespace . $className)) {
            $class = $namespace . $className;
        }

        return parent::hasMany($class, $link)->from([$alias => $class::tableName()]);
    }

    /**
     * 内嵌了错误处理的模型获取方法
     * 
     * @param  mixed $condition
     * @return object
     */
    public static function findModel($condition = null)
    {
        if ($condition === null) {
            return new static;
        }

        $model = static::findOne($condition);

        if ($model === null) {
            throwex('Not Found');
        }
        
        return $model;
    }

    /**
     * 根据请求中的参数为模型中的字段批量赋值
     * 
     * @param  string $name 搜索参数的name前缀值
     * @return object       当前对象
     */
    protected function setSearchParams($name = 'search')
    {
        foreach (get($name, []) as $field => $value) {
            try {
                $this->$field = $value;
            } catch (\yii\base\UnknownPropertyException $e) {
                // do nothing...
            }
        }

        return $this;
    }

    /**
     * @see common\componets\ARQuery::map()
     */
    public static function map($key, $value = null)
    {
        $className = get_called_class();

        return $className::find()->map($key, $value);
    }

    /**
     * 切换指定字段的逻辑值
     * 
     * @param  string  $field 字段名称
     * @return boolean
     */
    public function toggle($field = 'state')
    {
        if ($this->$field == static::STATE_VALID) {
            $this->$field = static::STATE_INVALID;
        } else {
            $this->$field = static::STATE_VALID;
        }
        return $this->update();
    }

    /**
     * 状态切换的按钮
     * 按钮顺序一般遵循以下逻辑：假定事物默认是正常态，则操作顺序应为先删除后恢复
     *
     * @param  string  $filed   字段
     * @param  array   $btns    按钮描述
     * @param  boolean $reverse 按钮逻辑是否反转
     * @return string
     */
    public function toggleBtn($field = 'state', $btns = ['冻结', '恢复'], $reverse = false)
    {
        if ($this->$field == static::STATE_VALID && !$reverse) {
            $method = 'dangerBtn';
            $btn = $btns[0];
        } else {
            $method = 'successBtn';
            $btn = $btns[1];
        }
        $key = $this->primaryKey()[0];
        $keyValue = $this->$key;
        $data = ['field' => $field, 'key' => $keyValue, 'class' => $this::className()];
        $params = Security::base64encrypt(json_encode($data));
        return Hui::$method($btn, ['toggle', 'params' => $params], ['class' => 'ajaxBtn']);
    }

    /**
     * 快捷生成表单标题
     *
     * @param string $label   表单标题
     * @param array  $options 标题属性
     * @return string
     */
    public function title($label = '', $options = [])
    {
        $label = ($this->isNewRecord ? '添加' : '编辑') . $label;
        $tag = ArrayHelper::remove($options, 'tag', 'h2');
        $options['style'] = (array) ArrayHelper::getValue($options, 'style');
        $options['style']['text-align'] = 'center';

        return Hui::$tag($label, $options);
    }

    /**
     * 删除指定字段保存的上传文件
     * 
     * @param  string  $field 要删除文件所对应的字段
     * @return boolean        删除状态
     */
    public function deleteFile($field)
    {
        return @unlink(Yii::getAlias('@webroot') . $this->$field);
    }

    /**
     * Linkage 组件的默认逻辑删除方法
     * 当对应表存在逻辑有效值 state 时，将会自动调用该方法进行删除操作
     * 
     * @param  array       $ids 要删除的元素的主键序列
     * @return true|string      成功则返回true，失败则返回错误原因
     */
    public function deleteLinkage($ids)
    {
        $primaryKey = current($this->primaryKey());
        if (self::dbUpdate($this::tableName(), ['state' => self::STATE_INVALID], [$primaryKey => $ids])) {
            return true;
        } else {
            return '已经删除了！';
        }
    }

    /**
     * 获取模型的别名
     * 
     * @param  string $modelClass 要获取别名的模型类名
     * @return string
     */
    protected static function getModelAlias($modelClass)
    {
        return lcfirst(StringHelper::basename($modelClass));
    }

    /**
     * 获取模型的真实表名
     * 
     * @return string
     */
    public static function rawTableName()
    {
        return static::getDb()
            ->getSchema()
            ->getRawTableName(static::tableName());
    }
}
