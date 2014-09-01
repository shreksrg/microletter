<?php

class Payment_model extends CI_Model
{
    protected $_errCode = 0;
    protected $_errCodes = array();
    protected $_orders = array();

    public function setErrCode($code)
    {
        $this->_errCode = $code;
    }

    public function getErrCode()
    {
        return $this->_errCode;
    }

    /**
     * 新增支付项
     */
    public function newPayItem($orderId, $type)
    {
        $modelOrder = CModel::make('order_model');
        $orderObj = $modelOrder->genOrder($orderId);

        if (($orderRow = $orderObj->row)) {
            $itemId = $orderRow->item_id;
            $modItem = CModel::make('planItem_model');
            $orderObj->item = $modItem->genItem($itemId); //订单项目
            if (!$orderObj->item->row) {
                $this->setErrCode(1002); //订单项目无效
                return false;
            }
        } else {
            $this->setErrCode(1001);
            return false;
        }

        $state = $modelOrder->getOrderState($orderObj);
        if ($state != 'on') {
            $this->setErrCode(1003); //订单过期或已经完成
            return false;
        }

        //获取项目订单
        $amount = ($orderRow->gross / $orderObj->item->row->quota); // 支付金额

        //产生支付项id
        $paySn = UUID::fast_uuid(4);
        $value = array(
            'orderId' => $orderRow->id, //项目项订单Id
            'orderSn' => $orderRow->sn, //项目项订单编码
            'paySn' => $paySn, // 支付项编码
            'amount' => $amount, //支付金额
            'add_time' => time()
        );

        $reBoolean = $this->db->insert($value);
        return $reBoolean === true ? $value : false;
    }

    /**
     * 更新支付项状态
     */
    public function updatePayment($data)
    {
        return true;
    }

}