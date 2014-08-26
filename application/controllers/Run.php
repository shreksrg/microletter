<?php

class Run extends MicroController
{
    public function index()
    {
        //$this->load->model('run_model');
        // $this->run_model->test();

        d( $this->uri->rsegment(2));

        $st = time();
        $et = strtotime("+3 days 7 hours 5 seconds");
        $diffTime = Utils::getDiffTime($st, $et);
       // var_dump($diffTime);
    }

    public function test(){
        d( $this->uri->rsegment(2));
    }
}