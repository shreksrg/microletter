<?php

class Payment_model extends CI_Model
{
    protected $_errCode = 0;
    protected $_errCodes = array();
    protected $_orders = array();

    public function setErrCode($code)
    {
        $this->_errCode = $code;
    }

    public function getErrCode()
    {
        return $this->_errCode;
    }

    /**
     * 新增支付项
     */
    public function newPayItem($orderId, $type)
    {
        $modelOrder = CModel::make('order_model');
        $orderObj = $modelOrder->genOrder($orderId);

        //订单状态
        $state = $modelOrder->getState($orderObj);
        if ($state != 'on') {
            $this->setErrCode(1003); //订单过期或已经完成
            return false;
        }

        $orderRow = $orderObj->row;

        // 获取订单商品
        $sql = "select title from mic_order_goods where isdel=0 order_id=$orderId limit 1";
        $query = $this->db->query($sql);
        $pay_title = ($row = $query->row()) ? $row->title : '';

        //获取项目订单
        $amount = ($orderRow->gross / $orderRow->quota); // 支付金额

        //产生支付项id
        $paySn = UUID::fast_uuid(4);
        $value = array(
            'order_id' => $orderRow->id, //项目项订单Id
            'order_sn' => $orderRow->sn, //项目项订单编码
            'pay_sn' => $paySn, // 支付项编码
            'type' => 2, // 支付类型
            'status' => 1, // 支付状态,开发状态为已经支付，生产环境请改为0
            'amount' => $amount, //支付金额
            'add_time' => time()
        );

        $reBoolean = $this->db->insert('mic_payment_item', $value);
        if ($reBoolean === true) {
            $value['pay_id'] = $this->db->insert_id();
            $value['pay_title'] = $pay_title;
            return $value;
        }
        return false;
    }

    /**
     * 更新支付项状态
     */
    public function updatePayment($data)
    {
        return true;
    }

}