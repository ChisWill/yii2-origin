<?php use common\helpers\ArrayHelper; ?>
<?php common\assets\EChartsAsset::register($this) ?>
<?= $this->regCss(['trace']) ?>

<div class="real-table" style="padding-top: 0px;">
    <div class="table-name">访客信息统计</div>
</div>
<?php
$search = get('search', []);
$start = ArrayHelper::getValue($search, 'start_date');
$end = ArrayHelper::getValue($search, 'end_date');
?>
<div class="list-container pl-20">
    <form class="search-form">
        <ul>
            <li>
                <input type="text" class="input-text chart-startdate" name="search[start_date]" placeholder="开始日期" style="width: 45%" value="<?= $start ?>">&nbsp;-&nbsp;<input type="text" class="input-text chart-enddate" name="search[end_date]" placeholder="截止日期" style="width: 45%" value="<?= $end ?>">
            </li>
            <input type="submit" class="submit-input btn-success size-M btn radius" value="搜索">
        </ul>
    </form>
</div>

<div id="chartContainer" style="height: 400px;"></div>
<div class="trace-list">
    <div class="trace-item clearfix">
        <div class="fl trace-icon"><img src="/images/trace-icon1.png"></div>
        <div class="fl trace-text">
            <i><?= $visitUsers ?></i>
            <span>页面访问人数</span>
        </div>
    </div>
    <div class="trace-item clearfix">
        <div class="fl trace-icon"><img src="/images/trace-icon2.png"></div>
        <div class="fl trace-text">
            <i><?= $visitNumbers ?></i>
            <span>浏览量</span>
        </div>
    </div>
    <div class="trace-item clearfix">
        <div class="fl trace-icon"><img src="/images/trace-icon3.png"></div>
        <div class="fl trace-text">
            <i><?= round($avgDuration, 2) ?> 秒</i>
            <span>平均停留时间</span>
        </div>
    </div>
    <div class="trace-item clearfix">
        <div class="fl trace-icon"><img src="/images/trace-icon4.png"></div>
        <div class="fl trace-text">
            <i><?= $avgVisitNumbers ?></i>
            <span>人均浏览量</span>
        </div>
    </div>
    <div class="trace-item clearfix">
        <div class="fl trace-icon"><img src="/images/trace-icon5.png"></div>
        <div class="fl trace-text">
            <i><?= round($missRate * 100, 2) ?> %</i>
            <span>跳失率</span>
        </div>
    </div>
</div>
<div class="real-table">
    <div class="table-name mb-20">实时访问记录</div>
    <?= $realtimeVisitHtml ?>
</div>
<div class="page-table">
    <div class="table-name">页面访问排行</div>
    <?= $pageRankHtml ?>
</div>

<script>
$(function () {
    // 图表绘制
    var chart = echarts.init(document.getElementById('chartContainer'));
    var data = JSON.parse('<?= json_encode($visitData) ?>');
    var x = [], y = [];
    for (var k in data) {
        x.push(data[k]['created_at']);
        y.push(data[k]['count']);
    }
    option = {
        grid: {
            left: '2%',
            right:'4.5%',
            top:'14%',
            bottom: '5%',
            containLabel: true
        },
        color: ['#35c9bb'],
        xAxis: {
            name:'时间',
            type: 'category',
            data: x
        },
        yAxis: {
            name:'访问人数',
            type: 'value',
            minInterval: 1
        },
        series: [{
            areaStyle: {
                normal: {
                    color: new echarts.graphic.LinearGradient(0, 0, 0, 1, [{
                        offset: 0,
                        color: '#35c9bb'
                    }, {
                        offset: 1,
                        color: '#e7ecfb'
                    }])
                }
            },
            data: y,
            type: 'line',
            smooth: true
        }]
    };
    chart.setOption(option);
    window.onresize = function () {
        chart.resize();
    };
});
</script>