<?php


class Login_model extends CI_Model
{
    private $_uid;
    private $_validateFlag = false;
    protected $_user;

    function __construct()
    {
        parent::__construct();
    }

    /**
     * 验证用户登录
     */
    public function validate($username, $password)
    {
        $sql = "SELECT * FROM mic_user WHERE username = ? AND password = ?";
        $query = $this->db->query($sql, array($username, md5($password)));
        if ($query->num_rows() > 0) {
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
            $sql = "SELECT * FROM mic_user WHERE id={$this->_uid}";
            $query = $this->db->query($sql);
            if ($query->num_rows() > 0) {
                $this->_user->id = $this->_uid;
                $this->_user->info = $query->row();
            }
            $return = true;
        }
        return $return;
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