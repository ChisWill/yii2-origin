fwef

<script>
    $(function() {
        wx.config({
            debug: true, // 开启调试模式,调用的所有api的返回值会在客户端alert出来，若要查看传入的参数，可以在pc端打开，参数信息会通过log打出，仅在pc端时才会打印。
            appId: '<?= $configParams['appId'] ?>', // 必填，公众号的唯一标识
            timestamp: '<?= $configParams['timestamp'] ?>', // 必填，生成签名的时间戳
            nonceStr: '<?= $configParams['noncestr'] ?>', // 必填，生成签名的随机串
            signature: '<?= $configParams['signature'] ?>', // 必填，签名
            jsApiList: ['openLocation'] // 必填，需要使用的JS接口列表
        });
        wx.ready(function() {
            wx.openLocation({
                name: '共康公寓', // 位置名
                latitude: 31.0938140000, //要去的纬度-地址
                longitude: 121.5039390000, //要去的经度-地址
                address: '上海市宝山区三泉路', // 地址详情说明
                scale: 1, // 地图缩放级别,整型值,范围从1~28。默认为最大
                infoUrl: '' // 在查看位置界面底部显示的超链接,可点击跳转
            });
        });
    })
</script>