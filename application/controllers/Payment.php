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
            //响应支付确认页
            $this->confirm();
        } else {
            //保存支付订单并提交
            $this->save();
        }
    }

    /**
     * 响应返回支付确认页
     */
    protected function confirm()
    {
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
                CView::show('message/error', array('code' => 1001, 'content' => '挑战已关闭'));
                return false;
            }
            //支付项目
            $modelItem = CModel::make('planItem_model');
            $data['item'] = (array)$modelItem->orderItemRows($orderRow->id);
            if (!$data['item']) {
                CView::show('message/error', array('code' => 1002, 'content' => '挑战项目已关闭'));
                return false;
            }
            //发起人姓名
            $data['originator'] = $userRow->fullname;
            //支付费用
            $data['amount'] = ($orderRow->gross / $orderRow->quota); // 支付金额
            //订单商品信息
            $modelGoods = CModel::make('goods_model');
            $data['goods'] = (array)$modelGoods->orderGoodsRows($orderId);
            if (!$data['goods']) {
                CView::show('message/error', array('code' => 1003, 'content' => '挑战项目已关闭'));
                return false;
            }
            // 订单信息
            $data['order'] = (array)$orderRow;
            CView::show('payment/confirm', $data);
        } else
            CView::show('message/error', array('code' => 1000, 'content' => '挑战已结束'));
    }

    /**
     * 生成并保存支付订单
     */
    protected function save()
    {
        //验证订单
        $orderId = (int)$this->input->get('orderId');
        $payType = (int)$this->input->get('type');

        $modelOrder = CModel::make('order_model');
        $orderObj = $modelOrder->genOrder($orderId);
        $state = $modelOrder->getState($orderObj);
        if ($state == 'none') {
            CView::show('message/error', array('code' => 1000, 'content' => '支付错误'));
            return false;
        }

        //生成支付订单
        $payItem = $this->_modelPayment->newPayItem($orderObj, $payType); //产生支付项数据

        //订单支付处理
        if ($payItem) {
            if ($payType == 2) {
                //阿里支付表单
                //CView::show('payment/formAliPay', array('order' => $payItem));
                $this->alipay($payItem);
            } else {
                CView::show('message/error', array('code' => '1001', 'content' => '该支付目前不支持'));
            }
            // $token = base64_encode($payItem['amount']);
            // header('location:' . SITE_URL . '/payment/notify?token=' . $token);
        } else {
            CView::show('message/error', array('code' => '-1', 'content' => '订单支付失败,请重新支付'));
        }
        return true;
    }

    /**
     * 支付宝交易
     */
    public function alipay($data)
    {
        $aliPay = new AliPay();
        $aliPay->disburse($data);
    }

    /**
     * 支付宝通知接口
     */
    protected function aliPayNotify()
    {
        if (REQUEST_METHOD == 'GET') {
            $aliPay = new AliPay();
            $return = $aliPay->verify();
            $tradeNo = $this->input->get('trade_no');
            $orderId = $this->input->get('out_trade_no');
            $amount = $this->input->get('total_fee');
            $data = array('status' => 'fail', 'orderId' => $orderId, 'amount' => $amount);
            if ($return) {
                $result = $this->input->get('result'); //交易状态
                $data['status'] = 'success';
            }
            CView::show('payment/result', $data);
        } else {
            $postData = $this->input->post('notify_data');
            $aliPay = new AliPay();
            $return = $aliPay->notify($postData);
            if ($return !== false || $return) {
                //更新订单状态
                $reVal = $this->_modelPayment->updatePayment($return);
            }
        }
    }


    /**
     * 通知接口
     */
    public function notify()
    {
        $type = (int)$this->input->get('type');
        if ($type == 2) {
            $this->aliPayNotify();
        }
        /*$token = $this->input->get('token');
        $data['amount'] = base64_decode($token);
        CView::show('payment/notify', $data);*/
    }
}
