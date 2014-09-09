<?php

class PlanItem extends CCApplication
{
    protected $_itemId = 0;
    protected $_goods = null;
    protected $_recordSet = null;
    protected $_row = null;
    protected $_goodsRecs = null; //项目商品容器
    static protected $_items = array();

    public function __construct($itemId, $rs = null)
    {
        $this->_itemId = $itemId;
        if ($rs !== null) $this->_recordSet = $rs;
    }

    public static function loadItem($itemObj)
    {
        self::$_items[$itemObj->id] = $itemObj;
    }

    public function getId()
    {
        return $this->_itemId;
    }

    public function setRecordSet($query)
    {
        $this->_recordSet = $query;
    }

    public function getRecordSet($query)
    {
        $this->_recordSet = $query;
    }

    public function setGoodsRecs($query)
    {
        $this->_goodsRecs = $query;
    }

    public function getGoodsRecs()
    {
        return $this->_goodsRecs;
    }

    public function setRow($row)
    {
        $this->_row = $row;
    }

    public function getRow()
    {
        if (!$this->_row && $this->_recordSet) {
            $this->_row = $this->_recordSet->row();
        }
        return $this->_row;
    }

    public function getGoods()
    {
        if (!$this->_goods)
            $this->_goods = new stdClass();
        return $this->_goods;
    }


}