<?php use common\helpers\Html; ?>

<fieldset class="layui-elem-field layui-field-title">
    <legend>各查询结果演示</legend>
</fieldset>

<table class="layui-table">
    <colgroup>
      <col width="200">
      <col>
    </colgroup>
    <tbody>
        <tr>
            <td>分页搜索</td>
            <td>
                <table class="layui-table">
                    <colgroup>
                      <col width="200">
                      <col width="200">
                      <col width="200">
                    </colgroup>
                    <thead>
                        <tr>
                            <th>用户名</th>
                            <th>金额</th>
                            <th>时间</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($result['paginate'] as $row): ?>
                        <tr>
                            <td><?= $row['user']['username'] ?></td>
                            <td><?= $row['amount'] ?></td>
                            <td><?= $row['created_at'] ?></td>
                        </tr>
                    <?php endforeach ?>
                    </tbody>
                </table>
                <!-- 显示分页的页码，不需要设置参数，直接调用即可 -->
                <?= self::linkPager() ?>
            </td>
        </tr>
        <tr>
            <td>生成的SQl语句</td>
            <td><?= $result['sql'] ?></td>
        </tr>
        <tr>
            <td>满足条件的第一条记录</td>
            <td><?= '<xmp>' . print_r($result['one'], true) . '</xmp>' ?></td>
        </tr>
        <tr>
            <td>满足条件的所有记录</td>
            <td><?= '<xmp>' . print_r($result['all'], true) . '</xmp>' ?></td>
        </tr>
        <tr>
            <td>满足条件的记录数</td>
            <td><?= $result['count'] ?></td>
        </tr>
        <tr>
            <td>是否存在满足条件的记录</td>
            <td><?= $result['exists'] ? Html::successSpan('是') : Html::errorSpan('否') ?></td>
        </tr>
        <tr>
            <td>分组合计`account`</td>
            <td><?= var_export($result['group']) ?></td>
        </tr>
        <tr>
            <td>总合计`account`</td>
            <td><?= $result['sum'] ?></td>
        </tr>
        <tr>
            <td>关联查询Sql语句</td>
            <td><?= $result['joinSql'] ?></td>
        </tr>
        <tr>
            <td>关联查询的所有记录</td>
            <td><?= '<xmp>' . print_r($result['joinAll'], true) . '</xmp>' ?></td>
        </tr>
    </tbody>
</table>