<?php


class Login extends MicroController
{
    public function index()
    {
        $request = $this->input->server('REQUEST_METHOD');
        $reUrl = $this->input->get('reUrl');
        $request === 'GET' ? CView::show('login/index', array('reUrl' => $reUrl)) : $this->userLogin();
    }

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
     * 用户注册
     */
    public function register()
    {

    }

    /**
     * 用户登出
     */
    public function logout()
    {
        $responseArg = array('code' => 0, 'message' => 'logout complete', 'data' => null);
        $this->_user->logout();
        header('location:' . SITE_URL);
        return false;
        echo json_encode($responseArg);
    }


}