<?php

namespace shudu\controllers;

use shudu\models\Logic;
use Yii;

class SiteController extends \shudu\components\Controller
{
    public $layout = 'main';

    public function actionIndex()
    {
        $data = [
            [0,3,0,6,0,5,0,0,4],
            [7,0,0,0,0,0,0,3,0],
            [0,0,0,0,0,4,0,0,0],
            [0,0,0,4,0,3,1,0,0],
            [0,0,9,0,0,0,6,0,0],
            [8,0,0,0,2,0,0,0,0],
            [0,0,0,0,0,0,0,0,9],
            [0,1,5,0,0,0,0,8,0],
            [0,0,0,0,9,0,0,5,2],
        ];
        $data = [
            [6,7,0,1,0,5,0,8,4,],
            [8,3,1,0,4,6,0,5,7,],
            [0,4,0,7,0,8,6,1,0,],
            [0,0,7,0,0,4,0,0,0,],
            [0,0,0,3,0,7,0,9,6,],
            [0,9,0,0,0,2,7,4,5,],
            [0,0,0,0,6,9,0,7,8,],
            [7,0,0,0,0,1,0,0,9,],
            [9,2,8,4,7,3,5,6,1,],
        ];
        // $data = [
        //     [6,0,0,1,0,0,0,8,0],
        //     [0,3,1,0,0,0,0,0,0],
        //     [0,4,0,7,0,0,6,0,0],
        //     [0,0,0,0,0,4,0,0,0],
        //     [0,0,0,0,0,0,0,9,6],
        //     [0,9,0,0,0,2,7,0,5],
        //     [0,0,0,0,6,9,0,0,8],
        //     [7,0,0,0,0,0,0,0,9],
        //     [0,2,0,4,0,3,0,0,0],
        // ];
        // $data = [
        //     [0, 8, 0, 0, 0, 0, 6, 0, 0],
        //     [0, 0, 0, 4, 8, 0, 0, 0, 9],
        //     [0, 7, 0, 0, 0, 0, 8, 0, 5],
        //     [4, 0, 1, 0, 0, 0, 0, 0, 0],
        //     [0, 3, 0, 0, 6, 1, 4, 9, 0],
        //     [0, 0, 0, 7, 2, 4, 1, 0, 0],
        //     [0, 9, 3, 2, 1, 8, 0, 6, 4],
        //     [8, 1, 0, 3, 4, 0, 9, 0, 0],
        //     [0, 4, 0, 0, 0, 5, 3, 0, 0],
        // ];
        $logic = new Logic();
        if (req()->isAjax) {
            switch (post('action')) {
                case 'init':
                    if ($logic->checkRawData(post('data'))) {
                        $answer = $logic->getResult();
                        return success(['data' => $answer->data, 'tag' => $answer->tag, 'desc' => $answer->getDesc(), 'step' => $answer->step]);
                    } else {
                        return error('输入数据格式不正确');
                    }
                    break;
                case 'query':
                    $step = post('step', 0);
                    if ($logic->checkRawData(post('data'))) {
                        if ($step == 0) {
                            $answer = $logic->getResult();
                        } else {
                            $answer = $logic->solve(post('step'));
                        }
                        return success(['data' => $answer->data, 'tag' => $answer->tag, 'desc' => $answer->getDesc(), 'step' => $answer->step]);
                    } else {
                        return error('输入数据格式不正确');
                    }
                    break;
                default:
                    return error('参数错误');
                    break;
            }
        } else {
            $answer = $logic->getResult();
            return $this->render('index', compact('answer'));
        }

    }
}
