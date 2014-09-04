<?php

/**
 * 筹资项目管理
 */
class OrderMan_model extends CI_Model
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
     * 获取项目订单列表
     */
    public function getList($page, $rows = 20, $criteria)
    {
        $page = (int)$page;
        if ($page <= 0) $page = 1;
        $start = ($page - 1) * $rows;
        $list = array('total' => 0, 'rows' => array());
        $where = chr(32);

        //订单编码
        if (isset($criteria['order_sn']) && strlen(($orderSn = $criteria['order_sn']))) {
            $where .= " and sn like '$orderSn%'";
        }

        //订单状态
        if (isset($criteria['status']) && strlen(($status = $criteria['status']))) {
            $now = time();
            if ($status == 0) {
                $where .= " and status=0";
            } elseif ($status == 1) {
                $where .= " and status>0 and expire>$now and paids<quota";
            } elseif ($status == 2) { //支付成功
                $where .= " and status>0 and paids>=quota";
            } elseif ($status == 3) { //支付未成功
                $where .= " and status>0 and expire<=$now and  paids<quota";
            }
        }

        //下单起始日期
        if (isset($criteria['ab_time']) && strlen(($abTime = $criteria['ab_time']))) {
            $abTime = strtotime($abTime);
            $where .= " and add_time>=$abTime";
        }

        //下单截止日期
        if (isset($criteria['ae_time']) && strlen(($aeTime = $criteria['ae_time']))) {
            $aeTime = strtotime($aeTime);
            $where .= " and add_time<=$aeTime";
        }

        //支付截止起始日期
        if (isset($criteria['eb_time']) && strlen(($ebTime = $criteria['eb_time']))) {
            $ebTime = strtotime($ebTime);
            $where .= " and expire>=$ebTime";
        }

        //支付截止终止日期
        if (isset($criteria['ee_time']) && strlen(($eeTime = $criteria['ee_time']))) {
            $eeTime = strtotime($eeTime);
            $where .= " and expire<=$eeTime";
        }

        //订单总价起始值
        if (isset($criteria['min_gross']) && strlen(($gross = $criteria['min_gross']))) {
            $where .= " and gross>=$gross";
        }

        //订单总价终止值
        if (isset($criteria['max_gross']) && strlen(($gross = $criteria['max_gross']))) {
            $where .= " and gross<=$gross";
        }

        $sqlCount = "select count(*) as num from mic_order where isdel=0" . chr(32) . $where;
        $query = $this->db->query($sqlCount);
        $total = (int)$query->row()->num;

        //echo $where;

        if ($total > 0) {
            $sql = "select * from mic_order where isdel=0 $where  order by add_time desc limit $start,$rows";
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


}