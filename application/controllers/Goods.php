<?php

class Goods extends MicroController
{
    public $_modelGoods;

    public function __construct()
    {
        parent::__construct();
        $this->_modelGoods = CModel::set('goods_model');
    }

    /**
     * 商品列表
     */
    public function index()
    {
        $page = $this->input->get('page', true);
        $data['list'] = $this->_modelGoods->getList($page);
        CView::show('goods/index', $data);
    }

    /**
     * ajax获取商品列表
     */
    public function getList()
    {
        $page = $this->input->get('page', true);
        $list = $this->_modelGoods->getList($page);
        CAjax::show(0, 'successful', $list);
    }

    public function detail()
    {
        $id = $this->input->get('id', true);
        $data['detail'] = $this->_modelGoods->getDetail($id);
        CView::show('goods/detail', $data);
    }
}