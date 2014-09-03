<?php

/**
 * 筹资项目管理
 */
class GoodsMan_model extends CI_Model
{
    protected $_items = array();
    protected $_goodsRs = array();
    protected $_errCode = 0;

    public function setErrCode($code)
    {
        return $this->_errCode = $code;
    }

    public function getErrCode()
    {
        return $this->_errCode;
    }

    public function getGoodsRow($id)
    {
        $id = (int)$id;
        $query = $this->db->query("select * from mic_goods where isdel=0 and id=$id");
        return $query->row();
    }

    /**
     * 获取商品列表
     */
    public function getList($page, $rows = 20)
    {
        $page = (int)$page;
        if ($page <= 0) $page = 1;
        $start = ($page - 1) * $rows;
        $list = array('total' => 0, 'rows' => array());
        $query = $this->db->query("select count(*) as num from mic_goods where isdel=0");
        $total = (int)$query->row()->num;
        if ($total > 0) {
            $query = $this->db->query("select * from mic_goods where isdel=0  order by add_time desc limit $start,$rows");
            if ($query->row()) {
                $list['total'] = $total;
                $list['rows'] = $query->result_array();
            }
        }
        return $list;
    }

    public function newGoods($data)
    {
        $newId = 0;
        $value = array(
            'title' => $data['title'],
            'digest' => $data['title'],
            'img' => $data['img'],
            'source' => $data['source'],
            'price' => $data['price'],
            'desc' => $data['desc'],
            'status' => $data['status'],
            'add_time' => time(),
        );

        $reVal = $this->db->insert('mic_goods', $value);
        if ($reVal === true) {
            $newId = $this->db->insert_id();
        }
        return $newId;
    }

    /**
     * 编辑商品
     */
    public function editGoods($data)
    {
        $id = (int)$data['id'];
        $value = array(
            'title' => $data['title'],
            'digest' => $data['title'],
            'img' => $data['img'],
            'source' => $data['source'],
            'price' => $data['price'],
            'desc' => $data['desc'],
            'status' => $data['status'],
            'update_time' => time(),
        );
        $reVal = $this->db->update('mic_goods', $data, array('id' => $id));
        return $reVal;
    }

    /**
     * 删除商品
     */
    public function deleteGoods($id)
    {
        $id = is_array($id) ? implode(',', $id) : (int)$id;
        $sql = "update mic_goods set isdel=1 where isdel=0 and id in($id)";
        $return = $this->db->query($sql);
        return $return;
    }

}