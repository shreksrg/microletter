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
    public function newPayItem($orderObj, $type)
    {
        $orderRow = $orderObj->row;
        $orderId = $orderRow->id;
        // 获取订单商品
        $sql = "select title from mic_order_goods where isdel=0 and order_id=$orderId order by add_time desc limit 1";
        $query = $this->db->query($sql);
        $payTitle = ($row = $query->row()) ? $row->title : '';

        //获取项目订单
        $amount = ($orderRow->gross / $orderRow->quota); // 支付金额

        //产生支付项id
        $paySn = UUID::fast_uuid(4);
        $value = array(
            'order_id' => $orderRow->id, //项目项订单Id
            'order_sn' => $orderRow->sn, //项目项订单编码
            'pay_sn' => $paySn, // 支付项编码
            'type' => $type, // 支付类型
            'status' => 1, // 支付状态,开发状态为已经支付，生产环境请改为0
            'amount' => $amount, //支付金额
            'add_time' => time()
        );

        $reBoolean = $this->db->insert('mic_payment_item', $value);
        if ($reBoolean === true) {
            $value['pay_id'] = $this->db->insert_id();
            $value['pay_title'] = $payTitle;
            return $value;
        }
        return false;
    }

    /**
     * 更新支付项状态
     */
    public function updatePayItem($data)
    {
        $status = $data['status'];
        $paySn = $data['paySn'];
        $outSn = $data['tradeSn'];
        $now = time();
        $value = array(
            'status' => $status,
            'out_sn' => $outSn,
            'update_time' => $now,
            'pay_time' => $status == 1 ? $now : null,
        );

        $condition = array('pay_sn' => $data['payNo']);
        $this->db->update('mic_payment_item', $value, $condition);

        //更新订单支付人数
        if ($status == 1) {
            $sql = "select order_id from mic_payment_item where pay_sn=$paySn";
            $query = $this->db->query($sql);
            if ($query->row()) {
                $orderId = $query->row()->order_id;
                $sql = "update mic_order set paids=paids+1,update_time=$now where id=$orderId";
                $this->db->query($sql);
            }
        }
        return true;
    }
}