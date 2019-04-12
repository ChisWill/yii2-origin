<?php $this->regCss(['common', 'encrypt']) ?>
<?php $this->title = 'PHP ' . t('Encrypt') ?>

<?php $form = self::beginForm(['showLabel' => true, 'id' => 'submitForm']) ?>
<?php $items = [1 => t('Obfuscate'), 0 => t('Not Obfuscate')] ?>
<div class="content clear-fix font-20">
    <div class="content-left fl">
        <ul>
            <li><?= $form->field($obfuscate, 'obfuscate_variable_name')->radioList($items) ?></li>
            <li><?= $form->field($obfuscate, 'obfuscate_string_literal')->radioList($items) ?></li>
            <li><?= $form->field($obfuscate, 'obfuscate_constant_name')->radioList($items) ?></li>
            <li><?= $form->field($obfuscate, 'obfuscate_function_name')->radioList($items) ?></li>
            <li><?= $form->field($obfuscate, 'obfuscate_class_name')->radioList($items) ?></li>
            <li><?= $form->field($obfuscate, 'obfuscate_interface_name')->radioList($items) ?></li>
            <li><?= $form->field($obfuscate, 'obfuscate_trait_name')->radioList($items) ?></li>
            <li><?= $form->field($obfuscate, 'obfuscate_class_constant_name')->radioList($items) ?></li>
            <li><?= $form->field($obfuscate, 'obfuscate_property_name')->radioList($items) ?></li>
            <li><?= $form->field($obfuscate, 'obfuscate_method_name')->radioList($items) ?></li>
            <li><?= $form->field($obfuscate, 'obfuscate_namespace_name')->radioList($items) ?></li>
        </ul>
    </div>
    <div class="content-right fr text-center">
        <ul>
            <li><?= $form->field($obfuscate, 't_ignore_constants')->textarea($items) ?></li>
            <li><?= $form->field($obfuscate, 't_ignore_functions')->textarea($items) ?></li>
            <li><?= $form->field($obfuscate, 't_ignore_classes')->textarea($items) ?></li>
            <li><?= $form->field($obfuscate, 't_ignore_namespaces')->textarea($items) ?></li>
            <li><?= $form->field($obfuscate, 'user_comment')->textarea($items) ?></li>
        </ul>   
    </div>
</div>

<div class="content-bottom text-center">
    <div>
        <?= $form->field($obfuscate, 'file')->upload() ?>
    </div>

    <div class="font-14 color-gray">请上传小于2M的php、zip文件</div>
    <?= $form->submit(t('Encrypt'), ['id' => 'encryptBtn']) ?>
</div>
<?php self::endForm() ?>

<?php self::beginForm(['id' => 'autoForm', 'action' => url(['obfuscate'])]) ?>
<?php self::endForm() ?>

<script>
$(function () {
    $("#encryptBtn").click(function () {
        var $this = $(this);
        if ($this.hasClass('actived')) {
            return false;
        }
        $this.addClass('actived');
        $("#submitForm").ajaxSubmit($.config('ajaxSubmit', {
            success: function (msg) {
                if (msg.state) {
                    $("#autoForm").ajaxSubmit($.config('ajaxSubmit', {
                        success: function (msg) {
                            if (msg.state) {
                                $a = $("<a href='<?= url(['encrypt/download']) ?>'></a>");
                                $("body").append($a);
                                $a[0].click();
                                $a.remove();
                            } else {
                                $.alert(msg.info);
                            }
                            $this.removeClass('actived');
                        }
                    }));
                } else {
                    $.alert(msg.info);
                    $this.removeClass('actived');
                }
            }
        }));
        return false;
    });
});
</script>