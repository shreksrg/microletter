<?php

class OrderMan extends AdminController
{

    public function __construct()
    {
        parent::__construct();
        $this->_modelOrder = CModel::make('admin/orderman_model', 'orderMan_model');
    }

    public function index()
    {
         CView::show('admin/order/index');
    }

    /**
     * ajax订单列表
     */
    public function orders()
    {
        $page = (int)$this->input->get('page');
        $rows = (int)$this->input->get('rows');
        $criteria = $this->input->get(); //查询条件
        $list = $this->_modelOrder->getList($page, $rows, $criteria);
        echo json_encode($list);
    }


    /**
     * 编辑订单
     */
    public function edit()
    {
        if (REQUEST_METHOD == 'POST') {
            $data = $this->input->post();
            // 表单输入验证
            /*$validator = $this->validateForm();
            if ($validator->run() == false)
                CAjax::show('1000', '表单输入值不合法,请检查');*/
            $reVal = $this->_modelOrder->editOrder($data);
            $repArr = array('0', 'successful');
            if ($reVal !== true) $repArr = array('1001', 'fail');
            CAjax::show($repArr[0], $repArr[1]);
        } else {
            $id = $this->input->get('id');
            $data['order'] = $this->_modelOrder->getOrder($id);
            CView::show('admin/order/edit', $data);
        }
    }

    /**
     * 删除用户
     */
    public function drop()
    {
        $id = $this->input->post('id');
        $return = $this->_modelOrder->deleteOrder($id);
        $repArr = array('0', 'successful');
        if ($return !== true) $repArr = array('1001', 'fail');
        CAjax::show($repArr[0], $repArr[1]);
    }

    /**
     * 编辑密码
     */
    public function  password()
    {
        $id = $this->input->get('id');
        $password = $this->input->post('password');
        $return = $this->_modelOrder->setPassword($id, $password);
        $repArr = array('0', 'successful');
        if ($return !== true) $repArr = array('1001', 'fail');
        CAjax::show($repArr[0], $repArr[1]);
    }

    protected function  validateForm()
    {
        $validator = FormValidation::make();
        $validator->set_rules('title', 'Title', 'required|xss_clean');
        $validator->set_rules('source', 'Source', 'required|numeric|xss_clean');
        $validator->set_rules('price', 'Price', 'required|numeric|xss_clean');
        $validator->set_rules('img', 'Img', 'required|max_length[512]|xss_clean');
        $validator->set_rules('status', 'Status', 'required|integer|xss_clean');
        $validator->set_rules('desc', 'Desc', 'required|xss_clean');
        return $validator;
    }



}