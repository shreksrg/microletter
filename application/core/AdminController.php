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
    }

    protected function _init()
    {
        $this->_user = new User();
        if (method_exists($this, '_authentication')) {
            call_user_func_array(array($this, '_authentication'), array());
        }
    }
}