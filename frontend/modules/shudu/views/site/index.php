<script src="https://cdn.jsdelivr.net/npm/vue/dist/vue.js"></script>
<?php common\assets\HuiAsset::register($this) ?>
<?php common\assets\LayerAsset::register($this) ?>

<style>
table#main {
    margin: auto;
    width: 540px;
    height: 540px;
}
table#main td {
    text-align: center;
    margin: 0;
    padding: 0;
    width: 60px;
    height: 60px;
}
table#main .write {
    color: #027fff;
    font-size: 30px;
    line-height: 20px;
}
table#main .raw {
    color: black;
    font-size: 26px;
}
table#main .write ul {
    display: table-row;
    font-size: 10px;
    color: gray;
    font-weight: 100;
}
table#main .write ul li {
    display: table-cell;
    width: 18px;
}
table.buttonArea {
    margin: auto;
    width: 500px;
    margin-top: 50px;
}
table.buttonArea td {
    text-align: center;
}
table#sub #inputDataArea {
    width: 500px;
    height: 500px;
    resize: none;
    font-size: 35px;
    letter-spacing: 28px;
    padding-left: 20px;
}
</style>

<div id="container">
    <div class="list-container" v-if="raw.length > 0">
        <h2 class="text-c mb-20">{{desc}}</h2>
        <table id="main" class="table table-border table-bordered table-bg">
            <tr v-for="(row, index) in raw">
                <td v-for="(col, i) in row">
                    <div class="write" v-if="col==0">
                        <template v-if="data[index][i] != 0">{{data[index][i]}}</template>
                        <template v-else>
                            <ul>
                                <li v-if="tag[index][i].hasOwnProperty(0)">1</li><li v-else>&nbsp;</li>
                                <li v-if="tag[index][i].hasOwnProperty(1)">2</li><li v-else>&nbsp;</li>
                                <li v-if="tag[index][i].hasOwnProperty(2)">3</li><li v-else>&nbsp;</li>
                            </ul>
                            <ul>
                                <li v-if="tag[index][i].hasOwnProperty(3)">4</li><li v-else>&nbsp;</li>
                                <li v-if="tag[index][i].hasOwnProperty(4)">5</li><li v-else>&nbsp;</li>
                                <li v-if="tag[index][i].hasOwnProperty(5)">6</li><li v-else>&nbsp;</li>
                            </ul>
                            <ul>
                                <li v-if="tag[index][i].hasOwnProperty(6)">7</li><li v-else>&nbsp;</li>
                                <li v-if="tag[index][i].hasOwnProperty(7)">8</li><li v-else>&nbsp;</li>
                                <li v-if="tag[index][i].hasOwnProperty(8)">9</li><li v-else>&nbsp;</li>
                            </ul>
                        </template>
                    </div>
                    <div class="raw" v-else>{{col}}</div>
                </td>
            </tr>
        </table>
        <table class="buttonArea">
            <tr>
                <td><button v-on:click="first" id="first" class="btn-warning-outline btn radius size-M">初始</button></td>
                <td><button v-on:click="prev" id="prev" class="btn-secondary-outline btn radius size-S">上一步</button></td>
                <td><button v-on:click="next" id="next" class="btn-secondary-outline btn radius size-S">下一步</button></td>
                <td><button v-on:click="last" id="last" class="btn-danger-outline btn radius size-M">最后</button></td>
            </tr>
        </table>
    </div>
    <div v-else>
        <h2 class="text-c mb-20">手动创建数独题目</h2>
        <table id="sub" class="buttonArea">
            <tr>
                <td><textarea id="inputDataArea" @paste="onPaste"></textarea></td>
            </tr>
            <tr>
                <td><button v-on:click="submitData" id="submitData" class="btn-danger-outline btn radius size-M">确认创建</button></td>
            </tr>
        </table>
    </div>
</div>
</body>
<script>
var url = "<?= url(['index']) ?>";
var component = new Vue({
    el: '#container',
    data: {
        raw: [],
        tag: [],
        data: [],
        desc: '',
        text: '',
        step: 0
    },
    methods: {
        first: function () {
            this.step = 0;
            this.query(this.step);
        },
        prev: function () {
            if (this.step > 0) {
                this.step--;
            } else {
                this.step = 0;
            }
            this.query(this.step);
        },
        next: function () {
            this.step++;
            this.query(this.step);
        },
        last: function () {
            this.query(-1);
        },
        onPaste: function (event) {
            event.preventDefault();
            let text = event.clipboardData.getData("text");
            $("#inputDataArea").val(text.replace(/[\D]/g, ""));
        },
        submitData: function () {
            this.text = $("#inputDataArea").val();
            $.post(url, {action: 'init', data: this.text}, msg => {
                if (msg.state) {
                    this.raw = msg.info.data;
                    this.data = msg.info.data;
                    this.tag = msg.info.tag;
                    this.desc = msg.info.desc;
                    this.step = msg.info.step;
                } else {
                    $.alert(msg.info);
                }
            }, 'json');
        },
        query: function (step) {
            $.post(url, {action: 'query', step: step, data: this.text}, msg => {
                if (msg.state) {
                    this.data = msg.info.data;
                    this.tag = msg.info.tag;
                    this.desc = msg.info.desc;
                    this.step = msg.info.step;
                } else {
                    $.alert(msg.info);
                }
            }, 'json');
        }
    }
});
</script>