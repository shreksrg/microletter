<?php
Micro::import('application.libraries.payment.*');

class Payment extends MicroController
{
    protected $_modelPayment;

    public function __construct()
    {
        parent::__construct();
        $this->_modelPayment = CModel::make('payment_model');
    }


    public function index()
    {
        if (REQUEST_METHOD == 'GET') {
            //生成支付确认页
            $orderId = (int)$this->input->get('orderId');
            $modelOrder = CModel::make('order_model');
            $orderObj = $modelOrder->genOrder($orderId);
            $state = $orderObj->getState();
            if ($state == 'on') {
                $data = array();
                $orderRow = $orderObj->row;
                $modelUser = CModel::make('user_model');
                $userRow = $modelUser->getRowById($orderRow->user_id);
                if (!$userRow) {
                    echo "挑战人不存在";
                    return false;
                }
                //发起人姓名
                $data['originator'] = $userRow->fullname;
                //支付费用
                $data['amount'] = ($orderRow->gross / $orderRow->quota); // 支付金额
                //产品信息
                $modelGoods = CModel::make('goods_model');
                $data['goods'] = $modelGoods->getOrderGoods($orderId);
                // 订单信息
                $data['order'] = $orderObj;

                CView::show('payment/confirm', $data);
            } else
                echo '挑战已结束';

        } else {
            //保存支付订单并提交
        }

        $code = 0;
        $message = 'successful';
        $orderId = (int)$this->input->get('orderId');
        $type = (int)$this->input->get('type'); //支付方式
        $type = 2; //支付宝
        $payItem = $this->_modelPayment->newPayItem($orderId, $type); //产生支付项数据
        if ($payItem === false) {
            $code = $this->_modelPayment->errCode;
            $message = '挑战项目无效';
        } else
            CAjax::show($code, $message, array('orderId' => $orderId, 'payId' => $payItem['pay_id']));
        return false;

        $orderId = (int)$this->input->get('orderId');
        if ($orderId <= 0) CAjax::show(1001, 'failure');
        else {
            //验证订单状态
            $return = $this->_modelPayment->checkOrder($orderId);
            if ($return !== true) CAjax::show(1002, 'failure'); //该订单已经过期或无效
        }
        $orderObj = $this->_modelPayment->getOrder($orderId);
        $payId = $this->aliPay($orderObj);
        $code = 1000;
        $message = 'failure';
        if ($payId > 0) {
            $code = 0;
            $message = 'successful';
        }
        CAjax::show($code, $message, array('orderId' => $orderId, 'payId' => $payId));
    }


    /**
     * 提交支付
     */
    public function submit()
    {
        $request = $this->input->server('REQUEST_METHOD');
        $orderId = (int)$this->input->get('orderId');
        if ($request == 'GET') {
            //响应返回支付订单确认页
            $modelOrder = CModel::make('order_model');
            $orderObj = $modelOrder->genOrder($orderId);

            if (($orderRow = $orderObj->row)) {
                $itemId = $orderRow->item_id;
                $modItem = CModel::make('planItem_model');
                $orderObj->item = $modItem->genItem($itemId); //订单项目
                if (!$orderObj->item->row) {
                    echo "项目已关闭无效";
                    return false;
                }
            } else {
                echo "无效的挑战项目";
                return false;
            }

            $state = $modelOrder->getOrderState($orderObj);
            if ($state != 'on') {
                echo "该挑战已结束,谢谢你的支持"; //订单过期或已经完成
                return false;
            }


            //支付费用
            $data['amount'] = ($orderRow->gross / $orderRow->quota); // 支付金额
            //产品信息
            $data['goods'] = $modItem->getGoodsRecs($orderObj->item->row->id)->row_array();
            // 订单信息
            $data['order'] = $orderObj;

            CView::show('payment/confirm', $data);
        } else {
            //生成支付提交表单
            $orderId = (int)$this->input->post('orderId');
            $type = (int)$this->input->post('type'); //支付方式

            //生成支付订单记录
            $payItem = $this->_modelPayment->newPayItem($orderId, $type); //产生支付项数据
            if ($payItem !== false) {
                $token = base64_encode($payItem['amount']);
                header('location:' . SITE_URL . '/payment/notify?token=' . $token);
            } else {
                echo 'errorCode:' . $this->_modelPayment->errCode;
            }

            return false;

            //支付宝支付
            if ($type == 2) {
                // $form = AliPay::form($payItem);
                CView::show('payment/formAliPay', array('order' => $payItem));
                return false;
            }

            if ($type == 2) {
                $form = MicroPay::form($payItem);
                CView::show('payment/formMicroPay', $form);
                return false;
            }
        }
    }

    /**
     * 支付宝回调接口
     */
    public function apiAliPay()
    {
        $method = $this->input->server('REQUEST_METHOD');
        if ($method === 'GET') {
            //响应返回前端通知页面
            CView::show('payment/notify');
        } else {
            //后端通知
            $data = $this->input->post();
            $reVal = AliPay::notify($data);
            if ($reVal === true)
                $this->_modelPayment->updatePayment($data); //更新支付状态
        }
    }

    /**
     * 模拟通知接口页面
     */
    public function notify()
    {
        $token = $this->input->get('token');
        $data['amount'] = base64_decode($token);
        CView::show('payment/notify', $data);
    }
}
