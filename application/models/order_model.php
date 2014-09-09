<?php


class Order_model extends CI_Model
{
    protected $_errCode = array('apply' => 0);

    /**
     * 匹配手机验证码
     * @param $mobile 手机号
     * @param $code 验证码
     * @return boolean
     */
    public function matchMobileCaptcha($mobile, $code)
    {
        return true;
        $time = time();
        $sql = "SELECT id FROM mic_mobile_captcha where isdel=0 and type=1 and  status=0 and expire>=$time and mobile=? and code=? limit 1";
        $query = $this->db->query($sql, array($mobile, $code));
        if ($query->row()) {
            $id = $query->row()->id;
            return true;
        }
        return false;
    }

    /**
     * 订单申请
     * @param array $form 申请表单数据
     * @return boolean
     */
    public function apply($form)
    {
        // 注册新用户
        $modelLogin = CModel::make('login_model');
        $userId = $modelLogin->register($form);
        if ($userId == 0) return $this->setErrCode('apply', 1101);

        $addressId = $this->newConsignee($userId, $form); //新增用户收货地址
        if ($addressId <= 0) return $this->setErrCode('apply', 1000);

        $itemId = $form['itemId'];
        $modPlanItem = CModel::make('planItem_model');
        $planItem = $modPlanItem->genItem($itemId);
        $planItem->goods->row = $modPlanItem->getGoodsRow($itemId);
        $planItem['message'] = $form['message']; //订单留言

        $orderId = $this->createItemOrder($userId, $planItem); //新增项目订单
        if ($orderId <= 0) return $this->setErrCode('apply', 1001);

        $return = $this->newOrderItem($orderId, $planItem->row); //新增订单项目
        if ($return <= 0) return $this->setErrCode('apply', 1002);

        $recId = $this->newShipAddress($orderId, $form); //新增订单收货地址
        if ($recId <= 0) return $this->setErrCode('apply', 1003);
        return $orderId;
    }


    /**
     * 增加用户新地址
     */
    public function newConsignee($userId, $values)
    {
        $fullName = $values['fullName'];
        $mobile = $values['mobile'];
        $address = $values['address'];
        $message = $values['message'];

        $rt = $this->db->insert('mic_consignee', array('user_id' => $userId, 'fullname' => $fullName, 'mobile' => $mobile, 'address' => $address, 'message' => $message));
        return $rt === true ? $this->db->insert_id() : 0;
    }

    /**
     * 增加筹资项目订单
     */
    public function createItemOrder($userId, $itemObj)
    {
        $sn = $this->genOrderSn();
        $time = time();
        $expire = $time + $itemObj->row->period * 3600;
        $values = array(
            'user_id' => $userId,
            'sn' => $sn,
            'item_id' => $itemObj->id,
            'quota' => $itemObj->row->quota,
            'gross' => $itemObj->row->gross,
            'expire' => $expire,
            'message' => $itemObj['message'],
            'status' => 0, //初始化为关闭状态
            'add_time' => $time,
        );

        $rt = $this->db->insert('mic_order', $values);
        if ($rt == true) {
            $orderId = $this->db->insert_id();
            $reNew = $this->newOrderGoods($orderId, $itemObj); //创建订单商品
            if ($reNew === true) {
                $this->db->update('mic_order', array('status' => 1), array('id' => $orderId));
                return $orderId;
            }
        }
        return 0;
    }

    /**
     * 新增订单项目记录
     */
    public function newOrderItem($orderId, $ItemRow)
    {
        $row = (array)$ItemRow;
        $values = array(
            'order_id' => $orderId,
            'item_id' => $row['id'],
            'grade' => $row['grade'],
            'grade_name' => $row['grade_name'],
            'title' => $row['title'],
            'quota' => $row['quota'],
            'gross' => $row['gross'],
            'period' => $row['period'],
            'desc' => $row['desc'],
            'add_time' => time(),
        );
        $return = $this->db->insert('mic_order_item', $values);
        return $return;
    }

    /**
     * 新增筹资项目订单商品
     */
    public function newOrderGoods($orderId, $itemObj)
    {
        $values = array();
        $return = false;
        $row = $itemObj->goods->row;
        if ($row) {
            $value = array(
                'order_id' => $orderId,
                'item_id' => $itemObj->id,
                'title' => $row->title,
                'origin' => $row->origin,
                'img' => $row->img,
                'price' => $row->price,
                'quantity' => $row->quantity,
                'add_time' => time(),
            );
            $return = $this->db->insert('mic_order_goods', $value);
        }
        return $return;
    }

    /**
     * 增加筹资项目订单收货地址
     */
    public function newShipAddress($orderId, $address)
    {
        $values = array(
            'order_id' => $orderId,
            'consignee' => $address['fullName'],
            'mobile' => $address['mobile'],
            'address' => $address['address'],
        );
        $rt = $this->db->insert('mic_ship_address', $values);
        return $rt === true ? $this->db->insert_id() : 0;
    }

    /**
     * 获取错误日志码
     */
    public function getErrCode($key)
    {
        return isset($this->_errCode[$key]) ? $this->_errCode[$key] : -1;
    }

    public function setErrCode($key, $value)
    {
        $this->_errCode[$key] = $value;
        return false;
    }

    /**
     * 生成订单编号
     */
    public function genOrderSn()
    {
        $sn = UUID::fast_uuid();
        $query = $this->db->query("select id from mic_order where sn=$sn limit 1");
        if ($query->row()) $this->genOrderSn();
        else return $sn;
    }

    /**
     * 创建订单对象
     *
     */
    public function genOrder($orderId)
    {
        $detail = array();
        $orderId = (int)$orderId;
        $sql = "select * from mic_order where isdel=0 and id=$orderId limit 1";
        $query = $this->db->query($sql);
        $order = new ItemOrder($orderId, $query);
        return $order;
    }

    public function getDetail($orderObj)
    {
        $order = array();
        $orderRow = $orderObj->row;
        $orderId = $orderRow->id;
        if ($orderRow) {
            $order['order'] = $orderRow;
            $modItem = CModel::make('planItem_model');
            $order['item'] = $modItem->orderItemRows($orderId); //订单项目
            //订单项目商品信息
            $modelGoods = CModel::make('goods_model');
            $order['goods'] = $modelGoods->orderGoodsRows($orderId);
        }
        return $order;
    }

    /**
     * 获取订单发货信息
     */
    public function getShipInfo($orderId)
    {
        $info = null;
        $sql = "select * from mic_ship_address where isdel=0 and order_id=? limit 1";
        $query = $this->db->query($sql, array((int)$orderId));
        if ($query->row())
            $info = $query->row_array();
        return $info;
    }


    /**
     * 根据用户id创建订单对象
     */
    public function getOrderByUId($userId)
    {
        $orderObj = null;
        $sql = "select * from mic_order where isdel=0 and status>0 and user_id=? order by add_time desc limit 1";
        $query = $this->db->query($sql, array($userId));
        if (($row = $query->row()))
            $orderObj = new ItemOrder($row->id, $query);
        return $orderObj;
    }

    /**
     * 获取订单状态
     */
    public function getState($orderObj)
    {
        $state = 'none';
        if (($orderRow = $orderObj->row) && $orderRow->status > 0) {
            $diff = $orderRow->expire - time();
            $lacks = $orderRow->quota - $orderRow->paids;
            if ($diff > 0 && $lacks > 0) { // 订单进行中
                $state = 'on';
            } elseif ($diff <= 0 && $lacks > 0) { //订单结束（失败）
                $state = 'fail';
            } else {
                $state = 'achieve'; //订单完成（成功）
            }
        }
        return $state;
    }
}