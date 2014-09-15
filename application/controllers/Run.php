<?php

class Run extends MicroController
{

    public function index()
    {




        return false;
        phpinfo();
        return false;

        //CModel::make('login_model');

        echo strtotime('+3 days 2 hours 14 minutes');
        //echo time();
        return false;

        echo $id = $this->input->get('id');
        d($id);
        return true;
        //$this->load->model('run_model');
        // $this->run_model->test();

        // d($this->uri->rsegment(2));


        // var_dump($diffTime);


        $retval = $this->DateDiff('d', 1409050905, 1409335310);
        $unit = array('y', 'm', 'w', 'd', 'h', 'n', 's');
        $lang = array('y' => '年', 'm' => '月', 'w' => '周', 'd' => '天', 'h' => '小时', 'n' => '分', 's' => '秒');
        foreach ($unit as $abbr) {
            $log[$abbr] = $this->DateDiff($abbr, 1409050905, 1409335310);
        }

        foreach ($log as $key => $value) {
            if ($value > 0) {
                echo $value . $lang[$key] . '前';
                break;
            }
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