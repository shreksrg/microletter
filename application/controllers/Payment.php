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
     * 支付确认
     */
    public function confirm()
    {
        $data = array();
        CView::show('payment/confirm', $data);
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
            $data = array();
            CView::show('payment/confirm', $data);
        } else {
            //生成支付提交表单
            $orderId = (int)$this->input->get('orderId');
            $type = (int)$this->input->get('type'); //支付方式
            $payItem = $this->_modelPayment->newPayItem($orderId, $type); //产生支付项数据

            if ($type == 1) {
                $form = AliPay::form($payItem);
                CView::show('payment/formAliPay', $form);
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
}
