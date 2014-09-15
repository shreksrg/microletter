<?php

class UserMan extends AdminController
{

    public function __construct()
    {
        parent::__construct();
        $this->_modelUser = CModel::make('admin/userman_model', 'userMan_model');
    }

    public function index()
    {
         CView::show('admin/user/index');
    }

    /**
     * ajax用户列表
     */
    public function users()
    {
        $page = (int)$this->input->get('page');
        $rows = (int)$this->input->get('rows');
        $list = $this->_modelUser->getList($page, $rows);
        echo json_encode($list);
    }

    /**
     * 新增用户
     */
    public function append()
    {
        if (REQUEST_METHOD == 'POST') {
            $data = $this->input->post();

            // 表单输入验证
            /* $validator = $this->validateForm();
             if ($validator->run() == false)
                 CAjax::show('1000', '表单输入值不合法,请检查');*/

            $repArr = array(0, 'successful');
            $reVal = $this->_modelItem->newItem($data);
            if ($reVal !== true) {
                $repArr[0] = $this->_modelItem->errCode;
                $repArr[1] = '创建项目失败';
            }
            CAjax::show($repArr[0], $repArr[1]);
        } else {
            CView::show('admin/item/new');
        }
    }

    /**
     * 编辑用户
     */
    public function edit()
    {
        if (REQUEST_METHOD == 'POST') {
            $data = $this->input->post();
            // 表单输入验证
            /*$validator = $this->validateForm();
            if ($validator->run() == false)
                CAjax::show('1000', '表单输入值不合法,请检查');*/
            $reVal = $this->_modelUser->editUser($data);
            $repArr = array('0', 'successful');
            if ($reVal !== true) $repArr = array('1001', 'fail');
            CAjax::show($repArr[0], $repArr[1]);
        } else {
            $id = $this->input->get('id');
            $data['user'] = $this->_modelUser->getUser($id);
            CView::show('admin/user/edit', $data);
        }
    }

    /**
     * 删除用户
     */
    public function drop()
    {
        $id = $this->input->post('id');
        $return = $this->_modelUser->deleteUser($id);
        $repArr = array('0', 'successful');
        if ($return !== true) $repArr = array('1001', 'fail');
        CAjax::show($repArr[0], $repArr[1]);
    }

    /**
     * 编辑密码
     */
    public function  password()
    {
        $id = $this->input->get('id');
        $password = $this->input->post('password');
        $return = $this->_modelUser->setPassword($id,$password);
        $repArr = array('0', 'successful');
        if ($return !== true) $repArr = array('1001', 'fail');
        CAjax::show($repArr[0], $repArr[1]);
    }

    protected function  validateForm()
    {
        $validator = FormValidation::make();
        $validator->set_rules('title', 'Title', 'required|xss_clean');
        $validator->set_rules('source', 'Source', 'required|numeric|xss_clean');
        $validator->set_rules('price', 'Price', 'required|numeric|xss_clean');
        $validator->set_rules('img', 'Img', 'required|max_length[512]|xss_clean');
        $validator->set_rules('status', 'Status', 'required|integer|xss_clean');
        $validator->set_rules('desc', 'Desc', 'required|xss_clean');
        return $validator;
    }

}