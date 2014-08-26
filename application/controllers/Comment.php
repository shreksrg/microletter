<?php

/**
 * 留言评论
 */
class Comment extends MicroController
{

    protected $_modelComment;

    public function __construct()
    {
        parent::__construct();
        $this->_modelComment = CModel::make('comment_model');
    }

    public function commentOrder()
    {
        $request = $this->server('REQUEST_METHOD');
        if ($request == 'GET') {
            $data['orderId'] = (int)$this->input->get('orderId'); //订单id
            $data['payId'] = (int)$this->input->get('payId'); //订单支付项Id
            $data['token'] = (int)$this->input->get('token'); //用户令牌
            $view = $data['payId'] > 0 ? 'comment/support' : 'comment/nonsupport';
            CView::show($view, $data);
        } else {
            $form = array(); //表单数据
            $orderId = (int)$this->post('orderId');
            $payId = (int)$this->post('payId');

            if ($form['token'] !== $_SESSION['_comment_token']) {
                header('location:' . SITE_URL . '/item');
                exit;
            }
            $this->_modelComment->newComment($form); //新增留言
            unset($_SESSION['_comment_token']);
            header('location:' . SITE_URL . '/comment/show?id=' . $orderId);
        }
    }

    /**
     * 显示订单留言列表
     */
    public function show()
    {
        $data = array();
        $orderId = (int)$this->input->get('id');
        $modOrder = CModel::make('order_model');

        $order = $modOrder->genOrder($orderId);
        if ($order->row) {
            $modItem = CModel::make('planItem_model');
            $planItem = $modItem->genItem($order->row->item_id);
            $data['quota'] = $planItem->row->quota; //限定支付总人数
            $data['supports'] = $modOrder->getSupports($orderId); //支付人数
            // $data['leftTime'] = $modOrder->formatLeftTime($order->getLeftTime()); //剩余时间
            $data['comments'] = $this->_modelComment->getCommentsByOrderId($orderId); // 订单评论列表
            CView::show('comment/show', $data);
        }
    }
}