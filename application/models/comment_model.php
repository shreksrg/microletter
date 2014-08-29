<?php

class Comment_model extends CI_Model
{
    protected $_errCode = array();

    public function getErrCode()
    {
        return $this->_errCode;
    }

    public function newComment($data)
    {
        $newId = 0;
        $orderId = (int)$data['orderId'];
        $payId = (int)$data['payId'];

        //支付项验证
        if ($payId > 0) {
            $this->db->select('id');
            $query = $this->db->get_where('mic_payment_item', array('isdel' => 0, 'id' => $payId, 'order_id' => $orderId), 0, 1);
            if (!$query->row()) {
                $this->_errCode = 1001; // 不存在订单支付项
                return $newId;
            } else {
                //验证是否已经评论
                $sql = "select count(*) as num from mic_comment where isdel=0 and order_id=? and pay_id=?";
                $query = $this->db->query($sql, array($orderId, $payId));
                $num = (int)$query->row()->num;
                if ($num > 0) {
                    $this->_errCode = 1002; // 该支持（支付项）已经评论
                    return $newId;
                }
            }
        }

        $values = array(
            'order_id' => $orderId,
            'pay_id' => $payId,
            'fullname' => isset($data['fullName']) ? $data['fullName'] : '',
            'type' => $payId > 0 ? 1 : 0,
            'comment' => $data['content'],
            'add_time' => time()
        );
        $this->db->insert('mic_comment', $values);
        $newId = $this->db->insert_id();
        if ($newId <= 0) $this->_errCode = 1000; //插入失败
        return $newId;
    }


    public function getComments($args)
    {
        $page = (int)$args['page'];
        $offset = isset($args['offset']) ? (int)$args['offset'] : 10;
        if ($page <= 1) $page = 1;
        $page--;
        $comments = array();
        $sql = "select * from mic_comment where isdel=0 and order_id=? and type=? order by add_time desc limit ?,?";
        $query = $this->db->query($sql, array($args['orderId'], $args['type'], $page, $offset));
        if ($query->row())
            $comments = $query->result_array();
        return $comments;
    }


}