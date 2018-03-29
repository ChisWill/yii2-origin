$(function () {
    // 用户相关图表
    var data = JSON.parse($(".jsonData").html()),
        typeData = JSON.parse($(".typeData").html()),
        levelData = JSON.parse($(".levelData").html()),
        typeChart = echarts.init(document.getElementById('typeChart')),
        levelChart = echarts.init(document.getElementById('levelChart')),
        legend = [],
        legendOption = {
            orient: 'vertical',
            left: 'left',
            data: legend
        },
        typeXAxis = [],
        typeSeries = [],
        levelXAxis = [],
        levelSeries = [],
        key, k, varName;
    if (data.length !== 0) {
        for (key in typeData) {
            typeXAxis.push(typeData[key]);
        }
        for (key in levelData) {
            levelXAxis.push(levelData[key]);
        }
        for (key in data) {
            var typeSeriesData = [];
            for (k in typeData) {
                varName = 'type' + k;
                typeSeriesData.push(data[key][varName] || 0);
            }
            var levelSeriesData = [];
            for (k in levelData) {
                varName = 'level' + k;
                levelSeriesData.push(data[key][varName] || 0);
            }
            var typeRow = {
                    name: data[key]['name'],
                    type: 'bar',
                    data: typeSeriesData
                },
                levelRow = {
                    name: data[key]['name'],
                    type: 'bar',
                    data: levelSeriesData
                };
            typeSeries.push(typeRow);
            levelSeries.push(levelRow);
            legend.push(data[key]['name']);
        }
        typeChart.setOption({
            title: {
                text: '客户分类表',
                x: 'center'
            },
            tooltip: {
                trigger: 'axis'
            },
            legend: legendOption,
            xAxis: {
                data: typeXAxis
            },
            yAxis: {
                minInterval: 1
            },
            series: typeSeries
        });
        levelChart.setOption({
            title: {
                text: '意向客户表',
                x: 'center'
            },
            tooltip: {
                trigger: 'axis'
            },
            legend: legendOption,
            xAxis: {
                data: levelXAxis
            },
            yAxis: {
                minInterval: 1
            },
            series: levelSeries
        });
    } else {
        $("#typeChart").hide();
        $("#levelChart").hide();
    }
    // 产品相关图表
    var productsData = JSON.parse($(".productsData").html());
        productChart = echarts.init(document.getElementById('productChart')),
        compareChart = echarts.init(document.getElementById('compareChart')),
        legend = [],
        legendOption = {
            orient: 'vertical',
            left: 'left',
            data: legend
        },
        productSeries = [],
        compareSeries = [];
    for (key in productsData[0]) {
        var productRow = {
            name: productsData[0][key]['name'],
            value: productsData[0][key]['amount']
        };
        productSeries.push(productRow);
        legend.push(productsData[0][key]['name']);
    }
    for (key in productsData[1]) {
        var compareRow = {
            name: productsData[1][key]['name'],
            value: productsData[1][key]['amount']
        }
        compareSeries.push(compareRow);
    }
    productChart.setOption({
        title: {
            text: '项目分布图',
            x: 'center'
        },
        tooltip: {
            trigger: 'item',
            formatter: "{b} : {c} ({d}%)"
        },
        legend: legendOption,
        series: [{
            type: 'pie',
            radius: '70%',
            data: productSeries
        }]
    });
    compareChart.setOption({
        title: {
            text: '项目成交对比图',
            x: 'center'
        },
        tooltip: {
            trigger: 'item',
            formatter: "{b} : {c} ({d}%)"
        },
        legend: legendOption,
        series: [{
            name: '总项目数',
            type: 'pie',
            label: {
                normal: {
                    position: 'inner'
                }
            },
            radius : [0, '30%'],
            data: productSeries
        }, {
            name: '成交项目数',
            type: 'pie',
            radius: ['45%', '70%'],
            data: compareSeries
        }]
    });
});