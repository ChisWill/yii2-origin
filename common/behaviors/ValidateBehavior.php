<?php

namespace common\behaviors;

use Yii;

/**
 * 验证器行为类
 *
 * @author ChisWill
 */
class ValidateBehavior extends \yii\base\Behavior
{
    /**
     * 仅检查验证码是否正确
     * 
     * @param  string $value 验证码
     * @return boolean
     */
    public function glanceCaptcha($value)
    {
        $captcha = $this->owner->createCaptchaAction();
        $code = $captcha->getVerifyCode();
        return $this->owner->caseSensitive ? ($value === $code) : strcasecmp($value, $code) === 0;
    }
}
