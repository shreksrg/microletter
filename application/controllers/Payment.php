<?php

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


    // 支付宝
    protected function aliPay($orderObj)
    {
        $orderRow = $orderObj->row;
        $itemId = $orderRow->item_id;
        $modelItem = CModel::make('planItem_model');
        $itemObj = $modelItem->genItem($itemId);

        $outSn = UUID::fast_uuid(6); //外部交易号,由支付宝返回
        $amount = ($orderRow->gross / $itemObj->row->quota); // 支付金额

        //产生支付项id
        $orderRow = $orderObj->row;
        $payItems = array(
            'orderId' => $orderRow->id,
            'orderSn' => $orderRow->sn,
            'orderGross' => $orderRow->gross,
            'outSn' => $outSn,
            'amount' => $amount,
            'type' => 2, //支付宝支付
        );
        return $this->_modelPayment->newPaymentItem($payItems);
    }

    //银联支付
    protected function unionPay()
    {

    }

    // 微信支付
    protected function microPay()
    {

    }


    //其他支付
    protected function otherPay()
    {

    }
}
