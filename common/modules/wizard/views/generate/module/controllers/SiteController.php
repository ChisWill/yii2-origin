<?php
echo "<?php\n";
?>

namespace <?= $namespace ?>;

use Yii;

class SiteController extends \<?= $baseNs ?>\components\Controller
{
    public $layout = 'main';

    public function actionIndex()
    {
        $this->view->title = '首页 - <?= $moduleName ?>';

        return $this->render('index');
    }
}
