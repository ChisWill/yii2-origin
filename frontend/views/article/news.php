<?php use common\helpers\Html; ?>
news<br>
<?php foreach ($subMenus as $key => $sub) {
    if ($sub['id'] == $subMenu->id) {
        $selected = 'xxx';
    } else {
        $selected = '';
    }
    echo Html::a($sub->name . $selected, [$menu['url'], 'id' => $sub->id]). '<br>';
} ?>