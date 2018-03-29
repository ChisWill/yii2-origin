<?php use oa\models\OaUser; ?>
<?php oa\assets\ChartAsset::register($this) ?>

<style type="text/css">
.hidden {
    display: none;
}
.chart {
    width: 600px;
    height: 400px;
}
.container {
    width: 100%;
    text-align: center;
}
.container li {
    display: inline-block;
    margin: 40px 40px 0;
}
</style>

<div class="jsonData hidden"><?= json_encode($data) ?></div>
<div class="productsData hidden"><?= json_encode($products) ?></div>
<div class="typeData hidden"><?= json_encode(OaUser::getTypeMap()) ?></div>
<div class="levelData hidden"><?= json_encode(OaUser::getLevelMap(false, 1)) ?></div>

<?= $html ?>

<ul class="container">
    <li id="typeChart" class="chart"></li>
    <li id="levelChart" class="chart"></li>
    <li id="productChart" class="chart"></li>
    <li id="compareChart" class="chart"></li>
</ul>