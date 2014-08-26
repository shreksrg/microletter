<?php

class Comment_model extends CI_Model
{
    protected $_errCode = array();

    public function newComment($data)
    {
        $newId = 0;
        $orderId = (int)$data['orderId'];
        $payId = (int)$data['payId'];

        //支付项验证
        if ($payId > 0) {
            $this->db->select('id');
            $query = $this->db->get_where('mic_payment_item', array('isdel' => 0, 'id' => $payId, 'order_id' => $orderId), 0, 1);
            if ($query->num_rows < 0) {
                $this->_errCode = 1000; // 不存在订单支付项
                return $newId;
            }
        }

        $this->db->select('id');
        $data = array(
            'order_id' => $orderId,
            'pay_id' => $payId,
            'fullname' => $data['fullName'],
            'type' => $payId > 0 ? 1 : 0,
            'comment' => $data['content'],
            'add_time' => time()
        );
        $this->db->insert('mic_comment', $data);
        $newId = $this->db->insert_id();
        if ($newId < 0) $this->_errCode = 1001; //插入失败
        return $newId;
    }


    public function getCommentsByOrderId($orderId, $type)
    {
        $sql = "select * from mic_comment where isdel=0 and order_id=? and type=?";
        $query = $this->db->query($sql, array($orderId, $type));
        if ($query->row()) {
            foreach ($query->result() as $row) {
                $diffTime = (time() - $row->add_time());
                
            }
        }

    }
}