<?php

namespace common\traits;

use Yii;
use common\helpers\Inflector;
use common\helpers\ArrayHelper;
use common\helpers\StringHelper;

/**
 * ARModel和Model的共通方法
 *
 * @author ChisWill
 */
trait ModelTrait
{
    /**
     * 设置上传的文件信息
     * 
     * @param string $field 上传文件的[name]属性
     * @return object
     */
    public function setUploadedFile($field)
    {
        $modelName = StringHelper::basename(get_called_class());
        $file = ArrayHelper::getValue($_FILES, $modelName . '.name', []);
        $isMultiple = $file && isset($file[$field]) && is_array($file[$field]);
        if ($isMultiple) {
            $this->$field = \common\widgets\UploadedFile::getInstances($this, $field);
        } else {
            $this->$field = \common\widgets\UploadedFile::getInstance($this, $field);
        }

        return $this;
    }

    /**
     * 覆写父类方法，当未设置$data时，自动获取 POST 中的数据
     */
    public function load($data = null, $formName = null)
    {
        if ($data === null) {
            $data = Yii::$app->request->post();
        }
        return parent::load($data, $formName);
    }

    /**
     * 获取模型中字段定义的描述信息
     * 
     * @param  string $field 字段
     * @return string
     */
    public function label($field)
    {
        $labels = method_exists($this, 'attributeLabels') ? $this->attributeLabels() : [];

        return ArrayHelper::getValue($labels, $field, '');
    }

    /**
     * 重置字段的映射，添加默认值
     * 
     * @param  array          $map     映射数组
     * @param  boolean|string $prepend 要添加的默认值
     * @return array                   添加完默认值的字段映射数组
     */
    protected static function resetMap($map, $prepend = false)
    {
        if ($prepend !== false) {
            $prepend === true && $prepend = '全部';
            $map = ['' => $prepend] + $map;
        }

        return $map;
    }

    /**
     * 重置字段的值，默认从字段的映射数组获取
     * 
     * @param  mixed  $value      要重置的字段的值，如果为null，则表示使用模型中对应字段的值
     * @param  string $emptyValue 如果不能从字段映射中获取到值，则返回的默认值
     * @return mixed              重置后的值
     */
    protected function resetValue($value = null, $emptyValue = '')
    {
        // 获取调用的方法
        $valueMethod = debug_backtrace()[1]['function'];
        // 找到对应的字段
        preg_match('/^get(.*)Value$/U', $valueMethod, $mapMethod);
        $field = $mapMethod[1];
        // 拼接对应的map方法
        $mapMethod = 'get' . $field . 'Map';
        // 得到map
        $map = static::$mapMethod();
        // 只有value为null的时候，才使用模型中对应字段的值
        $value === null && $value = $this->{Inflector::camel2id($field, '_')};
        // 从map中获取该value的输出值，如果不存在则返回空字符串
        return ArrayHelper::getValue($map, $value, $emptyValue);
    }
}
