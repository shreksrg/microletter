<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class AdminController extends CI_Controller
{
    protected $_user;

    public function __construct()
    {
        parent::__construct();
        micro::openSession();
        $this->_init();
        defined('REQUEST_METHOD') or define('REQUEST_METHOD', $this->input->server('REQUEST_METHOD'));
        defined('MCIRO_WEBSITE_TITLE') or define('MCIRO_WEBSITE_TITLE', '人品大挑战管理平台V1.0');
    }

    protected function _init()
    {
        $this->_user = new User();
        $this->_user->stateKeyPrefix = base64_encode('_microLetter_adminKey');
        if (method_exists($this, '_authentication')) {
            call_user_func_array(array($this, '_authentication'), array());
        }
    }

    protected function _authentication()
    {
        if ($this->_user->isGuest) {
            header('location:' . SITE_URL . '/admin/loginman');
            die(0);
        }
    }
}