<?php common\assets\LayerAsset::register($this) ?>
<script src="//nos.netease.com/vod163/nePublisher.min.js"></script>

<div id="my-publisher"></div>

<script>
$(function () {
    var myPublisher = new nePublisher(
        'my-publisher', // String 必选 推流器容器id
        {   // Object 可选 推流初始化videoOptions参数
            videoWidth: 640,    // Number 可选 视频宽度 default 640
            videoHeight: 480,   // Number 可选 视频高度 default 480
            fps: 15,            // Number 可选 帧率 default 15
            bitrate: 600,       // Number 可选 码率 default 600
        }, { // Object 可选 推流初始化flashOptions参数
            previewWindowWidth: 862,    // Number 可选 推流器宽度 default 862
            previewWindowHeight: 486,   // Number 可选 推流器高度 default 446
            wmode: 'transparent',       // String 可选 flash显示模式 default transparent
            quality: 'high',            // String 可选 flash质量 default high
            allowScriptAccess: 'always' // String 可选 flash跨域允许 default always
        }, function() {
            /*
                function 可选 初始化成功的回调函数
                可在该回调中调用getCameraList和getMicroPhoneList方法获取 摄像头和麦克风列表
                cameraList = this.getCameraList();
                microPhoneList = this.getMicroPhoneList();
            */
            // cameraList = this.getCameraList();
            // microPhoneList = this.getMicroPhoneList();
            // myPublisher.setCamera(cameraList[0]);
            // myPublisher.setMicroPhone(microPhoneList[0]);
            // $.alert(cameraList);
            $.alert('ok');
        }, function(code, desc) {
            $.alert(desc);
            /*
                function 可选 初始化失败后的回调函数
                @param code 错误代码
                @param desc 错误信息
                判断是否有错误代码传入检查初始化时是否发生错误
                若有错误可进行相应的错误提示
            */
    });

    myPublisher.startPublish(
        'rtmp://pdl867f6d9d.live.126.net/live/0273b06b4c5d4c7489f73641a8d3fd54?wsSecret=bf48b2cc3dd5164e296cbad3246ef777&wsTime=1481701406', // String 必选 要推流的频道地址
        { // Object 可选 推流参数
            videoWidth: 640,    // Number 可选 推流视频宽度 default 640
            videoHeight: 480,   // Number 可选 推流视频高度 default 480
            fps: 15,            // Number 可选 推流帧率 default 15
            bitrate: 600,       // Number 可选 推流码率 default 600
        }, function(code, desc) {
            $.alert(desc);
            /*
                function 可选 推流过程中发生错误进行回调
                @param code 错误代码
                @param desc 错误信息
                判断是否有错误代码传入推流过程中是否发生错误
                若有错误可进行相应的错误提示
            */
    });
});
</script>