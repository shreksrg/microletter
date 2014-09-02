<?php

/**
 * 筹资项目管理
 */
class GoodsMan_model extends CI_Model
{
    protected $_items = array();
    protected $_goodsRs = array();

    /**
     * 获取商品列表
     */
    public function getList($page, $rows = 20)
    {
        $page = (int)$page;
        if ($page <= 0) $page = 1;
        $start = $page - 1;
        $list = array('total' => 0, 'rows' => array());
        $query = $this->db->query("select * from mic_goods where isdel=0  limit $start,$rows");
        if ($query->row()) {
            $list['total'] = $query->num_rows();
            $list['rows'] = $query->result_array();
        }
        return $list;
    }
}