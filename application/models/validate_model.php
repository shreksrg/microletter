<?php

class Validate_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }

    /**
     * 通过手机检查用户是否存在
     * @param $mobile int 手机号
     * @return boolean
     */
    public function chkUserByMobile($mobile)
    {
        $sql = "select id from mic_user where isdel=0 and mobile=?";
        $query = $this->db->query($sql, array($mobile));
        return $query->row() ? true : false;
    }

    /**
     * 通过订单Id判断订单是否存在
     * @param $orderId int 订单号
     * @return boolean
     */
    public function hasOrderById($orderId)
    {
        $now = time();
        $sql = "select id from mic_order where isdel=0 and status>0 and expire>?  and id=?";
        $query = $this->db->query($sql, array($now, $orderId));
        return $query->row() ? true : false;
    }
}