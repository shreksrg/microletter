<?php

class ItemMan extends AdminController
{

    public function __construct()
    {
        parent::__construct();
        $this->_modelItem = CModel::make('admin/itemman_model', 'itemman_model');
    }

    public function index()
    {
         CView::show('admin/item/index');
    }

    /**
     * ajax项目列表
     */
    public function items()
    {
        $page = (int)$this->input->get('page');
        $rows = (int)$this->input->get('rows');
        $list = $this->_modelItem->getList($page, $rows);
        echo json_encode($list);
    }

    /**
     * 新增项目
     */
    public function append()
    {
        if (REQUEST_METHOD == 'POST') {
            $data = $this->input->post();

            // 表单输入验证
            /* $validator = $this->validateForm();
             if ($validator->run() == false)
                 CAjax::show('1000', '表单输入值不合法,请检查');*/

            $repArr = array(0, 'successful');
            $reVal = $this->_modelItem->newItem($data);
            if ($reVal !== true) {
                $repArr[0] = $this->_modelItem->errCode;
                $repArr[1] = '创建项目失败';
            }
            CAjax::show($repArr[0], $repArr[1]);
        } else {
            CView::show('admin/item/new');
        }
    }

    /**
     * 编辑项目
     */
    public function edit()
    {
        if (REQUEST_METHOD == 'POST') {
            $data = $this->input->post();
            // 表单输入验证
            /*$validator = $this->validateForm();
            if ($validator->run() == false)
                CAjax::show('1000', '表单输入值不合法,请检查');*/

            
            $reVal = $this->_modelItem->editItem($data);
            $repArr = array('0', 'successful');
            if ($reVal !== true) $repArr = array('1001', 'fail');
            CAjax::show($repArr[0], $repArr[1]);
        } else {
            $id = $this->input->get('id');
            $data = $this->_modelItem->getItem($id);
            CView::show('admin/item/edit', $data);
        }
    }

    /**
     * 编辑商品
     */
    public function drop()
    {
        $id = $this->input->post('id');
        $return = $this->_modelItem->deleteItem($id);
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

    /**
     * 响应商品列表
     */
    public function select()
    {
        // $modelGoods = CModel::make('admin/goods', 'goodsMan_model');
        // $data = $modelGoods->getList(1, 10);
        CView::show('admin/item/goods');
    }
}