<?php

class ItemOrder extends CCApplication
{
    protected $_orderId = 0;
    protected $_row = null;
    protected $_recordSet;
    protected $_planItem = null;
    static protected $_orders = array();

    public function __construct($orderId, $recordSet = null)
    {
        $this->_orderId = $orderId;
        $this->_recordSet = $recordSet;
    }

    static public function register($orderObj)
    {
        self::$_orders[$orderObj->id] = $orderObj;
    }

    public function getId()
    {
        return $this->_orderId;
    }

    public function setRecordSet($query)
    {
        $this->_recordSet = $query;
    }

    public function getRecordSet($query)
    {
        $this->_recordSet = $query;
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

    /**
     * 获取记录数
     */
    public function getCountRows()
    {
        return (int)$this->_recordSet->num_rows();
    }

    /**
     * 获取剩余时间
     */
    public function getLeftTime()
    {
        $time = array();
        $row = $this->getRow();
        if ($row)
            $time = Utils::getDiffTime(time(), $row->expire);
        return $time;
    }

    public function setItem($itemObj)
    {
        $this->_planItem = $itemObj;
    }

    public function getItem()
    {
        return $this->_planItem;
    }
}