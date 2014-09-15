<?php

/**
 * 筹资项目控制器类
 */
class Panel extends AdminController
{
    public $_modelItem;

    public function __construct()
    {
        parent::__construct();
        // $this->_modelItem = CModel::make('admin/index');
    }



    /**
     * 项目列表首页
     */
    public function index()
    {
        CView::show('admin/index', array());
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
     * 项目商品详情
     */
    public function detail()
    {
        $id = $this->input->get('id', true);
        $data['detail'] = $this->_modelGoods->getDetail($id);
        CView::show('goods/detail', $data);
    }
}