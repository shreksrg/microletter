<?php

/**
 * 筹资项目管理
 */
class MItem_model extends CI_Model
{
    protected $_items = array();
    protected $_goodsRs = array();

    /**
     * 获取项目列表
     */
    public function getList($page, $offset = 10)
    {
        $page = (int)$page;
        if ($page <= 0) $page = 1;
        $start = $page - 1;
        $list = array();
        $query = $this->db->query("select * from mic_item where isdel=0 and status=1 limit $start,$offset");
        if ($query->num_rows > 0) {
            foreach ($query->result_array() as $row) {
                $itemId = $row['id'];
                $planItem = new PlanItem($itemId, $row);
                $planItem->goods->rows = $this->getGoodsRecs($itemId)->result_array();
                $list[$itemId] = $planItem;
            }
        }
        return $list;
    }


    /**
     * 创建有效的筹资项目对象
     */
    public function genItem($id)
    {
        $query = $this->db->query("select * from mic_item where isdel=0 and status=1 and id=$id limit 1");
        $item = new PlanItem($id);
        $item->recordSet = $query;
        $this->_items[$id] = $item;
        return $item;
    }

    /**
     * 获取项目商品数据集
     */
    public function getGoodsRecs($itemId)
    {
        $sql = "select * from  mic_item_goods where isdel=0 and item_id=$itemId";
        return $this->db->query($sql);
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
}