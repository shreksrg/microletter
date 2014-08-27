<?php

class Payment_model extends CI_Model
{
    protected $_errCode = array();
    protected $_orders = array();

    /**
     * 获取订单对象
     */
    public function getOrder($orderId)
    {
        $orderObj = null;
        if (isset($this->_orders[$orderId]))
            $orderObj = $this->_orders[$orderId];
        return $orderObj;
    }

    /**
     * 验证该订单状态
     */
    public function checkOrder($orderId)
    {
        $flag = false;
        $time = time();
        $sql = "select * from mic_order where isdel=0 and status=1 and id=? and expire>? ";

        $query = $this->db->query($sql, array($orderId, $time));
        if ($query->row()) {
            $orderId = $query->row()->id;
            $orderObj = new ItemOrder($orderId, $query);
            $this->_orders[$orderId] = $orderObj;
            $flag = true;
        }
        return $flag;
    }

    /**
     * 创建支付记录项
     */
    public function newPaymentItem($values)
    {
        $data = array(
            'order_id' => $values['orderId'],
            'order_sn' => $values['orderSn'],
            'order_gross' => $values['orderGross'],
            'out_sn' => $values['outSn'],
            'amount' => $values['amount'],
            'type' => $values['type'],
            'pay_time' => time(),
            'status' => 1,
        );
        $this->db->insert('mic_payment_item', $data);
        $newId = (int)$this->db->insert_id();
        if ($newId <= 0) $this->_errCode = 1000; //插入失败
        return $newId;
    }
}