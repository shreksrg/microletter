<?php

/**
 * 筹资项目管理
 */
class PayMan_model extends CI_Model
{
    protected $_errCode = 0;

    public function setErrCode($code)
    {
        return $this->_errCode = $code;
    }

    public function getErrCode()
    {
        return $this->_errCode;
    }

    public function getOrder($id)
    {
        $id = (int)$id;
        $query = $this->db->query("select id, gross,quota,status,expire,desc from mic_order where isdel=0 and id=$id");
        return $query->row_array();
    }

    /**
     * 获取支付项目订单列表
     */
    public function getList($page, $rows = 20, $criteria)
    {
        $page = (int)$page;
        if ($page <= 0) $page = 1;
        $start = ($page - 1) * $rows;
        $list = array('total' => 0, 'rows' => array());
        $where = chr(32);

        $orderId = (int)$criteria['orderId'];


        $sqlCount = "select count(*) as num from mic_payment_item where isdel=0 and  order_id=$orderId";
        $query = $this->db->query($sqlCount);
        $total = (int)$query->row()->num;


        if ($total > 0) {
            $sql = "select * from mic_payment_item where isdel=0 and order_id=$orderId order by add_time desc limit $start,$rows";
            $query = $this->db->query($sql);
            $list['total'] = $total;
            $list['rows'] = $query->result_array();
        }
        return $list;

    }

    /**
     * 编辑订单
     */
    public function editOrder($data)
    {
        $id = (int)$data['id'];
        $value = array(
            'gross' => $data['gross'],
            'quota' => $data['quota'],
            'expire' => strtotime($data['expire']),
            'status' => $data['status'],
            'desc' => $data['desc'],
            'update_time' => time(),
        );
        $reVal = $this->db->update('mic_order', $value, array('id' => $id));
        return $reVal;
    }

    /**
     * 删除项目
     */
    public function deleteOrder($id)
    {
        $id = is_array($id) ? implode(',', $id) : (int)$id;
        $sql = "update mic_order set isdel=1 where isdel=0 and id in($id)";
        $return = $this->db->query($sql);
        return $return;
    }


    /**
     * 生成退款订单记录
     */
    public function newRefund()
    {
        $sql = "select max(pay_time) as pay_time from mic_refund where isdel=0 limit 1 ";
        $query = $this->db->query($sql);
        $lastTime = (int)$query->row()->pay_time;

        $return = 0;
        $now = time();
        $sql = "select p1.* from mic_order as o1 inner join mic_payment_item as p1 on o1.id=p1.order_id
                    where o1.isdel=0 and p1.isdel=0 and o1.status>0 and p1.status=1 and p1.pay_time>0 and o1.expire<=$now and o1.quota<=paids and p1.pay_time>$lastTime
                    order  by p1.pay_time desc";
        $query = $this->db->query($sql);
        if ($query->row()) {
            $values = array();
            $count = 0;
            $now = time();
            foreach ($query->result_array() as $row) {
                $sn = '0' . UUID::fast_uuid();
                $values[] = array(
                    'refund_sn' => $sn,
                    'pay_id' => $row['id'],
                    'pay_sn' => $row['pay_sn'],
                    'pay_time' => $row['pay_time'],
                    'type' => $row['type'],
                    'out_sn' => $row['out_sn'],
                    'amount' => $row['amount'],
                    'add_time' => $now,
                );
            }
            $return = $this->db->insert_batch('mic_refund', $values);
        }
        return $return;
    }

    public function refunds($page, $rows = 20, $criteria)
    {
        $page = (int)$page;
        if ($page <= 0) $page = 1;
        $start = ($page - 1) * $rows;
        $list = array('total' => 0, 'rows' => array());
        $where = chr(32);

        //支付单号
        if (isset($criteria['pay_sn']) && strlen(($orderSn = $criteria['pay_sn']))) {
            $where .= " and sn like '$orderSn%'";
        }

        //退款单号
        if (isset($criteria['refund_sn']) && strlen(($refundSn = $criteria['refund_sn']))) {
            $where .= " and sn like '$refundSn%'";
        }

        //退款状态
        if (isset($criteria['status']) && strlen(($status = (int)$criteria['status']))) {
            $where .= " and status=$status";
        }

        //支付起始日期
        if (isset($criteria['pb_time']) && strlen(($abTime = $criteria['pb_time']))) {
            $abTime = strtotime($abTime);
            $where .= " and pay_time>=$abTime";
        }

        //支付截止日期
        if (isset($criteria['pe_time']) && strlen(($aeTime = $criteria['pe_time']))) {
            $aeTime = strtotime($aeTime);
            $where .= " and pay_time<=$aeTime";
        }

        //退款截止起始日期
        if (isset($criteria['rb_time']) && strlen(($ebTime = $criteria['rb_time']))) {
            $ebTime = strtotime($ebTime);
            $where .= " and refund_time>=$ebTime";
        }

        //退款截止终止日期
        if (isset($criteria['re_time']) && strlen(($eeTime = $criteria['re_time']))) {
            $eeTime = strtotime($eeTime);
            $where .= " and refund_time<=$eeTime";
        }

        //订单总价起始值
        if (isset($criteria['min_amount']) && strlen(($amount = $criteria['min_amount']))) {
            $where .= " and amount>=$amount";
        }

        //订单总价终止值
        if (isset($criteria['max_amount']) && strlen(($amount = $criteria['max_amount']))) {
            $where .= " and amount<=$amount";
        }

        $sqlCount = "select count(*) as num from mic_refund where isdel=0" . chr(32) . $where;
        $query = $this->db->query($sqlCount);
        $total = (int)$query->row()->num;

        if ($total > 0) {
            $sql = "select * from mic_refund where isdel=0 $where  order by pay_time desc limit $start,$rows";
            $query = $this->db->query($sql);
            $list['total'] = $total;
            $list['rows'] = $query->result_array();
        }
        return $list;
    }
}