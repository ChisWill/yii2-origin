<?php

namespace common\models;

use Yii;

/**
 * 这是表 `picture` 的模型
 */
class Picture extends \common\components\ARModel
{
    const TYPE_SWIPER = 1;
    const TYPE_LINK = 2;

    public function rules()
    {
        return [
            [['type'], 'integer'],
            [['title', 'path'], 'string', 'max' => 100],
            [['url'], 'string', 'max' => 250]
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => '标题',
            'path' => '图片路径',
            'url' => '跳转地址',
            'type' => '类型',
        ];
    }

    /****************************** 以下为设置关联模型的方法 ******************************/

    // public function getRelation()
    // {
    //     return $this->hasOne(Class::className(), ['foreign_key' => 'primary_key']);
    // }

    /****************************** 以下为公共显示条件的方法 ******************************/

    public function search()
    {
        $this->setSearchParams();

        return self::find()
            ->filterWhere([
                'picture.id' => $this->id,
                'picture.type' => $this->type,
            ])
            ->andFilterWhere(['like', 'picture.title', $this->title])
            ->andFilterWhere(['like', 'picture.path', $this->path])
            ->andFilterWhere(['like', 'picture.url', $this->url])
            ->andTableSearch()
        ;
    }

    /****************************** 以下为公共操作的方法 ******************************/

    public static function getSwipers()
    {
        return self::find()
            ->where(['type' => self::TYPE_SWIPER])
            ->asArray()
            ->orderBy('id DESC')
            ->all();
    }

    // 获取友情链接
    public static function getLinks()
    {
        return self::find()
            ->where(['type' => self::TYPE_LINK])
            ->asArray()
            ->orderBy('id DESC')
            ->all();
    }

    /****************************** 以下为字段的映射方法和格式化方法 ******************************/

    // Map method of field `type`
    public static function getTypeMap($prepend = false)
    {
        $map = [
            self::TYPE_SWIPER => '轮播图',
            self::TYPE_LINK => '友情链接'
        ];

        return self::resetMap($map, $prepend);
    }

    // Format method of field `type`
    public function getTypeValue($value = null)
    {
        return $this->resetValue($value);
    }
}
