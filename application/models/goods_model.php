<?php

class Goods_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function  genItemGoods($itemId)
    {
        $sql = "select * from  mic_item_goods where isdel=0 and item_id=$itemId";
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
}