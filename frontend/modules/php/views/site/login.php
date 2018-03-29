<?php $this->regCss(['common', 'register']) ?>
<?php $this->title = t('Login') ?>

<div class="content">
    <div class="content-wrap">
        <span><?= t('Login') ?></span>
        <?php $form = self::beginForm() ?>
        <?= $form->field($model, 'username')->textInput(['placeholder' => t('Please input an email.')]) ?>
        <?= $form->field($model, 'password')->passwordInput(['placeholder' => t('Please input password.')]) ?>
        <?= $form->field($model, 'rememberMe')->hiddenInput(['value' => 1]) ?>
        <div>
            <input id="loginBtn" type="submit" value="<?= t('Login') ?>">
        </div>
        <?php self::endForm() ?>
    </div>
</div>