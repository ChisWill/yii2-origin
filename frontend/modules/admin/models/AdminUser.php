<?php

namespace admin\models;

use Yii;
use common\helpers\Html;

class AdminUser extends \common\models\AdminUser
{
    public $oldPassword;
    public $newPassword;
    public $cfmPassword;

    public function rules()
    {
        return array_merge(parent::rules(), [
            [['oldPassword', 'newPassword', 'cfmPassword'], 'required', 'on' => 'password'],
            [['oldPassword'], 'validateOldPassword'],
            [['newPassword'], 'compare', 'compareAttribute' => 'cfmPassword'],
        ]);
    }

    public function scenarios()
    {
        return array_merge(parent::scenarios(), [
            'password' => ['oldPassword', 'newPassword', 'cfmPassword'],
        ]);
    }

    public function attributeLabels()
    {
        return array_merge(parent::attributeLabels(), [
            'oldPassword' => '旧密码',
            'newPassword' => '新密码',
            'cfmPassword' => '确认密码',
        ]);
    }

    public function validateOldPassword()
    {
        if (!u()->validatePassword($this->oldPassword)) {
            $this->addError('oldPassword', '旧密码不正确');
        }
    }

    public static function getAdminListHtml()
    {
        $query = (new static)
            ->search()
            ->with(['roles.item'])
            ->andWhere(['state' => static::STATE_VALID])
            ->andWhere(['<=', 'power', u()->power]);
        return $query->getTable([
            'id' => ['search' => true],
            'username' => ['search' => true],
            'realname' => ['search' => true, 'type' => 'text'],
            'login_time',
            'roles' => ['header' => '角色', 'value' => function ($user) {
                $roles = [];
                foreach ($user->roles as $role) {
                    if ($role['item']['description'] == Yii::$app->controller->module->id) {
                        $roles[] = Html::likeSpan($role->item_name);
                    }
                }
                return implode('，', $roles);
            }],
            'state' => ['search' => 'select'],
            ['type' => ['edit' => 'saveAdmin', 'delete' => 'ajaxDeleteAdmin']]
        ], [
            'addBtn' => ['saveAdmin' => '添加管理员'],
            'ajaxUpdateAction' => 'ajaxUpdateAdmin'
        ]);
    }
}
