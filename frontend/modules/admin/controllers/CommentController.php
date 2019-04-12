<?php

namespace admin\controllers;

use Yii;
use admin\models\Form;

class CommentController extends \admin\components\Controller
{
    /**
     * @authname 留言查看
     */
    public function actionFormList()
    {
        $query = Form::getTypeQuery(Form::TYPE_MESSAGE);

        $html = $query->getTable(Form::getTableColumns([
            ['realname' => '姓名'],
            ['company_name' => '公司名'],
            ['email' => '邮箱'],
            ['tel' => '电话'],
            ['requirement' => '需求'],
            ['desc' => '简述']
        ]), [
            'extraBtn' => [
                'readAll?type=' . Form::TYPE_MESSAGE => '全部已读'
            ]
        ]);

        return $this->render('formList', compact('html'));
    }

    /**
     * @authname 全部已读
     */
    public function actionReadAll($type)
    {
        Form::updateAll(['is_read' => Form::IS_READ_YES], ['type' => $type]);

        return $this->redirect(req()->getReferrer());
    }
}
