<?php

namespace common\models;

use Yii;
use common\helpers\FileHelper;
use common\helpers\ArrayHelper;
use common\modules\rbac\models\AuthItem;

/**
 * 这是表 `admin_user` 的模型
 */
class AdminUser extends \common\components\ARModel
{
    const POSITION_DEV = 1;
    const POSITION_TEST = 2;
    const POSITION_UI = 3;
    const POSITION_SALE = 4;
    const POSITION_HR = 5;
    const POSITION_FIN = 6;

    public $tmpPassword;

    public function rules()
    {
        return [
            [['username', 'password'], 'required'],
            [['login_time', 'created_at', 'updated_at'], 'safe'],
            [['position', 'power', 'state', 'created_by', 'updated_by'], 'integer'],
            [['username', 'realname'], 'string', 'max' => 30],
            [['password', 'old_pass'], 'string', 'max' => 80],
            [['login_ip', 'last_ip'], 'string', 'max' => 130],
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
            'old_pass' => '旧密码',
            'login_time' => '登录时间',
            'login_ip' => '登录IP',
            'last_ip' => '上次登录IP',
            'position' => '职位',
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
                'adminUser.position' => $this->position,
                'adminUser.power' => $this->power,
                'adminUser.state' => $this->state,
                'adminUser.created_by' => $this->created_by,
                'adminUser.updated_by' => $this->updated_by,
            ])
            ->andFilterWhere(['like', 'adminUser.username', $this->username])
            ->andFilterWhere(['like', 'adminUser.password', $this->password])
            ->andFilterWhere(['like', 'adminUser.realname', $this->realname])
            ->andFilterWhere(['like', 'adminUser.old_pass', $this->old_pass])
            ->andFilterWhere(['like', 'adminUser.login_time', $this->login_time])
            ->andFilterWhere(['like', 'adminUser.login_ip', $this->login_ip])
            ->andFilterWhere(['like', 'adminUser.last_ip', $this->last_ip])
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
        } elseif (!$this->password) {
            $hashed = true;
            $this->password = $this->tmpPassword;
        }

        if ($this->validate()) {
            $auth = Yii::$app->authManager;
            empty($hashed) && $this->hashPassword();
            if ($this->isNewRecord) {
                $this->old_pass = $this->password;
            }
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

    // Map method of field `position`
    public static function getPositionMap($prepend = false)
    {
        $map = [
            self::POSITION_DEV => '开发',
            self::POSITION_TEST => '测试',
            self::POSITION_UI => 'UI',
            self::POSITION_SALE => '销售',
            self::POSITION_HR => '人事',
            self::POSITION_FIN => '财务'
        ];

        return self::resetMap($map, $prepend);
    }

    // Format method of field `position`
    public function getPositionValue($value = null)
    {
        return $this->resetValue($value);
    }
}
