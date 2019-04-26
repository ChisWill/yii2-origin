<?php \common\assets\LayerUiAsset::register($this) ?>

<fieldset class="layui-elem-field layui-field-title">
    <legend>常规使用：任意单个文件上传</legend>
</fieldset>

<style type="text/css">
.layui-upload-img {
    width: 90px;
    height: 90px;
    margin: 0 10px 10px 0;
}
</style>

<div class="layui-upload">
    <!-- 最简易的使用场景，只需要`type="file"`框就行，必须要有name属性 -->
    <input type="file" name="file" id="inputFile">
</div>

<fieldset class="layui-elem-field layui-field-title">
    <legend>对多个上传按钮绑定事件</legend>
</fieldset>

<div class="layui-row" style="margin-bottom: 25px;">
    <div class="layui-col-xs3">
        选择图片
    </div>
    <div class="layui-col-xs3">
        图片预览
    </div>
    <div class="layui-col-xs3">
        进度条
    </div>
    <div class="layui-col-xs3" align="center">
        结果显示
    </div>
</div>
<div class="layui-row">
    <div class="layui-col-xs3">
        <!-- 上传按钮 -->
        <input type="file" class="inputFile" name="file">
    </div>
    <div class="layui-col-xs3">
        <!-- 预览图标签 -->
        <img class="layui-upload-img previewImg">
    </div>
    <div class="layui-col-xs3">
        <!-- 进度条代码，实际项目可自行设计进度条样式 -->
        <div class="layui-progress" lay-showpercent="true">
            <div class="layui-progress-bar" style="width: 0%">
                <span class="layui-progress-text">0%</span>
            </div>
        </div>
    </div>
    <div class="layui-col-xs3" style="text-align: center">
        <p style="color: red"></p>
    </div>
</div>

<div class="layui-row">
    <div class="layui-col-xs3">
        <input type="file" class="inputFile" name="file">
    </div>
    <div class="layui-col-xs3">
        <img class="layui-upload-img previewImg">
    </div>
    <div class="layui-col-xs3">
        <div class="layui-progress" lay-showpercent="true">
            <div class="layui-progress-bar" style="width: 0%">
                <span class="layui-progress-text">0%</span>
            </div>
        </div>
    </div>
    <div class="layui-col-xs3" style="text-align: center">
        <p style="color: red"></p>
    </div>
</div>

<fieldset class="layui-elem-field layui-field-title">
    <legend>对多个表单绑定事件</legend>
</fieldset>

<?php $form = self::beginForm(['class' => 'testForm', 'id' => 'testForm1']) ?>
<caption>表单1</caption>
<div class="layui-form-item">
    <label class="layui-form-label">标题</label>
    <div class="layui-input-block">
        <input type="text" name="title" lay-verify="title" autocomplete="off" placeholder="请输入标题" class="layui-input">
    </div>
</div>
<div class="layui-form-item">
    <label class="layui-form-label">图片1上传</label>
    <div class="layui-input-block">
        <!-- 表单中存在多个需要上传的文件时，name 属性后面必须加上 [] -->
        <input type="file" name="file[]" lay-verify="title" class="layui-btn layui-btn-normal" style="line-height: 0px; padding: 6px 10px;">
    </div>
</div>
<div class="layui-form-item">
    <label class="layui-form-label">图片2上传</label>
    <div class="layui-input-block">
        <!-- 除了 type 和 name 属性外，其他属性只是为了样式效果，都可以去除 -->
        <input type="file" name="file[]" lay-verify="title" class="layui-btn layui-btn-normal" style="line-height: 0px; padding: 6px 10px;">
    </div>
</div>
<div class="layui-form-item">
    <label class="layui-form-label">图片预览</label>
    <div class="layui-input-label">
        <img class="layui-upload-img previewImg">
    </div>
</div>

<div class="layui-form-item">
    <div class="layui-input-block">
        <input type="submit" class="layui-btn" lay-submit="" lay-filter="demo1" value="立即提交">
        <button type="reset" class="layui-btn layui-btn-primary">重置</button>
    </div>
</div>

<div class="layui-progress" lay-showpercent="true">
    <div class="layui-progress-bar" style="width: 0%">
        <span class="layui-progress-text">0%</span>
    </div>
</div>
<?php self::endForm() ?>

