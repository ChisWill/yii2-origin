<?php

namespace common\models;

use Yii;

/**
 * 这是表 `test` 的模型
 */
class Test extends \common\components\ARModel
{
    const SEX_MAIL = 1;
    const SEX_FEMAIL = 2;

    const REG_STATE_YES = 1;
    const REG_STATE_NO = -1;

    const IS_LINKAGE_YES = 1;

    public function rules()
    {
        return [
            [['name'], 'required'],
            [['pid', 'age', 'sex', 'reg_state', 'is_linkage', 'state', 'created_by', 'sort'], 'integer'],
            [['account'], 'number'],
            [['message', 'attach', 'extra'], 'default', 'value' => ''],
            [['reg_date', 'login_time', 'created_at'], 'safe'],
            [['email'], 'email'],
            [['name'], 'string', 'max' => 255],
            [['mobile'], 'string', 'max' => 11],
            [['title', 'email'], 'string', 'max' => 100],
            [['image', 'face'], 'string', 'max' => 200],
            [['tags'], 'string', 'max' => 1000]
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => '名字',
            'mobile' => '手机',
            'pid' => 'Pid',
            'title' => '标题',
            'age' => '年龄',
            'account' => '账户余额',
            'message' => '内容',
            'reg_date' => 'Reg Date',
            'image' => 'Image',
            'email' => '邮箱',
            'face' => '头像',
            'tags' => '标签',
            'sex' => '性别',
            'attach' => '附件',
            'extra' => '额外信息',
            'reg_state' => '注册状态',
            'login_time' => '登录时间',
            'is_linkage' => '是否用于显示层级列表',
            'state' => 'State',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
            'sort' => 'Sort',
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
                'test.id' => $this->id,
                'test.pid' => $this->pid,
                'test.age' => $this->age,
                'test.account' => $this->account,
                'test.sex' => $this->sex,
                'test.reg_state' => $this->reg_state,
                'test.is_linkage' => $this->is_linkage,
                'test.state' => $this->state,
                'test.created_by' => $this->created_by,
                'test.sort' => $this->sort,
            ])
            ->andFilterWhere(['like', 'test.name', $this->name])
            ->andFilterWhere(['like', 'test.mobile', $this->mobile])
            ->andFilterWhere(['like', 'test.title', $this->title])
            ->andFilterWhere(['like', 'test.message', $this->message])
            ->andFilterWhere(['like', 'test.reg_date', $this->reg_date])
            ->andFilterWhere(['like', 'test.image', $this->image])
            ->andFilterWhere(['like', 'test.email', $this->email])
            ->andFilterWhere(['like', 'test.face', $this->face])
            ->andFilterWhere(['like', 'test.tags', $this->tags])
            ->andFilterWhere(['like', 'test.attach', $this->attach])
            ->andFilterWhere(['like', 'test.extra', $this->extra])
            ->andFilterWhere(['like', 'test.login_time', $this->login_time])
            ->andFilterWhere(['like', 'test.created_at', $this->created_at])
            ->andTableSearch()
        ;
    }

    /****************************** 以下为公共操作的方法 ******************************/

    

    /****************************** 以下为字段的映射方法和格式化方法 ******************************/

    // Map method of field `sex`
    public static function getSexMap($prepend = false)
    {
        $map = [
            self::SEX_MAIL => '男',
            self::SEX_FEMAIL => '女'
        ];

        return self::resetMap($map, $prepend);
    }

    // Format method of field `sex`
    public function getSexValue($value = null)
    {
        return $this->resetValue($value);
    }

    // Map method of field `reg_state`
    public static function getRegStateMap($prepend = false)
    {
        $map = [
            self::REG_STATE_YES => '正常',
            self::REG_STATE_NO => '错乱'
        ];

        return self::resetMap($map, $prepend);
    }

    // Format method of field `reg_state`
    public function getRegStateValue($value = null)
    {
        return $this->resetValue($value);
    }
}
