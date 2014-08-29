<?php


class Login_model extends CI_Model
{
    private $_uid=0;
    private $_validateFlag = false;
    protected $_user;

    function __construct()
    {
        parent::__construct();
    }

    public function hashPassword($password)
    {
        return md5($password);
    }

    /**
     * 用户注册
     */
    public function register($data)
    {
        $mobile = $data['mobile'];
        $password = $this->hashPassword($data['captcha']);
        $value = array(
            'username' => $mobile,
            'password' => $password,
            'fullname' => $data['fullName'],
            'mobile' => $mobile,
            'type' => 1,
            'add_time' => time(),
        );

        $rt = $this->db->insert('mic_user', $value);
        if($rt===true){
           $this->_uid = $this->db->insert_id();
           $this->_user = new User();
        }
        return $this->_uid;
    }

    /**
     * 验证用户登录
     */
    public function validate($username, $password)
    {
        $sql = "SELECT * FROM mic_user WHERE username = ? AND password = ?";
        $query = $this->db->query($sql, array($username, $this->hashPassword($password)));
        if ($query->row()) {
            $this->_uid = $query->row()->id;
            $this->_validateFlag = true;
        }
        return $this->_validateFlag;
    }

    public function authenticate($user)
    {
        $this->_user = $user;
        $this->validate($user->username, $user->password);
        return $this->_validateFlag;
    }

    /**
     * 保存用户登录信息（生成用户会话）
     */
    public function save()
    {
        $return = false;
        if ($this->_validateFlag === true && $this->_user) {
            $sql = "SELECT * FROM mic_user WHERE isdel=0 and  id={$this->_uid}";
            $query = $this->db->query($sql);
            if ($query->num_rows() > 0) {
                $this->_user->id = $this->_uid;
                $this->_user->info = $query->row();
            }
            $return = true;
        }
        return $return;
    }

    public function setInfo($userObj)
    {
        $uid = (int)$userObj->id;
        $sql = "SELECT * FROM mic_user WHERE isdel=0 and id=$uid";
        $query = $this->db->query($sql);
        $userObj->info = $query->row();
    }

    public function getUserById($userId)
    {
        $sql = "SELECT * FROM mic_user WHERE isdel=0 and  id=$userId";
        $query = $this->db->query($sql);
        return $query->row();
    }

    /**
     * 检查是否登录
     */
    public function checkLogin($user)
    {
        $result = false;

        if (isset($_SESSION['__UID']) && (int)$_SESSION['__UID'] > 0) $result = true;
        return $result;
    }


}