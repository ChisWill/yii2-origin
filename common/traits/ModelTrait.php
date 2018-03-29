<?php

namespace common\traits;

use Yii;
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
}
