<?php

namespace api\components;

use Yii;

class WebUser extends \common\components\Identity implements \yii\filters\RateLimitInterface
{
    /**
     * 指定用户表
     */
    public static function tableName()
    {
        return '{{%api_app}}';
    }

    public function getRateLimit($request, $action)
    {
        if ($this->rate_limit >= 1) {
            return [$this->rate_limit, 1];
        } else {
            return [1, (int) (1 / $this->rate_limit)];
        }
    }

    public function loadAllowance($request, $action)
    {
        return [$this->allowance, $this->allowance_updated_at];
    }

    public function saveAllowance($request, $action, $allowance, $timestamp)
    {
        $this->allowance = $allowance;
        $this->allowance_updated_at = $timestamp;
        $this->rest -= 1;
        $this->save();
    }

    /**
     * 获取该认证实例表示的用户的ID。
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     * 获取基于 cookie 登录时使用的认证密钥。 认证密钥储存在 cookie 里并且将来会与服务端的版本进行比较以确保 cookie的有效性。
     */
    public function getAuthKey()
    {
        return '';
    }

    /**
     * 根据指定的用户ID查找 认证模型类的实例，当你需要使用session来维持登录状态的时候会用到这个方法。
     */
    public static function findIdentity($id)
    {
        return static::find()->select(['id', 'username'])->where('id = :id', [':id' => $id])->one();
    }

    /**
     * 根据指定的存取令牌查找 认证模型类的实例，该方法用于 通过单个加密令牌认证用户的时候（比如无状态的RESTful应用）。
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        return static::find()->where(['key' => $token])->andWhere(['state' => \common\components\ARModel::STATE_VALID])->one();
    }

    /**
     * 是基于 cookie 登录密钥的 验证的逻辑的实现。
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        return static::findOne(['username' => $username]);
    }
}
