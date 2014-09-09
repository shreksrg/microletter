<?php

class Goods_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function  genGoods($itemId)
    {
        $sql = "select * from  mic_goods where isdel=0 and item_id=$itemId";
        return $this->db->query($sql);
    }


    public function getList($page = 1, $offset = 10)
    {
        if ((int)$page <= 0) $page = 1;
        $start = $page - 1;
        $sql = "SELECT * FROM mic_goods where isdel=0 and isclosed=0 limit $start,$offset";
        $query = $this->db->query($sql);
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return null;
    }

    /**
     * 获取商品详细信息
     * @param int $id 商品id
     * @return
     */
    public function getDetail($id)
    {
        $id = (int)$id;
        if ($id > 0) {
            $sql = "SELECT * FROM mic_goods where isdel=0 and isclosed=0 and id=$id";
            $query = $this->db->query($sql);
            if ($query->num_rows() > 0) {
                return $query->row_array(0);
            }
        }
        return null;
    }

    /**
     * 获取商品来源地列表
     */
    public function getOrigin()
    {
        $sql = "SELECT * FROM mic_origin where isdel=0";
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /**
     * 获取项目商品
     */
    public function getItemGoodsRows($itemId)
    {
        $sql = "SELECT * FROM mic_item_goods where isdel=0 and item_id=$itemId order by add_time";
        $query = $this->db->query($sql);
        return $query->result();
    }

    /**
     * 获取订单商品
     */
    public function getOrderGoods($orderId)
    {
        $sql = "SELECT * FROM mic_order_goods where isdel=0 and order_id=$orderId";
        $query = $this->db->query($sql);
        return $query->result();
    }

    /**
     * 获取订单商品
     */
    public function orderGoodsRows($orderId, $limit = 0)
    {
        $limit = (int)$limit;
        $rows = null;
        $sql = "SELECT * FROM mic_order_goods where isdel=0 and order_id=$orderId";
        $query = $this->db->query($sql);
        return $limit > 0 ? $query->result() : $query->row();
    }


}