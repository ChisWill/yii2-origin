<fieldset class="layui-elem-field layui-field-title">
    <legend>各种实际案例场景演示</legend>
</fieldset>

<table class="layui-table">
    <colgroup>
      <col width="200">
      <col>
    </colgroup>
    <tbody>
        <tr>
            <td>案例一：统计每日访问人数</td>
            <td><?= $result['case1'] ?></td>
        </tr>
        <tr>
            <td>案例二：将充值提现记录融合到一起</td>
            <td><?= $result['case2'] ?></td>
        </tr>
        <tr>
            <td>案例三：对案例二的结果进行分页排序显示</td>
            <td>
                <table class="layui-table">
                    <colgroup>
                      <col width="200">
                      <col width="200">
                      <col width="200">
                      <col>
                    </colgroup>
                    <thead>
                        <tr>
                            <th>用户ID</th>
                            <th>金额</th>
                            <th>类型</th>
                            <th>实际</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($result['case3'] as $row): ?>
                        <tr>
                            <td><?= $row['user_id'] ?></td>
                            <td><?= $row['amount'] ?></td>
                            <td><?= $row['type'] ?></td>
                            <td><?= $row['created_at'] ?></td>
                        </tr>
                    <?php endforeach ?>
                    </tbody>
                </table>
                <?= self::linkPager() ?>
            </td>
        </tr>
    </tbody>
</table>