<?php

class planItem_model extends CI_Model
{
    protected $_items = array();
    protected $_goodsRs = array();

    /**
     * 获取项目列表
     */
    public function getList($page, $offset = 20)
    {
        $page = (int)$page;
        if ($page <= 0) $page = 1;
        $start = $page - 1;
        $list = array();
        $query = $this->db->query("select * from mic_item where isdel=0 and status>0 order by add_time desc limit $start,$offset");
        if ($query->row()) {
            foreach ($query->result() as $row) {
                $itemId = $row->id;
                $planItem = new PlanItem($itemId);
                $planItem->row = $row;
                $planItem->goods->row = $this->getGoodsRow($itemId);
                $list[$itemId] = $planItem;
            }
        }
        return $list;
    }

    /**
     * 项目商品详情
     */
    public function getDetail($itemId)
    {
        $item = $this->genItem($itemId);
        $item->goods->row = $this->getGoodsRow($itemId);
        return $item;
    }


    /**
     * 创建有效的筹资项目对象
     */
    public function genItem($id)
    {
        $query = $this->db->query("select * from mic_item where isdel=0 and id=$id limit 1");
        return $this->_items[$id] = new PlanItem($id, $query);
    }

    /**
     * 获取项目商品数据集
     */
    public function getGoodsRow($itemId)
    {
        $row = null;
        $modelGoods = CModel::make('goods_model');
        $rows = $modelGoods->getItemGoodsRows($itemId);
        if ($rows)
            $row = $rows[0];
        return $row;
    }

    /**
     * 筹资项目总价
     */
    public function getItemGross($itemId)
    {
        $gross = 0;
        if (!isset($this->_goodsRs[$itemId])) {
            $this->_goodsRs[$itemId] = $this->getItemGoods($itemId);
        }

        $query = $this->_goodsRs[$itemId];
        if ($query->num_rows > 0) {
            foreach ($query->result as $row) {
                $gross += $row->price * $row->quantity;
            }
        }
        return $gross;
    }

    /**
     * 订单项目记录
     */
    public function orderItemRows($orderId, $limit = 0)
    {
        $limit = (int)$limit;
        $rows = null;
        $sql = "SELECT * FROM mic_order_item where isdel=0 and order_id=$orderId";
        $query = $this->db->query($sql);
        return $limit > 0 ? $query->result() : $query->row();
    }
}