<?php

/**
 * 筹资项目管理
 */
class UserMan_model extends CI_Model
{
    protected $_errCode = 0;

    public function setErrCode($code)
    {
        return $this->_errCode = $code;
    }

    public function getErrCode()
    {
        return $this->_errCode;
    }

    public function getUser($id)
    {
        $data = array('item' => null, 'goods' => null);
        $id = (int)$id;
        $query = $this->db->query("select * from mic_user where isdel=0 and id=$id");
        return $query->row_array();
    }

    /**
     * 获取用户列表
     */
    public function getList($page, $rows = 20)
    {
        $page = (int)$page;
        if ($page <= 0) $page = 1;
        $start = ($page - 1) * $rows;
        $list = array('total' => 0, 'rows' => array());
        $query = $this->db->query("select count(*) as num from mic_user where isdel=0");
        $total = (int)$query->row()->num;
        if ($total > 0) {
            $query = $this->db->query("select * from mic_user where isdel=0  order by add_time desc limit $start,$rows");
            if ($query->row()) {
                $list['total'] = $total;
                $list['rows'] = $query->result_array();
            }
        }
        return $list;
    }

    /**
     * 新增管理用户
     */
    public function newUser($data)
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
     * 编辑会员
     */
    public function editUser($data)
    {
        $id = (int)$data['id'];
        $value = array(
            'fullname' => isset($data['fullname']) ? $data['fullname'] : '',
            'mobile' => isset($data['mobile']) ? $data['mobile'] : '',
            'desc' => $data['desc'],
            'status' => $data['status'],
            'update_time' => time(),
        );
        $reVal = $this->db->update('mic_user', $value, array('id' => $id));
        return $reVal;
    }

    /**
     * 删除用户
     */
    public function deleteUser($id)
    {
        $id = is_array($id) ? implode(',', $id) : (int)$id;
        $sql = "update mic_user set isdel=1 where isdel=0 and id in($id)";
        $return = $this->db->query($sql);
        return $return;
    }

    /**
     * 设置管理员密码
     */
    public function setPassword($id, $password)
    {
        if (strlen($password) <= 0) return false;
        $id = (int)$id;
        $sql = "select id from mic_user where isdel=0 and id=?";
        $query = $this->db->query($sql, array($id));
        if (!$query->row()) return false;

        $model = CModel::make('login_model');
        $password = $model->hashPassword($password);
        $sql = "update mic_user set password=? where isdel=0 and id=$id";
        $return = $this->db->query($sql, array($password));
        return $return;
    }



}