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
        echo CView::show('admin/goods/index', true);
    }

    public function goods()
    {
        $page = (int)$this->input->get('page');
        $rows = (int)$this->input->get('rows');
        $list = $this->_modelGoodsMan->getList($page, $rows);
        echo json_encode($list);
    }
}