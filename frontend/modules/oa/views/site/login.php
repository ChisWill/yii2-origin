<?php use common\helpers\Hui; ?>
<?php admin\assets\LoginAsset::register($this) ?>
<div class="login-wraper">
    <div id="loginform" class="login-box">
    <?php $form = self::beginForm(['class' => ['form', 'form-horizontal']]) ?>
        <div class="login-top clearfix">
            <div class="admin-img">
                <img src="<?= config('web_logo') ? :  '/images/default-img.png' ?>">
            </div>
            <div class="admin-name">后台管理系统</div>
        </div>
        <img class="login-line" src="/images/line.png">
        <?= $form->field($model, 'username')->textInput(['placeholder' => $model->label('username'), 'class' => ['input-text', 'size-L']])->label('<i class="Hui-iconfont">&#xe60d;</i>', ['class' => ['form-label', 'col-xs-3']]) ?>
        <?= $form->field($model, 'password')->passwordInput(['placeholder' => $model->label('password'), 'class' => ['input-text', 'size-L']])->label('<i class="Hui-iconfont">&#xe60e;</i>', ['class' => ['form-label', 'col-xs-3']]) ?>
        <?= $form->field($model, 'captcha', ['template' => "<div class='formControls captcha-row  col-xs-12'>{input}</div>\n{hint}\n{error}"])->captcha(['options' => ['placeholder' => '验证码', 'style' => ['width' => '150px'], 'class' => ['input-text', 'size-L']]])->label('<i class="Hui-iconfont">&#xe63f;</i>', ['class' => ['form-label', 'col-xs-3']]) ?>
        <?= $form->field($model, 'rememberMe', ['template' => "<div class='formControls col-xs-12 col-xs-offset-3'>{input}</div>\n{hint}\n{error}"])->checkbox(['label' => '记住我']) ?>
        <?= $form->submit('登 录', ['class' => ['size-L', 'mb-20', 'login-btn']]) ?>
    <?php self::endForm() ?>
    </div>
</div>
<!-- <div class="footer"><a href="http://www.miitbeian.gov.cn" target="_blank"><?= config('web_copyright') ?></a></div> -->
<script>
$(function () {
    // 首次登陆隐藏验证码，登陆失败后才出现
    ;!function () {
        if ('<?= session('requireCaptcha') ?>') {
            $("#loginform-captcha").parents('.row').show();
        } else {
            $("#loginform-captcha").parents('.row').hide();
        }
    }();
    
    $("#submitBtn").click(function () {
        $("form").ajaxSubmit($.config('ajaxSubmit', {
            success: function (msg) {
                if (msg.state) {
                    window.location.href = msg.info;
                } else {
                    $.alert(msg.info, function () {
                        $("#loginform-captcha").parents('.row').show();
                    });
                }
            }
        }));
        return false;
    });
});
</script>