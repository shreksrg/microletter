<?php

class Run extends MicroController
{

    public function index()
    {
        $str = '123456789564550123456789,123456789564550123456789,123456789564550123456789,123456789564550123456789,123456789564550123456789,123456789564550123456789,123456789564550123456789,123456789564550123456789,123456789564550123456789,123456789564550123456789,123456789564550123456789,123456789564550123456789,123456789564550123456789,123456789564550123456789,123456789564550123456789,123456789564550123456789,123456789564550123456789,123456789564550123456789,123456789564550123456789,123456789564550123456789,123456789564550123456789,123456789564550123456789,123456789564550123456789,123456789564550123456789,123456789564550123456789,123456789564550123456789,123456789564550123456789,123456789564550123456789,123456789564550123456789,123456789564550123456789,123456789564550123456789,123456789564550123456789,123456789564550123456789123456789564550123456789123456789564550123456789123456789564550123456789,123456789564550123456789,123456789564550123456789,123456789564550123456789,123456789564550123456789,123456789564550123456789';
         $gzdeflate = base64_encode(gzdeflate($str, 9));
       echo  gzinflate(base64_decode($gzdeflate));
        return false;

        $data = '2, 4, 5, 6, 6, 7, 8888888,3243242424232323232323232';
        $enctry = base64_encode($data);
        $data = base64_encode($enctry);
        var_dump($data);

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


    public function t()
    {

        function encrypt($data, $key)
        {
            $char = '';
            $str = '';
            $key = md5($key);
            $x = 0;
            $len = strlen($data);
            $l = strlen($key);
            for ($i = 0; $i < $len; $i++) {
                if ($x == $l) {
                    $x = 0;
                }
                $char .= $key{$x};
                $x++;
            }
            for ($i = 0; $i < $len; $i++) {
                $str .= chr(ord($data{$i}) + (ord($char{$i})) % 256);
            }
            return base64_encode($str);
        }

        function decrypt($data, $key)
        {
            $key = md5($key);
            $x = 0;
            $data = base64_decode($data);
            $len = strlen($data);
            $l = strlen($key);
            for ($i = 0; $i < $len; $i++) {
                if ($x == $l) {
                    $x = 0;
                }
                $char .= substr($key, $x, 1);
                $x++;
            }
            for ($i = 0; $i < $len; $i++) {
                if (ord(substr($data, $i, 1)) < ord(substr($char, $i, 1))) {
                    $str .= chr((ord(substr($data, $i, 1)) + 256) - ord(substr($char, $i, 1)));
                } else {
                    $str .= chr(ord(substr($data, $i, 1)) - ord(substr($char, $i, 1)));
                }
            }
            return $str;
        }


        $data = 'PHP加密解密算法,PHP加密解密算法PHP加密解密算法PHP加密解密算法PHP加密解密算法PHP加密解密算法'; // 被加密信息
        $key = '123'; // 密钥
        $encrypt = @encrypt($data, $key);
        $decrypt = @decrypt($encrypt, $key);
        echo $encrypt, "\n", $decrypt;


    }
}