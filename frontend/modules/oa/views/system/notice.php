<?php common\assets\ICheckAsset::register($this) ?>
<style type="text/css">
    .fieldset-aside {
        padding: 10px 15px;
    }
    .fieldset-row {
        width: 100%;
        margin-bottom: 10px;
    }
    .fieldset-row label {
        width: 100px;
        line-height: 30px;
    }
    .fieldset-row .input-text {
        width: calc(100% - 100px);
    }
    .fieldset-row .position-list label {
        margin-right: 15px;
    }
    .fieldset-row .position-list span {
        display: inline-block;
        margin-left: 5px;
    }
    .fieldset-aside .submit-input {
        margin: 0 auto;
    }
    .operate-btn {
        width: 100%;
        text-align: center;
    }
    .operate-btn .submit-btn {
        width: 100px;
    }
</style>
<?php if (u()->can('system/saveNotice')): ?>
<?php $form = self::beginForm(['id' => 'noticeForm']) ?>
<fieldset class="fieldset-box">
    <legend>群发消息</legend>
    <div class="fieldset-aside">
        <div class="fieldset-row clearfix">
            <label class="fl">消息内容：</label>
            <input class="input-text fl" type="text" palceholder="消息内容" name="content" />
        </div>
        <div class="fieldset-row clearfix">
            <label class="fl">通知群组：</label>
            <div class="position-list fl">
                <?php foreach ($map as $key => $value): ?>
                    <label><input type="checkbox" name="position[]" value="<?= $key ?>"><span><?= $value ?></span></label>
                <?php endforeach ?>
            </div>
        </div>
        <div class="operate-btn">
            <input type="submit" class="submit-btn submit-input btn-warning size-M btn radius" value="发送" id="noticeBtn" />
        </div>
    </div>
</fieldset>
<?php self::endForm() ?>
<?php endif ?>

<fieldset class="fieldset-box">
    <legend>公告列表</legend>
    <div class="fieldset-aside">
        <?= $html ?>
    </div>
</fieldset>
<script>
$(function () {
    $("#noticeBtn").click(function () {
        $("#noticeForm").ajaxSubmit($.config('ajaxSubmit', {
            success: function (msg) {
                if (msg.state) {
                    $.alert(msg.info || '群发成功');
                } else {
                    $.alert(msg.info);
                }
            }
        }));
        return false;
    });
});
</script>