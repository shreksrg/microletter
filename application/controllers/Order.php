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
        $filter = array('apply');
        $controller = $this->uri->rsegment(1);
        $method = $this->uri->rsegment(2);
        $url = $controller . '/' . $method;
        if ($this->_user->isGuest) {
            if (!in_array($method, $filter)) {
                header('location:' . SITE_URL . '/login?reUrl=' . $url);
                die(0);
            }
        }
    }

    /**
     * 申请订单，填写并提交收件人信息
     */
    public function apply()
    {
        if (!$this->_user->isGuest) { //用户已登录
            $userId = $this->_user->id;
            $isExists = $this->_modelOrder->hasItemExists($userId);
            if ($isExists === false) {
                $this->_modelOrder->memberApplyOrder($this->_user);
            } else  echo '您已经发了挑战，挑战未结束前不能再发起.</br> <a href="' . SITE_URL . '/order/status">查看我的挑战</a> </br> <a href="' . SITE_URL . '/login/logout">退出登录</a> ';

        } else {
            $request = $this->input->server('REQUEST_METHOD');
            if ($request == 'POST') {
                $form = array();
                $form['itemId'] = $this->input->post('itemId', true); //项目id
                $form['message'] = $this->input->post('message');
                $form['address'] = $this->input->post('address', true);
                $form['mobile'] = $this->input->post('mobile', true);
                $form['fullName'] = $this->input->post('fullName', true);
                $form['captcha'] = $captcha = $this->input->post('captcha', true);

                // 表单输入验证
                $validator = FormValidation::make();
                $validator->set_rules('itemId', 'ItemId', 'required|is_natural_no_zero|xss_clean');
                $validator->set_rules('address', 'Address', 'required|xss_clean');
                $validator->set_rules('mobile', 'Mobile', 'required|numeric|max_length[12]|xss_clean');
                $validator->set_rules('fullName', 'FullName', 'required|xss_clean');
                $validator->set_rules('captcha', 'Captcha', 'required|alpha_numeric|xss_clean');

                if ($validator->run() == false)
                    CAjax::show('1000', '表单输入值不合法');

                //验证手机是否已经注册
                $sql = "select id from mic_user where isdel=0 and mobile=?";
                $query = $this->db->query($sql, array($form['mobile']));
                if ($query->row()) {
                    CAjax::show(1099, '手机号已经注册');
                    exit;
                }

                $rtValid = $this->_modelOrder->matchMobileCaptcha($form['mobile'], $captcha); // 手机验证码匹配识别
                if ($rtValid === true) {
                    $uid = $this->_user->id;
                    $orderId = $this->_modelOrder->apply($this->_user, $form); //执行申请
                    if ($orderId > 0) CAjax::show(0, 'successful', array('orderId' => $orderId));
                    else  CAjax::show($this->_modelOrder->getErrCode('apply'), '订单创建失败');
                } else CAjax::show(1100, '手机验证码错误');
            } else {
                $itemId = (int)$this->input->cookie("itemId");
                $mobile = $this->input->cookie('_urMobile'); //用户注册手机号
                CView::show('order/form', array('itemId' => $itemId)); //显示注册表单（填写收件信息）
                return false;
                if (!isset($mobile)) {
                    CView::show('order/form', array('itemId' => $itemId)); //显示注册表单（填写收件信息）
                } else {
                    echo '请到新闻晨报登录后进行发起挑战';
                }

                return false;
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
        if ($state == 'none') {
            echo '您尚未发起挑战,<a href="' . SITE_URL . '/item">去发起挑战</a>';
        } else CView::show('order/status_' . $state, $data);

    }
}