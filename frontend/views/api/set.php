<?php $form = self::beginForm() ?>
<h1>app上线控制</h1>
<input type="text" name="url1" placeholder="url1" style="width: 250px;"><br>
<input type="text" name="jump1" placeholder="jump1,填写0表示不上线，非0表示上线" style="width: 250px;"><br>
<input type="text" name="url2" placeholder="url2" style="width: 250px;"><br>
<input type="text" name="jump2" placeholder="jump2,填写0表示不上线，非0表示上线" style="width: 250px;"><br>
<input type="submit" id="submitBtn">
<?php self::endForm() ?>

<script>
$(function () {
    $("#submitBtn").click(function () {
        $("form").ajaxSubmit($.config('ajaxSubmit', {
            success: function (msg) {
                if (msg.state) {
                    $.alert(msg.info || '操作成功', function () {
                        parent.location.reload();
                    });
                } else {
                    $.alert(msg.info);
                }
            }
        }));
        return false;
    });
});
</script>