<?php

namespace oa\models;

use Yii;
use common\helpers\Url;
use common\helpers\Curl;

/**
 * 这是表 `oa_notice` 的模型
 */
class OaNotice extends \common\components\ARModel
{
    public $file;
    public $start_created_at;
    public $end_created_at;

    public function rules()
    {
        return [
            [['title', 'content'], 'required'],
            [['content'], 'default', 'value' => ''],
            [['download_num', 'state', 'created_by'], 'integer'],
            [['created_at'], 'safe'],
            [['title'], 'string', 'max' => 100],
            [['attach', 'origin_name'], 'string', 'max' => 200],
            [['file'], 'file']
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => '标题',
            'content' => '内容',
            'attach' => '附件',
            'origin_name' => '附件原名',
            'download_num' => '下载次数',
            'state' => '状态',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
            'file' => '附件'
        ];
    }

    /****************************** 以下为设置关联模型的方法 ******************************/

    public function getAdminUser()
    {
        return $this->hasOne(AdminUser::className(), ['id' => 'created_by']);
    }

    /****************************** 以下为公共显示条件的方法 ******************************/

    public function search()
    {
        $this->setSearchParams();

        return self::find()
            ->filterWhere([
                'oaNotice.id' => $this->id,
                'oaNotice.download_num' => $this->download_num,
                'oaNotice.state' => $this->state,
                'oaNotice.created_by' => $this->created_by,
            ])
            ->andFilterWhere(['like', 'oaNotice.title', $this->title])
            ->andFilterWhere(['like', 'oaNotice.content', $this->content])
            ->andFilterWhere(['like', 'oaNotice.attach', $this->attach])
            ->andFilterWhere(['like', 'oaNotice.origin_name', $this->origin_name])
            ->andFilterWhere(['like', 'oaNotice.created_at', $this->created_at])
            ->andTableSearch()
        ;
    }

    /****************************** 以下为公共操作的方法 ******************************/

    public static function notify($uids, $content, $url = '')
    {
        $pushApiUrl = config('webDomain') . ':' . config('httpPushPort');
        if (is_array($uids)) {
            $uids = implode(',', $uids);
        }
        $data = [
            'uids' => $uids,
            'info' => $content,
            'event' => 'notify'
        ];
        if ($url) {
            $data['url'] = $url;
        }

        Curl::get(Url::create($pushApiUrl, $data));
    }

    public static function getNoticeQuery()
    {
        return self::find()
            ->joinWith('adminUser')
            ->orderBy('oaNotice.id DESC')
            ->active()
            ->asArray()
            ;
    }

    public function listQuery()
    {
        return $this->search()
            ->joinWith(['adminUser'])
            ->andFilterWhere(['>=', 'oaNotice.created_at', $this->start_created_at])
            ->andFilterWhere(['<', 'oaNotice.created_at', $this->end_created_at ? date('Y-m-d', strtotime($this->end_created_at) + 3600 * 24) : null])
            ->active()
            ->orderBy('oaNotice.id DESC');
    }

    /****************************** 以下为字段的映射方法和格式化方法 ******************************/
}
