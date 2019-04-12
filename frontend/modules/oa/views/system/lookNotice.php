<style type="text/css">
    .notice-box img {
        max-width: 100%;
    }
    .notice-box .download {
        padding: 20px 0px;
    }
    .notice-box .download span {
        font-size: 18px;
        font-weight: bold;
    }
    .notice-box .download a {
        font-size: 18px;
        color: #27c0b8;
        font-weight: bold;
    }
    .notice-box .download a:hover {
        text-decoration: underline;
    }
</style>
<h2 style="text-align: center;"><?= $model->title ?></h2>
<div class="notice-box">
    <?= $model->content ?>
    <?php if ($model->attach): ?>
    <div class="download">
        <span>附件：</span>   
        <a href="<?= $model->attach ?>" download=""><?= $model->origin_name ?></a>
    </div>
    <?php endif ?>
</div>