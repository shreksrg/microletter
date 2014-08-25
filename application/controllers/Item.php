<?php

/**
 * 筹资项目控制器类
 */
class Item extends MicroController
{
    public $_modelItem;

    public function __construct()
    {
        parent::__construct();
        $this->_modelItem = CModel::make('planItem_model');
    }

    /**
     * 项目列表首页
     */
    public function index()
    {
        $page = $this->input->get('page', true);
        $data['list'] = $this->_modelItem->getList($page);
        CView::show('item/index', $data);
    }

    /**
     * ajax获取项目列表
     */
    public function getList()
    {
        $page = $this->input->get('page', true);
        $data = $this->_modelItem->getList($page);

        //格式化为json输出
        $list = array();
        if ($data) {
            foreach ($data as $itemId => $planItem) {
                $list[$itemId] = array(
                    'item' => $planItem->row,
                    'goods' => $planItem->goods->rows,
                );
            }
        }
        CAjax::show(0, 'successful', $list);
    }

    /**
     * 项目详情
     */
    public function detail()
    {
        $id = (int)$this->input->get('id', true);
        $item = $this->_modelItem->getDetail($id);
        CView::show('item/detail', array('item' => $item));
    }

    /**
     * 响应返回项目订购页
     */
    public function itemOrder()
    {
        $orderId = (int)$this->input->get('id');
        $modOrder = CModel::make('order_model');
        $info = $modOrder->getOrderInfo($orderId);
        CView::show('item/order', array('info' => $info));
    }
}