<?php

/**
 * 筹资项目管理
 */
class ItemMan_model extends CI_Model
{
    protected $_items = array();
    protected $_goodsRs = array();
    protected $_errCode = 0;

    protected $_gradeMap = array(
        1 => '人品稀薄',
        2 => '马马虎虎',
        3 => '人气爆棚',
        4 => '神仙降临',
    );

    public function setErrCode($code)
    {
        return $this->_errCode = $code;
    }

    public function getErrCode()
    {
        return $this->_errCode;
    }

    public function getItem($id)
    {
        $data = array('item' => null, 'goods' => null);
        $id = (int)$id;
        $itemRec = $this->db->query("select * from mic_item where isdel=0 and id=$id");

        if ($itemRec->row()) {
            $data['item'] = $itemRec->row_array();
            $goodsRec = $this->db->query("select * from mic_item_goods where isdel=0 and item_id=$id order by add_time limit 1");
            if ($goodsRec->row()) {
                $data['goods'] = $goodsRec->row_array();
            }
        }
        return $data;
    }


    /**
     * 获取项目列表
     */
    public function getList($page, $rows = 20)
    {
        $page = (int)$page;
        if ($page <= 0) $page = 1;
        $start = ($page - 1) * $rows;
        $list = array('total' => 0, 'rows' => array());
        $query = $this->db->query("select count(*) as num from mic_item where isdel=0");
        $total = (int)$query->row()->num;
        if ($total > 0) {
            $query = $this->db->query("select * from mic_item where isdel=0  order by add_time desc limit $start,$rows");
            if ($query->row()) {
                $list['total'] = $total;
                $list['rows'] = $query->result_array();
            }
        }
        return $list;
    }

    public function newItem($data)
    {
        $itemId = 0;
        $value = array(
            'title' => $data['title'],
            'desc' => $data['desc'],
            'gross' => $data['gross'],
            'quota' => $data['quota'],
            'period' => $data['period'],
            'grade' => $data['grade'],
            'grade_name' => isset($this->_gradeMap[$data['grade']]) ? $this->_gradeMap[$data['grade']] : '',
            'status' => $data['status'],
            'add_time' => time(),
        );

        $return = $this->db->insert('mic_item', $value);
        if ($return === true) {
            $data['itemId'] = $this->db->insert_id();
            $reVal = $this->newItemGoods($data);
            if ($reVal !== true) $this->setErrCode(1002);
            return $reVal;
        } else {
            $this->setErrCode(1001);
        }
        return false;
    }


    /**
     * 编辑项目
     */
    public function editItem($data)
    {
        $id = (int)$data['item_id'];
        $value = array(
            'title' => $data['title'],
            'desc' => $data['desc'],
            'gross' => $data['gross'],
            'quota' => $data['quota'],
            'period' => $data['period'],
            'grade' => $data['grade'],
            'grade_name' => isset($this->_gradeMap[$data['grade']]) ? $this->_gradeMap[$data['grade']] : '',
            'status' => $data['status'],
            'update_time' => time(),
        );
        $reVal = $this->db->update('mic_item', $value, array('id' => $id));
        if ($reVal === true) {
            $reVal = $this->editItemGoods($data);
        }
        return $reVal;
    }

    /**
     * 新增项目商品
     */
    public function newItemGoods($data)
    {
        $value = array(
            'item_id' => (int)$data['itemId'],
            'goods_id' => (int)$data['goods_id'],
            'title' => $data['goods_title'],
            'price' => $data['goods_price'],
            'img' => $data['goods_img'],
            'price' => $data['goods_price'],
            'origin' => $data['goods_source'],
            'desc' => $data['goods_desc'],
            'add_time' => time(),
        );
        $reVal = $this->db->insert('mic_item_goods', $value);
        return $reVal;
    }

    /**
     * 编辑项目商品
     */
    public function editItemGoods($data)
    {
        $data['itemId'] = $data['item_id'];
        // $reVal = $this->db->update('mic_item_goods', $value, array('isdel' => 0, 'item_id' => $value['item_id'], 'goods_id' => $value['goods_id']));
        $this->db->delete('mic_item_goods', array('item_id' => $data['item_id'], 'goods_id' => $data['goods_id']));
        $reVal = $this->newItemGoods($data);
        return $reVal;
    }


    /**
     * 删除项目
     */
    public function deleteItem($id)
    {
        $id = is_array($id) ? implode(',', $id) : (int)$id;
        $sql = "update mic_item set isdel=1 where isdel=0 and id in($id)";
        $return = $this->db->query($sql);
        if ($return === true) {
            $sql = "update mic_item_goods set isdel=1 where isdel=0 and item_id in($id)";
            $return = $this->db->query($sql);
        }
        return $return;
    }

}