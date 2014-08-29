<?php

class CAjax extends CCApplication
{
    static public function show($code, $message, $data = null, $return = false)
    {
        $responseData = array('code' => $code, 'message' => $message, 'data' => $data);
        $json = json_encode($responseData);
        if ($return == false) {
            echo $json;
            exit(0);
        } else return $json;
    }
}