<?php

namespace console\controllers;

use common\helpers\Curl;
use common\helpers\Date;
use Yii;
use common\helpers\System;
use common\helpers\FileHelper;
use common\helpers\Url;

class AghController extends \common\components\ConsoleController
{
    public function actionAdt()
    {
        $guids = ['279423'];
        $result = [];
        foreach ($guids as $guid) {
            $user = self::db(sprintf('select * from bs_users where guid = %s limit 1', $guid))->queryOne();
            $answers = self::db(sprintf('select blockade_id,question_id,min(answer_start_time) start_time,max(answer_time) end_time,max(step) step from bs_blockade_user_answers where activity_id=%s and user_id=%s and answer_start_time>0 group by blockade_id order by start_time asc', $user['activity_id'], $user['user_id']))->queryAll();
            $current = array_shift($answers);
            foreach ($answers as $answer) {
                if ($answer['start_time'] <= $current['end_time']) {
                    $result[$guid] = [
                        'prev_blockade_id' => $current['blockade_id'],
                        'prev_time' => Date::time($current['start_time']) . '-' . Date::time($current['end_time']),
                        'current_blockade_id' => $answer['blockade_id'],
                        'current_time' => Date::time($answer['start_time']) . '-' . Date::time($answer['end_time']),
                    ];
                }
                $current = $answer;
            }
        }
        test($result);
    }

    public function actionYt()
    {
        $a = 'D0VOwZ%252FA%252FU3nx7m5gRGnpgiAMtHniQEgUboh2FnMEVM%253D';
        $r = base64_decode(urldecode($a));
        test($r);
        $appKey='679dc5f320414fe4bb5bf4e2cd9d631d';
        $appSecret='737f00445af04bcebde0329bfed033d6';
        $url = 'https://api.diwork.com/open-auth/selfAppAuth/getAccessToken';

        $arr = [
            'appKey' => $appKey,
            'timestamp' => (string)(int)(microtime(true) * 1000)
        ];
        $getSign = function ($params, $appSecret) {
            $s = '';
            foreach ($params as $k => $v) {
                $s .= $k . $v;
            }
            return urlencode(base64_encode(hash_hmac('sha256', $s, $appSecret)));
        };
        $arr['signature'] = $getSign($arr, $appSecret);
        $url = Url::create($url, $arr);
        $r = Curl::get($url);
        tes($arr);

        test($r);
    }
}
