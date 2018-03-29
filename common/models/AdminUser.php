<?php

namespace common\models;

use Yii;
use common\helpers\FileHelper;
use common\helpers\ArrayHelper;
use common\modules\rbac\models\AuthItem;

/**
 * 这是表 `hsh_admin_user` 的模型
 */
class AdminUser extends \common\components\ARModel
{
    public $tmpPassword;

    public function rules()
    {
        return [
            [['username', 'password'], 'required'],
            [['login_time', 'created_at', 'updated_at'], 'safe'],
            [['power', 'state', 'created_by', 'updated_by'], 'integer'],
            [['username', 'realname'], 'string', 'max' => 30],
            [['password'], 'string', 'max' => 80],
            [['username'], 'unique']
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'username' => '账号',
            'password' => '密码',
            'realname' => '真名',
            'login_time' => '最后登录时间',
            'power' => '权力值',
            'state' => '状态',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
            'updated_at' => 'Updated At',
            'updated_by' => 'Updated By',
        ];
    }

    /****************************** 以下为设置关联模型的方法 ******************************/

    public function getRoles()
    {
        return $this->hasMany(\common\modules\rbac\models\AuthAssignment::className(), ['user_id' => 'id']);
    }

    /****************************** 以下为公共显示条件的方法 ******************************/

    public function search()
    {
        $this->setSearchParams();

        return self::find()
            ->filterWhere([
                'adminUser.id' => $this->id,
                'adminUser.power' => $this->power,
                'adminUser.state' => $this->state,
                'adminUser.created_by' => $this->created_by,
                'adminUser.updated_by' => $this->updated_by,
            ])
            ->andFilterWhere(['like', 'adminUser.username', $this->username])
            ->andFilterWhere(['like', 'adminUser.password', $this->password])
            ->andFilterWhere(['like', 'adminUser.realname', $this->realname])
            ->andFilterWhere(['like', 'adminUser.login_time', $this->login_time])
            ->andFilterWhere(['like', 'adminUser.created_at', $this->created_at])
            ->andFilterWhere(['like', 'adminUser.updated_at', $this->updated_at])
            ->andTableSearch()
        ;
    }

    /****************************** 以下为公共操作的方法 ******************************/

    public function hashPassword()
    {
        $this->password = Yii::$app->security->generatePasswordHash($this->password);

        return $this;
    }

    public function saveAdmin()
    {
        if ($this->isNewRecord) {
            $this->power = u()->power - 1;
            $user = new \common\models\User;
            $user->username = $this->username;
            $user->password = $this->password;
            $user->hashPassword();
            if ($user->insert()) {
                $this->id = $user->id;
            } else {
                $this->addError('username', $this->label('username') . "的值\"{$user->username}\"已经被占用了。");
                return false;
            }
        } elseif (!$this->password) {
            $hashed = true;
            $this->password = $this->tmpPassword;
        }

        if ($this->validate()) {
            $auth = Yii::$app->authManager;
            empty($hashed) && $this->hashPassword();
            $this->save(false);
            $roles = post('AuthItem', ['roles' => []]);
            $roles = $roles['roles'] ?: [];
            list($add, $remove) = ArrayHelper::diff(static::roles($this->id), $roles);
            foreach ($add as $roleName) {
                $role = $auth->getRole($roleName);
                $auth->assign($role, $this->id);
            }
            foreach ($remove as $roleName) {
                $role = $auth->getRole($roleName);
                $auth->revoke($role, $this->id);
            }
            return true;
        } else {
            return false;
        }
    }

    protected static $_roles;
    public static function roles($uid)
    {
        if (!$uid) {
            return [];
        } elseif (self::$_roles === null) {
            $user = static::find()->with('roles.item')->andWhere(['id' => $uid])->one();
            self::$_roles = [];
            foreach ($user->roles as $role) {
                if ($role['item']['description'] === AuthItem::getCurrentAppId()) {
                    self::$_roles[] = $role['item_name'];
                }
            }
        }
        return self::$_roles;
    }

    /****************************** 以下为字段的映射方法和格式化方法 ******************************/
}
