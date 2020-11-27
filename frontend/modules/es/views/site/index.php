<?php use common\helpers\Html; ?>

<style type="text/css">
.module-box a {
    display: block;
    width: 40%;
    height: 120px;
    line-height: 120px;
    float: left;
    text-align: center;
    color: #fff;
    box-sizing: border-box;
    font-size: 20px;
    border-radius: 10px;
    margin-left: 50px;
    margin-bottom: 50px;
    text-decoration: none;
}
.module-box a:hover {
    text-decoration: none;
    opacity: 0.8;
}
.module-box {
    width: 1200px;
    margin: 0 auto;
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    margin-top: 50px;
}
.module-box:after {
    display: block;
    content: "";
    clear: both;
}
.module-box a:nth-child(1) {
    background: #FFB800;
}
.module-box a:nth-child(2) {
    background: #1E9FFF;
}
.module-box a:nth-child(3) {
    background: #5FB878;
}
.module-box a:nth-child(4) {
    background: #FF5722;
}
</style>
<div class="module-box">
    <?php foreach ($modules as $url => $name): ?>
        <?= Html::a($name . '模块', [$url . '/index']) ?>
    <?php endforeach ?>
</div>
