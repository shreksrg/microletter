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
            $form = $this->input->post(); //表单数据
            $orderId = (int)$this->input->post('orderId');
            $payId = (int)$this->input->post('payId');
            $token = $this->input->post('token');

            //验证token
            $_token = sha1($this->_salt . $orderId . $payId);
            if ($_token !== $token) {
                CAjax::show(-1, '验证失败');
            }
            $newId = $this->_modelComment->newComment($form); //新增留言
            $code = 0;
            $message = 'successful';
            if ($newId == 0) {
                $code = $this->_modelComment->getErrCode();
                switch ($code) {
                    case 1001:
                        $message = '你评论的挑战不存在';
                        break;
                    case 1002:
                        $message = '您已经评论过';
                        break;
                    case 1000:
                        $message = '系统繁忙,请稍候再试';
                        break;
                }
            }
            CAjax::show($code, $message);
        }
    }

    /**
     * 显示订单留言列表
     */
    public function show()
    {
        //验证用户登录
        if ($this->_user->isGuest) {
            header('location:' . SITE_URL . '/login?reUrl=comment/show');
            exit;
        }

        $data = array();
        $orderId = (int)$this->input->get('orderId');
        $type = (int)$this->input->get('type'); //类型，0=放弃；1=支持
        $modOrder = CModel::make('order_model');
        $order = $modOrder->genOrder($orderId);
        if ($order->row) {
            if ($this->_user->id != $order->row->user_id) {
                header('location:' . SITE_URL . '/item');
                exit;
            }

            $modItem = CModel::make('planItem_model');
            $planItem = $modItem->genItem($order->row->item_id);
            $data['quota'] = $planItem->row->quota; //限定支付总人数
            $data['supports'] = $modOrder->getSupports($orderId); //支付人数
            $args = array('orderId' => $orderId, 'type' => $type, 'page' => 1, 'offset' => 10);
            $data['comments'] = $this->_modelComment->getComments($args); // 订单评论列表
            CView::show('comment/show', $data);
        }
    }


    /**
     * ajax输出列表
     */
    public function  getList()
    {

        //验证用户登录
        if ($this->_user->isGuest)
            CAjax::show(1000, '登录超时');

        $data = array();
        $orderId = (int)$this->input->get('orderId');
        $type = (int)$this->input->get('type'); //类型，0=放弃；1=支持
        $page = (int)$this->input->get('page'); //页数

        $modOrder = CModel::make('order_model');
        $order = $modOrder->genOrder($orderId);
        if ($order->row) {
            if ($this->_user->id != $order->row->user_id) {
                CAjax::show(1002, '用户项目不匹配');
                exit;
            }
            $args = array('orderId' => $orderId, 'type' => $type, 'page' => $page, 'offset' => 10);
            $data['comments'] = $this->_modelComment->getComments($args); // 订单评论列表
            CAjax::show(0, 'successful', $data);

        } else {
            CAjax::show(1001, '项目不存在');
        }
    }

}