<?php $form = self::beginForm(['class' => 'testForm', 'id' => 'testForm2']) ?>
<caption>表单2</caption>
<div class="layui-form-item">
    <label class="layui-form-label">标题</label>
    <div class="layui-input-block">
        <input type="text" name="title" lay-verify="title" autocomplete="off" placeholder="请输入标题" class="layui-input">
    </div>
</div>
<div class="layui-form-item">
    <label class="layui-form-label">图片上传</label>
    <div class="layui-input-block">
        <input type="file" name="file" lay-verify="title" class="layui-btn layui-btn-normal" style="line-height: 0px; padding: 6px 10px;">
    </div>
</div>
<div class="layui-form-item">
    <label class="layui-form-label">图片预览</label>
    <div class="layui-input-label">
        <img class="layui-upload-img previewImg">
    </div>
</div>

<div class="layui-form-item">
    <div class="layui-input-block">
        <input type="submit" class="layui-btn" lay-submit="" lay-filter="demo1" value="立即提交">
        <button type="reset" class="layui-btn layui-btn-primary">重置</button>
    </div>
</div>

<div class="layui-progress" lay-showpercent="true">
    <div class="layui-progress-bar" style="width: 0%">
        <span class="layui-progress-text">0%</span>
    </div>
</div>
<?php self::endForm() ?>

<script>
$(function() {
    // 常规使用：任意单个文件上传
    $("#inputFile").uploadFile(function (msg) { // 在最简易的情形下，只需要提供一个回调方法作为参数，处理AJAX上传成功后的事件
        if (msg.state) {
            // 图片路径为也是图片的网络地址，可直接使用在<img>标签的 src 属性中
            $.alert('上传成功：图片路径为：' + msg.info);
        } else {
            $.alert(msg.info);
        }
    });
    // 同时对多个上传按钮绑定事件，在这个例子中，使用了全部四个参数，分别表示提交地址、额外提交的数据、回调方法、其他参数配置。参数顺序与`$.post()`方法保持一致
    $(".inputFile").uploadFile('<?= url(['advance/uploadFile']) ?>', {title: '文件上传'}, function (msg, self) {
        if (msg.state) {
            $(self).parents('.layui-row').find('p').html('上传成功：图片路径为：' + msg.info);
        } else {
            $.alert(msg.info);
        }
    }, {
        // 绑定“上传前”事件，一般用于校验文件
        before: function (file) {
            if (file.files[0].size > 200 * 1024) {
                $.alert('上传文件不得大于200k');
                return false;
            } else {
                return true;
            }
        },
        // 预览图片的回调事件。src 是文件的base64数据，如果上传的不是图片，则不会调用以下方法；self 表示当前点击的上传框的DOM对象
        preview: function (src, self) {
            // 此处仅做预览效果演示，实际使用中，应设计更好的结构，写出更优雅的代码
            $(self).parents('.layui-row').find('.previewImg').attr('src', src);
        },
        // 进度条的回调事件。percent 是当前进度的百分比数字；self 同上
        progress: function (percent, self) {
            // 如果不需要显示进度文本，可省略本行
            $(self).parents('.layui-row').find('.layui-progress-text').html(percent + '%');
            // 注意，此处是设置宽度的百分比，后面必须加上 % 号
            $(self).parents('.layui-row').find('.layui-progress-bar').css('width', percent + '%');
        }
    });
    // 对多个表单绑定事件，一般可省略前两个参数，提交地址使用表单的 action 属性，提交内容则是表单内容
    $(".testForm").uploadFile(function (msg) {
        if (msg.state) {
            $.alert('表单提交成功');
        } else {
            $.alert(msg.info);
        }
    }, {
        preview: function (src, self) {
            $(self).parents('.testForm').find('.previewImg').attr('src', src);
        },
        // 当对表单绑定事件时，此处的 self 不再是上传按钮，而是当前表单的 DOM 对象
        progress: function (percent, self) {
            $(self).find('.layui-progress-text').html(percent + '%');
            $(self).find('.layui-progress-bar').css('width', percent + '%');
        },
        // 绑定表单的“提交前”事件，一般用来进行表单验证操作
        before: function (form) {
            // 这里仅做基本示范，当表单中存在多个上传按钮时，以下代码只能判断第一个上传按钮是否有选择文件
            if ($(form).find('[type="file"]').val() == '') {
                $.alert('请先选择文件');
                return false; // 阻止表单继续提交
            } else {
                return true; // 必须`return true`才能继续提交
            }
        }
    });
});
</script>