<?php

class Payment extends MicroController
{

    public function paying()
    {
        
    }

    // 支付宝
    protected function aliPay()
    {

        return true;
    }

    //银联支付
    protected function unionPay()
    {

    }

    // 微信支付
    protected function microPay()
    {

    }


    //其他支付
    protected function otherPay()
    {

    }
}
