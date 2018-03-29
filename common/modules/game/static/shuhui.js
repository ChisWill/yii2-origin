/**
 * 尺寸模式：10 * 10，18 * 10，24 * 18，
 * 边宽固定100px
 * 放大50倍后加上边距的尺寸：600 * 600, 1000 * 600，1300 * 1000
 */
$(function () {
    var sizeList = [
        {width: 3, height: 2},
        {width: 10, height: 10},
        {width: 18, height: 10},
        {width: 24, height: 18},
    ];
    var mode = 0;
    var rate = 50;
    var padding = 100;
    var allWidth = sizeList[mode]['width'] * rate + padding;
    var allHeight = sizeList[mode]['height'] * rate + padding;
    $("#main-area").width(allWidth);
    $("#main-area").height(allHeight);
    var question = [
        {x: 2, y: 1, n: 2},
        {x: 3, y: 1, n: 3},
        {x: 1, y: 2, n: 3},
        {x: 2, y: 2, n: 2},
    ];
    var answer = [
        {x1: 1, y1: 0, x2: 2, y2: 0},
        {x1: 2, y1: 0, x2: 3, y2: 0},
        {x1: 1, y1: 0, x2: 1, y2: 1},
        {x1: 0, y1: 1, x2: 0, y2: 2},
        {x1: 0, y1: 2, x2: 1, y2: 2},
        {x1: 0, y1: 1, x2: 1, y2: 1},
        {x1: 2, y1: 1, x2: 2, y2: 2},
        {x1: 1, y1: 2, x2: 2, y2: 2},
        {x1: 2, y1: 1, x2: 3, y2: 1},
        {x1: 3, y1: 0, x2: 3, y2: 1}
    ];

    require.config({
        packages: [
            {
                name: 'zrender',
                location: '/js/zrender-2.1.0/src',
                main: 'zrender'
            }
        ]
    });
    require(
        [
            "zrender",
            'zrender/shape/Line',
            'zrender/shape/Circle',
            'zrender/shape/Text'
        ], function (zrender, Line, Circle, Text) {
            // 初始化zrender
            zr = zrender.init($("#main-area")[0]);
            // 定义基本参数值
            var edgePadding = padding / 2;
            var getX = function (x) {
                return x * rate + edgePadding;
            };
            var getY = function (y) {
                return y * rate + edgePadding;
            };
            var getPointJson = function (x, y) {
                return {
                    style: {
                        x: getX(x),
                        y: getX(y),
                        r: 2,
                        brushType: 'both',
                        color: 'gray',
                        strokeColor: 'gray',
                        lineWidth: 2,
                        text: ''
                    },
                    clickable: true,
                    onclick: function (event) {
                        
                    }
                };
            }
            var getTextJson = function (x, y, n) {
                return {
                    style: {
                        text: n,
                        x: (getX(x) + getX(x - 1) - 15) / 2,
                        y: (getY(y) + getY(y - 1) + 5) / 2,
                        textFont: '30px Arial'
                    }
                };
            };
            var getLineJson = function (x1, y1, x2, y2) {
                if (x1 == x2) {
                    return {
                        style: {
                            xStart: getX(x1),
                            yStart: getY(Math.min(y1, y2)) + 10,
                            xEnd: getX(x2),
                            yEnd: getY(Math.max(y1, y2)) - 10,
                            strokeColor: 'black',
                            lineWidth: 3
                        }
                    };
                } else {
                    return {
                        style: {
                            xStart: getX(Math.min(x1, x2)) + 10,
                            yStart: getY(y1),
                            xEnd: getX(Math.max(x1, x2)) - 10,
                            yEnd: getY(y2),
                            strokeColor: 'black',
                            lineWidth: 3
                        }
                    };
                }
            }
            
            // 循环X
            for (var x = 0; x <= sizeList[mode]['width']; x++) {
                // 循环y
                for (var y = 0; y <= sizeList[mode]['height']; y++) {
                    // 添加点
                    var circle = new Circle(getPointJson(x, y));
                    zr.addShape(circle);
                    // 添加边,每循环一个点需要添加两个边
                    // 横向
                    // if (x > 0) {
                    //     var line = new Line(getLineJson(x - 1, y, x, y));
                    //     zr.addShape(line);
                    // }
                    // // 纵向
                    // if (y > 0) {
                    //     var line = new Line(getLineJson(x, y - 1, x, y));
                    //     zr.addShape(line);
                    // }
                }
            };
            // 添加操作点
            zr.addShape(new Circle({
                style: {
                    x: getX(0),
                    y: getX(0),
                    r: 5,
                    brushType: 'both',
                    color: 'gray',
                    strokeColor: 'black',
                    lineWidth: 3,
                    text: ''
                },
                clickable: true,
                onclick: function (e) {
                    
                }
            }));
            // 添加题目数字
            for (var q in question) {
                var x = question[q]['x'];
                var y = question[q]['y'];
                var n = question[q]['n'];
                var text = new Text(getTextJson(x, y, n));
                zr.addShape(text);
            }
            // 添加答案
            for (var a in answer) {
                var x1 = answer[a]['x1'];
                var y1 = answer[a]['y1'];
                var x2 = answer[a]['x2'];
                var y2 = answer[a]['y2'];
                var line = new Line(getLineJson(x1, y1, x2, y2));
                zr.addShape(line);
            }
            // 验证
            $("#check").click(function () {
                var getRes = function () {
                    var tmp1 = {};
                    var tmp2 = {};
                    var key;
                    for (var k in answer) {
                        key = answer[k]['x1'] + '-' + answer[k]['y1'];
                        if (!tmp1[key]) {
                            tmp1[key] = k;
                        } else {
                            tmp2[key] = k
                        }
                        key = answer[k]['x2'] + '-' + answer[k]['y2'];
                        if (!tmp1[key]) {
                            tmp1[key] = k;
                        } else {
                            tmp2[key] = k
                        }
                    }
                    var res = {},
                        k1,
                        k2;
                    for (var k in answer) {
                        res[k] = [];
                        k1 = answer[k]['x1'] + '-' + answer[k]['y1'];
                        if (tmp1[k1] && tmp1[k1] != k) {
                            res[k].push(tmp1[k1]);
                        } else if (tmp2[k1] && tmp2[k1] != k) {
                            res[k].push(tmp2[k1]);
                        }
                        k2 = answer[k]['x2'] + '-' + answer[k]['y2'];
                        if (tmp1[k2] && tmp1[k2] != k) {
                            res[k].push(tmp1[k2]);
                        } else if (tmp2[k2] && tmp2[k2] != k) {
                            res[k].push(tmp2[k2]);
                        }
                    }
                    return res;
                }
                var getUniqueLineKey = function (line) {
                    var x1 = line['x1'],
                        y1 = line['y1'],
                        x2 = line['x2'],
                        y2 = line['y2'];
                    return Math.min(x1, x2) + '-' + Math.min(y1, y2) + '-' + Math.max(x1, x2) + '-' + Math.max(y1, y2);
                }
                var getLines = function () {
                    var lines = [],
                        key;
                    for (var k in answer) {
                        key = getUniqueLineKey(answer[k]);
                        lines.push(key);
                    }
                    return lines;
                }
                var check = function () {
                    var res = getRes(),
                        lines = getLines(),
                        last = 0,
                        now = 0;
                    for (var i = 0, length = answer.length; i < length; i++) {
                        if (res[now].length != 2) {
                            return false;
                        } else if (res[now][0] == last) {
                            last = now;
                            now = res[now][1];
                        } else {
                            last = now;
                            now = res[now][0];
                        }
                    }
                    for (var k in question) {
                        var x = question[k]['x'],
                            y = question[k]['y'],
                            line1 = {x1: x - 1, y1: y - 1, x2: x - 1, y2: y},
                            line2 = {x1: x - 1, y1: y, x2: x, y2: y},
                            line3 = {x1: x, y1: y, x2: x, y2: y - 1},
                            line4 = {x1: x - 1, y1: y - 1, x2: x, y2: y - 1},
                            count = 0;
                        var lineKey1 = getUniqueLineKey(line1);
                        var lineKey2 = getUniqueLineKey(line2);
                        var lineKey3 = getUniqueLineKey(line3);
                        var lineKey4 = getUniqueLineKey(line4);
                        $.inArray(lineKey1, lines) !== -1 ? count++ : 0;
                        $.inArray(lineKey2, lines) !== -1 ? count++ : 0;
                        $.inArray(lineKey3, lines) !== -1 ? count++ : 0;
                        $.inArray(lineKey4, lines) !== -1 ? count++ : 0;
                        if (count != question[k]['n']) {
                            return false;
                        }
                    }
                    return 0 == now;
                }
                if (check()) {
                    alert('success');
                } else {
                    alert('error');
                }
            });
            zr.render();
        })
});