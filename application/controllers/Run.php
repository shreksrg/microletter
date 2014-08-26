<?php

class Run extends MicroController
{
    public function index()
    {
        //$this->load->model('run_model');
        // $this->run_model->test();

        // d($this->uri->rsegment(2));


        // var_dump($diffTime);


        $retval = $this->DateDiff('d', 1409050905, 1409335310);
        $unit = array('y', 'm', 'w', 'd', 'h', 'n', 's');
        $log = array();
        foreach ($unit as $abbr) {
            $log[$abbr] = $this->DateDiff($abbr, 1409050905, 1409335310);
        }
    }

    public function DateDiff($part, $begin, $end)
    {
        $diff = $end - $begin;

        switch ($part) {
            case "y":
                $retval = bcdiv($diff, (60 * 60 * 24 * 365));
                break;
            case "m":
                $retval = bcdiv($diff, (60 * 60 * 24 * 30));
                break;
            case "w":
                $retval = bcdiv($diff, (60 * 60 * 24 * 7));
                break;
            case "d":
                $retval = bcdiv($diff, (60 * 60 * 24));
                break;
            case "h":
                $retval = bcdiv($diff, (60 * 60));
                break;
            case "n":
                $retval = bcdiv($diff, 60);
                break;
            case "s":
                $retval = $diff;
                break;
        }
        return $retval;
    }
}