<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends MicroController
{
    /**
     * 用户登录
     */
    public function userLogin()
    {
        $username = $this->input->post('username', true);
        $password = $this->input->post('password', true);

        $this->_user->username = trim($username);
        $this->_user->password = $password;

        $this->load->model('login_model', '', true);
        $modLogin = $this->login_model;
        $boolean = $modLogin->authenticate($this->_user);

        $responseArg = array('code' => 10000, 'message' => '登录失败，请检查用户名或密码', 'data' => null);
        if ($boolean === true && $modLogin->save()) {
            $responseArg['code'] = 0;
            $responseArg['message'] = 'successful';
        }
        echo json_encode($responseArg);
    }

    /**
     * 用户登出
     */
    public function logout()
    {
        $responseArg = array('code' => 0, 'message' => 'logout complete', 'data' => null);
        $this->_user->logout();
        echo json_encode($responseArg);
    }

    public function index()
    {
        $request = $this->input->server('REQUEST_METHOD');
        $request === 'GET' ? CView::show('login/index') : $this->userLogin();
    }
}