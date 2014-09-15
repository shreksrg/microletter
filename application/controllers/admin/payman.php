<?php

class PayMan extends AdminController
{

    public function __construct()
    {
        parent::__construct();
        $this->_modelPay = CModel::make('admin/payman_model', 'payMan_model');
    }

    public function index()
    {
        $orderId = (int)$this->input->get('orderId');
        CView::show('admin/pay/index', array('orderId' => $orderId));
    }

    /**
     * ajax订单列表
     */
    public function pays()
    {
        $page = (int)$this->input->get('page');
        $rows = (int)$this->input->get('rows');
        $criteria = $this->input->get(); //查询条件
        $list = $this->_modelPay->getList($page, $rows, $criteria);
        echo json_encode($list);
    }

    /**
     * 响应生成refund
     */
    public function refund()
    {
        $r = $this->input->get('r');

        //响应列表页
        if ($r == 'index') {
            CView::show('admin/pay/refund');
            return;
        }

        //生成退款订单
        if ($r == 'gen') {
            $return = $this->_modelPay->newRefund();
            $repArr = array(0, 'successful');
            if ($return <= 0) $repArr = array(1000, 'fail');
            CAjax::show($repArr[0], $repArr[1]);
        }

        //退款列表
        if ($r == 'list') {
            $criteria = $this->input->get();
            $page = (int)$this->input->get('page');
            $rows = (int)$this->input->get('rows');
            $list = $this->_modelPay->refunds($page, $rows, $criteria);
            echo json_encode($list);
        }

        //执行退款
        if ($r == 'do') {
            $id = $this->input->post('id');
            $return = $this->_modelPay->doRefund($id);
            CAjax::show(0, 'successful');
        }
    }


    /**
     * 删除用户
     */
    public function drop()
    {
        $id = $this->input->post('id');
        $return = $this->_modelPay->deletepay($id);
        $repArr = array('0', 'successful');
        if ($return !== true) $repArr = array('1001', 'fail');
        CAjax::show($repArr[0], $repArr[1]);
    }


}