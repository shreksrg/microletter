<?php

class Run extends MicroController
{
    public function index()
    {
        //$this->load->model('run_model');
       // $this->run_model->test();

        $st = time();
        $et = strtotime("+3 days 7 hours 5 seconds");
        $diffTime = Utils::getDiffTime($st, $et);
        var_dump($diffTime);
    }
}