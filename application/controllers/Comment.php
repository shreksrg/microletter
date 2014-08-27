<?php

/**
 * 留言评论
 */
class Comment extends MicroController
{

    protected $_modelComment;
    protected $_salt = '_hlCharacter';

    public function __construct()
    {
        parent::__construct();
        $this->_modelComment = CModel::make('comment_model');
    }


    /**
     * 评论留言
     */
    public function message()
    {
        $request = $this->input->server('REQUEST_METHOD');
        if ($request == 'GET') {
            $data['orderId'] = $this->input->get('orderId'); //订单id
            $data['payId'] = $this->input->get('payId'); //订单支付项Id
            $data['token'] = sha1($this->_salt . $data['orderId'] . $data['payId']); //用户令牌

            //获取发起人姓名：即订单收件人姓名
            $modelOrder = CModel::make('order_model');
            $shipInfo = $modelOrder->getShipInfo($data['orderId']);
            $data['consignee'] = isset($shipInfo['fullname']) ? $shipInfo['fullname'] : '';

            $view = $data['payId'] > 0 ? 'comment/form_support' : 'comment/form_refuse';
            CView::show($view, $data);
        } else {
            $form = array(); //表单数据
            $orderId = (int)$this->post('orderId');
            $payId = (int)$this->post('payId');
            $token = $this->post('token');

            //验证token
            $_token = sha1($this->_salt . $orderId . $payId);
            if ($_token !== $token) {
                CAjax::show(1000, '验证失败');
            }
            $this->_modelComment->newComment($form); //新增留言
            CAjax::show(0, 'successful');
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