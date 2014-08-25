<?php
defined('SITE_URL') or define('SITE_URL', '.');

class MicroController extends CI_Controller
{
    protected $_user;

    public function __construct()
    {
        parent::__construct();
        $this->_init();
    }

    protected function _init()
    {
        $this->_user = new User();
        if (method_exists($this, '_authentication')) {
            call_user_func_array(array($this, '_authentication'), array());
        }
    }
}