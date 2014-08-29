<?php

class CView extends CCApplication
{
    static public function show($file, $data = null, $return = false)
    {
        self::ci_instance()->load->view($file, $data, $return);
    }
}