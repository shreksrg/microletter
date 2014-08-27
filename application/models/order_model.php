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
     * @param User $user 用户对象
     * @param array $form 申请表单数据
     * @return boolean
     */
    public function apply($user, $form)
    {
        $uid = $user->id;
        $addressId = $this->newConsignee($uid, $form); //新增用户收货地址
        if ($addressId <= 0) return $this->setErrCode('apply', 1000);

        $itemId = $form['itemId'];
        $modPlanItem = CModel::make('planItem_model');
        $planItem = $modPlanItem->genItem($itemId);
        $planItem->goodsRecs = $modPlanItem->getGoodsRecs($itemId);
        $planItem['message'] = $form['message']; //订单留言

        $orderId = $this->createItemOrder($uid, $planItem); //新增项目订单
        if ($orderId <= 0) return $this->setErrCode('apply', 1001);

        $recId = $this->newShipAddress($orderId, $form); //新增订单收货地址
        if ($recId <= 0) return $this->setErrCode('apply', 1002);
        return $orderId;
    }

    /**
     * 增加用户新地址
     */
    public function newConsignee($userId, $address)
    {
        $fullName = $address['fullName'];
        $mobile = $address['mobile'];
        $address = $address['address'];
        $rt = $this->db->insert('mic_consignee', array('user_id' => $userId, 'fullname' => $fullName, 'mobile' => $mobile, 'address' => $address));
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
            if ($reNew == true) {
                $this->db->update('mic_order', array('status' => 1), array('id' => $orderId));
                return $orderId;
            }
        }
        return 0;
    }

    /**
     * 新增筹资项目订单商品
     */
    public function newOrderGoods($orderId, $itemObj)
    {
        $values = array();
        $return = false;
        if ($itemObj->goodsRecs) {
            foreach ($itemObj->goodsRecs->result() as $row) {
                $values[] = array(
                    'order_id' => $orderId,
                    'item_id' => $itemObj->id,
                    'goods_id' => $row->goods_id,
                    'title' => $row->title,
                    'price' => $row->price,
                    'price' => $row->price,
                    'quantity' => $row->quantity,
                    'gross' => $itemObj->row->gross,
                );
            }
            $return = $this->db->insert_batch('mic_order_goods', $values);
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
     * 获取订单商品
     */
    public function getGoodsRecs($orderId)
    {
        $sql = "select * from  mic_order_goods where isdel=0 and order_id=$orderId";
        return $this->db->query($sql);
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

    /**
     * 获取有效订单详细
     */
    public function getOrderInfo($orderId)
    {
        $order = array();
        $orderId = (int)$orderId;
        $orderObj = $this->genOrder($orderId);
        $orderRow = $orderObj->getRow();
        if ($orderRow) {
            $order['order'] = $orderObj;
            //$order['message'] = $orderRow->message; //订单留言
            //$order['addTime'] = $orderRow->add_time; //下单时间(发起时间)
            //$order['expire'] = $orderRow->expire; //订单结束时间

            $itemId = $orderRow->item_id;
            $modItem = CModel::make('planItem_model');
            $order['item'] = $planItem = $modItem->genItem($itemId); //订单项目

            //订单项目商品信息
            $order['goods'] = $modItem->getGoodsRecs($itemId)->row_array();

            //订单支付统计信息
            $order['quota'] = $quota = $planItem->row->quota; //限定支付总人数
            $order['supportNum'] = $this->getSupports($orderId); //已支付人数

            //剩余时间，精确到分钟
            $leftTime = $orderObj->getLeftTime();
            $order['leftTime'] = $this->formatLeftTime($leftTime);

            //订单评论
            // $order['comments'] = $this->getComments($orderId);
        }
        return $order;
    }


    /**
     * 获取订单评论
     */
    public function getComments($orderId)
    {
        $comments = array();
        $sql = "select *  from  mic_comment where isdel=0 and order_id=$orderId";
        $query = $this->db->query($sql);
        if ($query->num_rows)
            $comments = $query->result_array();
        return $comments;
    }


    /**
     * 格式化剩余时间标签
     */
    public function formatLeftTime($leftTime)
    {
        $time = $leftTime;
        $days = $time['leftDays'] > 0 ? $time['leftDays'] . '天' : '';
        $hours = $time['leftHours'] > 0 ? $time['leftHours'] . '小时' : '';
        $minutes = $time['leftMinutes'] > 0 ? $time['leftMinutes'] . '分' : '';
        $seconds = $time['leftSeconds'] > 0 ? $time['leftSeconds'] . '秒' : '';
        return $days . $hours . $minutes . $seconds;
    }


    /**
     * 获取订单支付者人数
     */
    public function getSupports($orderId)
    {
        $sql = "select count(*) as num from  mic_payment_item where isdel=0 and status=1 and order_id={$orderId}";
        $query = $this->db->query($sql);
        return intval($query->row()->num);
    }

    /**
     * 支付项目订单信息
     */
    public function paymentItem($orderId)
    {
        $orderId = (int)$orderId;
        $orderObj = $this->getOrder($orderId);
    }

    /**
     * 获取订单发货信息
     */
    public function getShipInfo($orderId)
    {
        $info = array();
        $sql = "select * from mic_ship_address where isdel=0 and order_id=? limit 1";
        $query = $this->db->query($sql, array((int)$orderId));
        if ($query->row())
            $info = $query->row_array();
        return $info;
    }

    /**
     * 获取订单状态
     */
    public function getStatus($user)
    {
        $data = array('state' => 'none');
        $uid = $user->id;
        $sql = "select * from mic_order where isdel=0 and user_id=? order by add_time desc limit 1";
        $query = $this->db->query($sql, array($uid));
        $orderRow = $query->row();
        if ($orderRow) {
            $orderId = $orderRow->id;
            //订单关闭
            if ($orderRow->status == 0) {
                $data['state'] = 'close';
                return $data;
            }

            $diffTime = $orderRow->expire - time(); //距离时间
            $data['info'] = $info = $this->getOrderInfo($orderId); //订单详情
            $lacks = $info['quota'] - $info['supportNum']; //缺少人数

            if ($lacks > 0 && $diffTime > 0) { //正在进行中的订单
                $data['state'] = 'on';
                $sql = "select count(*) as num from mic_comment where isdel=0 and pay_id=0 and type=0 and order_id=$orderId";
                $query = $this->db->query($sql);
                $data['info']['abandon'] = (int)$query->row()->num; //放弃人数
                $data['info']['consignee'] = $this->getShipInfo($orderId);
            } elseif ($lacks > 0 && $diffTime <= 0) { //结束订单：未完成筹资(失败)
                $data['state'] = 'fail';
            } elseif ($lacks <= 0) {
                $data['state'] = 'achieve';
                $time = Utils::getDiffTime($orderRow->add_time, $orderRow->achieve_time); //项目耗时统计
                $data['useTime'] = $this->formatLeftTime($time);
            }
        }
        return $data;
    }

    /**
     * 项目是否存在正在进行的订单中
     */
    public function hasItemExists($userId, $itemId)
    {
        $userId = (int)$userId;
        $time = time();
        $sql = "select * from mic_order where  isdel=0 and status>0 and expire>$time and user_id=$userId order by add_time desc limit 1";
        $query = $this->db->query($sql);
        if ($query->row()) {
            $orderId = $query->row()->id;
            $itemId = $query->row()->item_id;
            $quota = 0;
            $supports = $this->getSupports($orderId); //订单的支付人数
            $modItem = CModel::make('planItem_model');
            $itemObj = $modItem->genItem($itemId); //订单项目
            if ($itemObj->row) $quota = $itemObj->row->quota;
            $lacks = $quota - $supports;
            if ($lacks > 0)
                return true;
        }
        return false;
    }

    /**
     * 获取订单时效状态
     */
    public function getOrderState($orderObj, $itemObj = null)
    {
        $state = 'none';
        if ($orderObj->row) {
            $orderId = $orderObj->row->id;
            $itemId = $orderObj->row->item_id;
            $status = $orderObj->row->status;

            if ($status == 0) {
                return $state = 'close';
            }

            $diffTime = $orderObj->row->expire - time();
            $quota = 0;
            $supports = $this->getSupports($orderId); //订单的支付人数

            if ($itemObj === null) {
                $modItem = CModel::make('planItem_model');
                $itemObj = $modItem->genItem($itemId); //订单项目
            }
            if ($itemObj->row) $quota = $itemObj->row->quota;
            $lacks = $quota - $supports;

            if ($diffTime > 0 && $lacks > 0) { // 订单进行中
                $state = 'on';
            } elseif ($diffTime <= 0 && $lacks > 0) { //订单结束（失败）
                $state = 'fail';
            } elseif ($lacks <= 0) { //订单完成（成功）
                $state = 'achieve';
            }
        }
        return $state;
    }
}