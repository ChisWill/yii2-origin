<?php

namespace es\components;

use common\helpers\Curl;
use Elasticsearch\Client;
use Elasticsearch\ClientBuilder;
use es\assets\Asset;
use Yii;

/**
 * es 控制器的基类
 */
class Controller extends \common\components\WebController
{
    /** @var Client $client */
    public $client;

    public function init()
    {
        parent::init();

        $this->view->title = 'Elasticsearch';
        $this->client = ClientBuilder::create()->build();
    }
    
    public function beforeAction($action)
    {
        if (!parent::beforeAction($action)) {
            return false;
        } else {
            return true;
        }
    }

    protected function curlSearch($index, $data = '')
    {
        return Curl::get(sprintf('http://localhost:9200/%s/_search', $index), $data, ['header' => 'Content-Type:application/json;charset="utf-8"']);
    }

    protected function response($data)
    {
        return sprintf('<div id="jsonContent"></div><script>$("#jsonContent").html("<pre>" + jsonPretty(%s) + "</pre>")</script>', json_encode($data));
    }
}
