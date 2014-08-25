<?php

class User extends CUser
{
    public $username;
    public $password;

    public function getInfo()
    {
        return $this->getState('__userInfo');
    }

    public function setInfo($data)
    {
        $this->setState('__userInfo', $data);
    }

    public function logout()
    {
        $this->clearStates();
    }

}