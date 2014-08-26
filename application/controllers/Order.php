<?php

/**
 * 订单处理控制器类
 */
class Order extends MicroController
{
    public $_modelOrder;

    public function __construct()
    {
        parent::__construct();
        $this->_modelOrder = CModel::make('order_model');
    }

    public function _authentication()
    {
        $controller = $this->uri->rsegment(1);
        $method = $this->uri->rsegment(2);
        $url = $controller . '/' . $method;
        if ($this->_user->isGuest) {
            header('location:' . SITE_URL . '/login?reUrl=' . $url);
            die(0);
        }
    }

    /**
     * 申请订单，填写并提交收件人信息
     */
    public function apply()
    {
        $request = $this->input->server('REQUEST_METHOD');
        if ($request == 'POST') {
            $form = array();
            $form['itemId'] = $this->input->post('itemId', true); //商品id
            $form['message'] = $this->input->post('message', true);
            $form['address'] = $this->input->post('address', true);
            $form['mobile'] = $this->input->post('mobile', true);
            $form['fullName'] = $this->input->post('fullName', true);
            $captcha = $this->input->post('captcha', true);

            // 表单输入验证
            $validator = FormValidation::make();
            $validator->set_rules('itemId', 'ItemId', 'required|is_natural_no_zero|xss_clean');
            $validator->set_rules('address', 'Address', 'required|xss_clean');
            $validator->set_rules('mobile', 'Mobile', 'required|numeric|max_length[12]|xss_clean');
            $validator->set_rules('fullName', 'FullName', 'required|xss_clean');
            $validator->set_rules('captcha', 'Captcha', 'required|alpha_numeric|xss_clean');

            if ($validator->run() == false)
                CAjax::show('1000', '表单输入值不合法');

            $rtValid = $this->_modelOrder->matchMobileCaptcha($form['mobile'], $captcha); // 手机验证码匹配识别
            if ($rtValid === true) {
                $uid = $this->_user->id;
                $orderId = $this->_modelOrder->apply($this->_user, $form);
                if ($orderId > 0) CAjax::show(0, 'successful', array('orderId' => $orderId));
                else  CAjax::show($this->_modelOrder->getErrCode('apply'), '订单创建失败');
            } else CAjax::show(1100, '手机验证码错误');
        } else {
            $itemId = (int)$this->input->cookie("itemId");
            if ($itemId > 0) {
                CView::show('order/form', array('itemId' => $itemId));
            }
        }
    }

    /**
     * 订单确认
     */
    public function confirm()
    {
        $orderId = $this->input->get('orderId', true);
        $data['info'] = $this->_modelOrder->getOrderInfo($orderId);
        $data['consignee'] = $this->_modelOrder->getShipInfo($orderId);

        CView::show('order/confirm', $data);
    }

    /**
     * 发起人查阅订单详情
     */
    public function status()
    {
        $data = $this->_modelOrder->getStatus($this->_user);
        $state = $data['state'];
        CView::show('order/status_' . $state, $data);
    }

    /**
     * 支付订单项目详情页
     */
    public function paymentItem()
    {
        $id = (int)$this->input->get('id', true);
        $data = $this->_modelOrder->getOrderInfo($id);
        CView::show('order/payment_item', $data);
    }
}