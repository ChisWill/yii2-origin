<?php

namespace admin\controllers;

use Yii;
use common\helpers\Html;
use admin\models\Picture;
use common\helpers\ArrayHelper;

class ProductController extends \admin\components\Controller
{
    private $ngServerDb;
    private $ngStoreDb;

    public function init()
    {
        parent::init();

        $this->ngServerDb = Yii::$app->get('ngServerDb');
        $this->ngStoreDb = Yii::$app->get('ngStoreDb');
    }

    private $supplyMap = [
        'JD' => '京东',
        'KL' => '考拉海购',
        'LM' => '淘心选',
        'MI' => '小米有品',
        'SF2' => '顺丰优选',
        'SHOP' => '品牌馆',
        'SHOPNG' => '品牌馆',
        'SN' => '苏宁易购',
        'VDG' => '唯品会',
        'YXSRBT' => '网易严选',
    ];

    private $mallCatRedisKey = 'productListMallCat';

    /**
     * @authname 订单统计
     */
    public function actionList()
    {
        $params = $this->getParams();
        $query = $this->getBaseQuery($params);

        $mapQuery = clone $query;
        $bns = $mapQuery->map('bn', 'bn', $this->ngServerDb);
        $countQuery = clone $query;
        $total = $countQuery->sum('amount', $this->ngServerDb);

        $goodsMap = $this->getGoodsMap($bns);
        $catMap = $this->getCatMap();

        $html = $query->getTable([
            'order_id' => '订单ID',
            'name' => '商品名称',
            ['header' => '板块代号', 'value' => function ($row) {
                return explode('-', $row['bn'])[0];
            }],
            ['header' => '板块描述', 'value' => function ($row) {
                return ArrayHelper::getValue($this->supplyMap, explode('-', $row['bn'])[0], '未知');
            }],
            ['header' => '一级分类', 'value' => function ($row) use($goodsMap, $catMap) {
                return $this->getCateName($goodsMap, $catMap, $row['bn'], 1);
            }],
            ['header' => '二级分类', 'value' => function ($row) use($goodsMap, $catMap) {
                return $this->getCateName($goodsMap, $catMap, $row['bn'], 2);
            }],
            ['header' => '三级分类', 'value' => function ($row) use($goodsMap, $catMap) {
                return $this->getCateName($goodsMap, $catMap, $row['bn'], 3);
            }],
            'amount' => '总价'
        ], [
            'searchColumns' => [
                'company_id' => ['type' => 'text', 'header' => '公司序号', 'default' => $params['companyId']],
                'date' => ['type' => 'dateRange', 'header' => '日期', 'default' => $params['startDate']],
            ],
            'dbConnection' => $this->ngServerDb,
            'export' => $this->getExcelName('订单明细', $params),
        ]);

        return $this->render('list', compact('html', 'total'));
    }

    public function actionGroupList()
    {
        $params = $this->getParams();
        $query = $this->getBaseQuery($params);

        $countQuery = clone $query;
        $total = $countQuery->sum('amount', $this->ngServerDb);

        $groupData = [];
        foreach ($query->batch(1000, $this->ngServerDb) as $batch) {
            $bns = ArrayHelper::map($batch, 'bn', 'bn');
            $goodsMap = $this->getGoodsMap($bns);
            foreach ($batch as $row) {
                $bn = explode('-', $row['bn'])[0];
                if (!isset($groupData[$bn])) {
                    $groupData[$bn] = 0;
                }
                $groupData[$bn] += $row['amount'];
            }
        }
        $data = [];
        $sum = 0;
        foreach ($groupData as $bn => $amount) {
            $data[] = [
                'name' => $this->supplyMap[$bn],
                'amount' => $amount,
                'percent' => round($amount / $total * 100, 2)
            ];
            $sum += $amount;
        }
        $data[] = [
            'name' => '总计',
            'amount' => $sum,
            'percent' => ''
        ];

        $html = self::getTable($data, [
            'name' => ['header' => '板块名称'],
            'amount' => ['header' => '销售额（元）'],
            'percent' => ['header' => '占比（%）'],
        ], [
            'searchColumns' => [
                'company_id' => ['type' => 'text', 'header' => '公司序号', 'default' => $params['companyId']],
                'date' => ['type' => 'dateRange', 'header' => '日期', 'default' => $params['startDate']],
            ],
            'export' => $this->getExcelName('分类总计', $params),
        ]);

        return $this->render('groupList', compact('html'));
    }

    private function getCateName($goodsMap, $catMap, $bn, $level)
    {
        $catId = $goodsMap[$bn];
        $cat = $catMap[$catId];
        if ($level == 3) {
            return $cat['cat_name'];
        }
        $paths = explode(',', $cat['cat_path']);
        $levels = [];
        foreach ($paths as $v) {
            if (!empty($v)) {
                $levels[] = $v;
            }
        }
        if (isset($levels[$level - 1])) {
            return $catMap[$levels[$level - 1]]['cat_name'];
        } else {
            return '未知';
        }
    }

    private function getExcelName($name, $params)
    {
        $args = [];
        if (!$params['endDate']) {
            $format = '%s %s至今';
            $args[] = $name;
            $args[] = $params['startDate'];
        } else {
            $format = '%s %s到%s';
            $args[] = $name;
            $args[] = $params['startDate'];
            $args[] = $params['endDate'];
        }
        return sprintf($format, ...$args);
    }

    private function getParams()
    {
        $gets = get('search');
        $params = [];
        $params['companyId'] = ArrayHelper::getValue($gets, 'company_id', 50211);
        $params['startDate'] = ArrayHelper::getValue($gets, 'start_date', '2021-2-4');
        $params['endDate'] = ArrayHelper::getValue($gets, 'end_date', null);

        return $params;
    }

    private function getGoodsMap($bns)
    {
        $goodsQuery = self::dbQuery()
            ->select(['p.name', 'p.bn', 'g.mall_goods_cat as cat_id'])
            ->from('{{%products}} p')
            ->leftJoin('{{%goods}} g', 'p.goods_id=g.goods_id')
            ->where(['p.bn' => $bns]);
        return ArrayHelper::map($goodsQuery->all($this->ngStoreDb), 'bn', 'cat_id');
    }

    private function getBaseQuery($params)
    {
        return self::dbQuery()
            ->select(['o.order_id', 'i.name', 'i.bn', 'i.amount'])
            ->from('{{%orders}} o')
            ->leftJoin('{{%order_items}} i', 'o.order_id=i.order_id')
            ->where(['company_id' => $params['companyId'], 'create_source' => 'main'])
            ->andWhere(['>=', 'pay_time', strtotime($params['startDate'])])
            ->andWhere(['<>', 'pay_time', 0])
            ->andWhere(['<>', 'status', 2])
            ->andFilterWhere(['<=', 'pay_time', $params['endDate'] ? strtotime($params['endDate']) + 86400 : null])
            ->orderBy('o.order_id desc');
    }
    
    private function getCatMap()
    {
        $catMap = redis()->get($this->mallCatRedisKey);
        if (!$catMap) {
            $catQuery = self::dbQuery()
                ->select(['cat_id', 'cat_name', 'cat_path', 'parent_id'])
                ->from('{{%mall_goods_cat}}');
            $catMap = array_column($catQuery->all($this->ngStoreDb), null, 'cat_id');
            redis()->set($this->mallCatRedisKey, json_encode($catMap));
        } else {
            $catMap = json_decode($catMap, true);
        }
        return $catMap;
    }
}
