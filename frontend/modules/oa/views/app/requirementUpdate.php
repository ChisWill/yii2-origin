<?= $this->render('requirementView', compact('title', 'info')) ?>
<style>
.progress, .progress-bar, .sr-only {
    height: 5px;
    font-size: 0px;
    line-height: 0;
}
</style>
<?php $form = self::beginForm() ?>
<div class="row">
    <label class="form-label col-sm-2">
        上传需求文档
    </label>
    <div class="formControls col-sm-9">
        <input type="file" name="file" class="input-text" style="padding: 2px;">
    </div>
</div>
<div class="progress radius" style="width: 100%; visibility: hidden;"><div class="progress-bar"><span class="sr-only"></span></div></div>
<?= $form->submit('上传') ?>
<?php self::endForm() ?>
<script>
$(function () {
    $("form").uploadFile(function (msg) {
        if (msg.state) {
            $.alert('上传成功', function () {
                parent.location.reload();
            });
        } else {
            $.alert(msg.info);
        }
    }, {
        progress: function (percent, form) {
            $(form).find('.progress .sr-only').css('width', percent + '%');
            if (percent >= 100) {
                $(form).find('.progress').css('visibility', 'hidden');
            }
        },
        before: function (form) {
            $(form).find('.progress').css('visibility', 'visible');
            return true;
        }
    });
});
</script>