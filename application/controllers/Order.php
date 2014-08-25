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
        $this->_modelOrder = CModel::set('order_model');
    }

    public function _authentication()
    {
        if ($this->_user->isGuest) {
            header('location:' . SITE_URL . '/login');
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
            $form['goodsId'] = $this->input->post('gid', true); //商品id
            $form['message'] = $this->input->post('message', true);
            $form['address'] = $this->input->post('address', true);
            $form['mobile'] = $this->input->post('mobile', true);
            $form['fullName'] = $this->input->post('fullName', true);
            $captcha = $this->input->post('captcha', true);

            // 表单输入验证
            $validator = FormValidation::make();
            $validator->set_rules('gid', 'goodsId', 'required|is_natural_no_zero|xss_clean');
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
                if ($orderId > 0) header('location:' . SITE_URL . '/order/detail?id=' . $orderId);
                else  CAjax::show($this->_modelOrder->getLogs('apply'), '订单创建失败');
            }
        } else {
            $goodsId = $this->input->get('id', true); //获取商品Id
            if (($goodsId = (int)$goodsId) > 0) {
                CView::show('order/form', array('goodsId' => $goodsId));
            }
        }
    }

    /**
     * 订单确认
     */
    public function confirm()
    {
        $id = $this->input->get('id', true);
        $data['detail'] = $this->_modelOrder->getDetail($id);
        CView::show('order/confirm', $data);
    }

    /**
     * 支付订单项目详情页
     */
    public function paymentItem()
    {
        $id = (int)$this->input->get('id', true);
        $data['detail'] = $this->_modelOrder->getOrderInfo($id);
        CView::show('order/payment_item', $data);
    }
}