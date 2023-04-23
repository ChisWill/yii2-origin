<?php

namespace frontend\controllers;

use common\classes\YYWeChat;
use Yii;
use common\helpers\Json;
use common\helpers\ArrayHelper;
use Exception;
use frontend\models\User;
use oa\models\OaItem;
use oa\models\OaOrder;
use oa\models\OaOrderItem;

class YuController extends \frontend\components\Controller
{
    public $layout = 'yu';

    public function actionIndex()
    {

        $this->actionViewBook();
    }

    public function actionViewBook()
    {
        $wechat = new YYWeChat;
        $token = $wechat->getAccessToken();
        $ticket = $wechat->getTicket($token);
        $configParams = $wechat->getConfigParams(self::currentUrl([], true), $ticket);

        return $this->render('book', compact('configParams'));
    }

    public function actionViewOrderList()
    {
        return $this->render('orderList');
    }

    public function actionItemList()
    {
        $list = OaItem::find()
            ->select(['oaItem.id', 'oaItem.pid', 'oaItem.name'])
            ->joinWith(['parent'])
            ->andWhere(['!=', 'oaItem.pid', 0])
            ->asArray()
            ->all();

        $itemList = [];
        $parentList = [];
        foreach ($list as $value) {
            $itemList[$value['pid']][] = $value;
            $parentList[$value['pid']] = $value['parent']['name'];
        }

        return $this->success(compact('itemList', 'parentList'));
    }

    public function actionOrderList()
    {
        $openId = get('openId');
        $list = OaOrder::find()
            ->select(['oaOrder.id', 'oaOrder.user_id', 'oaOrder.origin_amount', 'oaOrder.arrive_date', 'oaOrder.freight_type', 'oaOrder.freight_amount', 'oaOrder.order_state', 'oaOrder.created_at'])
            ->joinWith(['user', 'items.item' => function ($query) {
                $query->select(['id', 'name']);
            }])
            ->where([
                'user.username' => $openId,
                'oaOrder.state' => 1
            ])
            ->asArray()
            ->orderBy('oaOrder.id DESC')
            ->all();

        $ordereStateMap = alter('order_state');
        array_walk($list, function (&$row) use ($ordereStateMap) {
            unset($row['user']);
            $row['order_state'] = $ordereStateMap[$row['order_state']];
        });

        return $this->success($list);
    }

    public function actionBookGoods()
    {
        $postString = file_get_contents('php://input');
        $data = json_decode($postString, true);
        $openId = $data['openId'] ?: '';
        $phone = $data['phone'] ?: '';
        $cart = $data['cart'] ?: '';
        $arriveDate = $data['arriveDate'] ?: '';

        // if (strlen($phone) !== 11 || substr($phone, 0, 1) !== '1') {
        //     $phone = '';
        // }
        if (!$openId) {
            return $this->error('请先登录');
        }
        if (!$phone) {
            return $this->error('请输入手机号后四位');
        }
        if (!$cart) {
            return $this->error('至少选择一个物品');
        }

        $user = $this->getUser($openId, $phone);

        try {
            $trans = self::dbTransaction();
            $this->createOrder($user, $cart, $arriveDate);
            $trans->commit();
            return $this->success();
        } catch (Exception $e) {
            $trans->rollBack();
            return $this->error($e->getMessage());
        }
    }

    private function getUser($openId, $phone)
    {
        $user = User::find()->where(['username' => $openId])->one();
        if (!$user) {
            $user = new User();
            $user->username = $openId;
            $user->password = md5($openId);
            $user->mobile = $phone;
            $user->insert(false);
        } else {
            $user->mobile = $phone;
            $user->update(false);
        }
        return $user;
    }

    private function createOrder($user, $cart, $arriveDate)
    {
        $validCart = array_filter($cart, function ($row) {
            $row = (int) $row;
            return $row > 0;
        });
        $items = OaItem::find()->where(['id' => array_keys($validCart)])->asArray()->all();

        if (count($items) !== count($validCart)) {
            throw new Exception('物品数据错误');
        }

        $amount = 0;
        foreach ($items as $row) {
            $amount += $row['amount'] * $validCart[$row['id']];
        }

        $order = new OaOrder();
        $order->user_id = $user->id;
        $order->origin_amount = $amount;
        $order->arrive_date = $arriveDate;
        if (!$order->insert()) {
            throw new Exception('预定失败');
        }
        $rows = [];
        foreach ($items as $row) {
            $rows[] = [
                $order->id,
                $row['id'],
                $validCart[$row['id']],
                $row['amount']
            ];
        }
        self::dbInsert('oa_order_item', ['order_id', 'item_id', 'num', 'amount'], $rows);
    }
}
