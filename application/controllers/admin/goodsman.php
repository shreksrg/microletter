<?php

class GoodsMan extends AdminController
{

    public function __construct()
    {
        parent::__construct();
        $this->_modelGoodsMan = CModel::make('admin/goodsman_model', 'goodsman_model');
    }

    public function index()
    {
         CView::show('admin/goods/index');
    }

    public function goods()
    {
        $page = (int)$this->input->get('page');
        $rows = (int)$this->input->get('rows');
        $list = $this->_modelGoodsMan->getList($page, $rows);
        echo json_encode($list);
    }

    /**
     * 新增商品
     */
    public function append()
    {
        if (REQUEST_METHOD == 'POST') {
            $data = $this->input->post();

            // 表单输入验证
            $validator = $this->validateForm();
            if ($validator->run() == false)
                CAjax::show('1000', '表单输入值不合法,请检查');

            $code = 0;
            $message = 'successful';
            $newId = $this->_modelGoodsMan->newGoods($data);
            if ($newId <= 0) {
                $code = 1000;
                $message = 'failure';
            }
            CAjax::show($code, $message);
        } else {
            CView::show('admin/goods/new');
        }
    }

    /**
     * 编辑商品
     */
    public function edit()
    {
        if (REQUEST_METHOD == 'POST') {
            $data = $this->input->post();
            // 表单输入验证
            $validator = $this->validateForm();
            if ($validator->run() == false)
                CAjax::show('1000', '表单输入值不合法,请检查');
            $reVal = $this->_modelGoodsMan->editGoods($data);
            $repArr = array('0', 'successful');
            if ($reVal !== true) $repArr = array('1001', 'fail');
            CAjax::show($repArr[0], $repArr[1]);
        } else {
            $id = $this->input->get('id');
            $row = $this->_modelGoodsMan->getGoodsRow($id);
            $data['info'] = (array)$row;
            CView::show('admin/goods/edit', $data);
        }
    }

    /**
     * 编辑商品
     */
    public function drop()
    {
        $id = $this->input->post('id');
        $return = $this->_modelGoodsMan->deleteGoods($id);
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