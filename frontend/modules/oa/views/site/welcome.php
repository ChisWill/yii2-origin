<?php use oa\models\OaNotice; ?>
<style type="text/css">
    .notice-box {
        width: 100%;
    }
    .notice-left {
        width: 850px;
        background: url('/images/notice-box.png') center center no-repeat;
        background-size: 100% 100%;
        margin: 0 auto;
        box-shadow: 0px 0px 20px 0px rgba(34, 23, 20, 0.18);
        position: relative;
    }
    .notice-left .title {
        text-align: center;
        font-size: 30px;
        margin-bottom: 20px;
        margin-top: -50px;
    }
    .notice-left .content {
        min-height: 200px;
    }
    .notice-left .content, .notice-left .download {
        width: 530px;
        margin: 0 auto;
    }
    .notice-left .download span {
        font-size: 18px;
        font-weight: bold;
    }
    .notice-left .download a {
        font-size: 18px;
        color: #27c0b8;
        font-weight: bold;
    }
    .notice-left .download a:hover {
        text-decoration: underline;
    }
    .notice-left .content img {
        max-width: 100%;
    }
    .notice-left .realname, .notice-left .time {
        text-align: right;
        position: absolute;
    }
    .notice-left .realname {
        color: #27c0b8;
        font-weight: bold;
        margin-bottom: 5px;
        bottom: 158px;
        right: 150px;
        font-size: 18px;
    }
    .notice-left .time {
        bottom: 128px;
        font-weight: bold;
        font-size: 16px;
        right: 150px;
    }
    .notice-left .notice-img {
        width: 100%;
    }
    @media screen and (max-width: 640px) {
        .notice-left {
            width: 100%;
            margin: 0 auto;
        }
        .notice-left .content, .notice-left .download {
            width: 80%;
            margin-left: 10%;
        }
        .notice-left .title {
            margin-top: -20px;
        }
        .notice-left .realname, .notice-left .time {
            position: static;
            text-align: center;
            font-size: 16px;
        }
        .notice-left .realname {
            margin-top: -90px;
        }
    }
</style>
<div class="notice-box">
    <div class="notice-left">
        <?php foreach (OaNotice::getNoticeQuery()->limit(1)->all() as $notice): ?>
            <img class="notice-img" src="/images/notice-top.png">
            <div class="title"><?= $notice['title'] ?></div>
            <div class="content"><?= $notice['content'] ?></div>
            <?php if ($notice['attach']): ?>
                <div class="download">
                    <span>附件：</span>   
                    <a href="<?= url(['system/downloadAttach', 'id' => $notice['id']]) ?>"><?= $notice['origin_name'] ?></a>
                </div>
            <?php endif ?>
            <img class="notice-img" src="/images/notice-bottom.png">
            <div class="realname">发布人：<?= $notice['adminUser']['realname'] ?></div>
            <div class="time"><?= $notice['created_at'] ?></div>
        <?php endforeach ?>
    </div>
</div>