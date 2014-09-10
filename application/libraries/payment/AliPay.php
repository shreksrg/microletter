<?php
Micro::import('application.libraries.payment.alipay.lib.*');

class AliPay extends CCApplication
{
    const ALIPAY_FORMAT = 'xml';
    const ALIPAY_VERSION = '2.0';


    /**
     * @var 本地接口URL配置
     */
    protected $_config_notify = null;

    /**
     * @var 安全支付配置
     */
    protected $_config_alipay = null;

    /**
     * @var 订单信息
     */
    protected $_order;

    /**
     * @var 请求号
     */
    protected $_requestId;

    /**
     * @var 授权令牌
     */
    protected $_authToken;


    public function __construct()
    {
        $this->getConfig();
    }

    /**
     * 设置订单信息参数
     */
    public function setOrder($data)
    {
        $order = new stdClass();
        $order->tradeNo = $data['pay_sn']; //支付订单单号
        $order->subject = $data['pay_title'];
        $order->gross = $data['amount'];
        $this->_order = $order;
    }

    /**
     * 获取配置
     */
    public function getConfig()
    {
        /*$config = array(
            'notify_url' => SITE_URL . '/payment/notify?type=2',
            'call_back_url' => SITE_URL . '/payment/notify?type=2',
            'merchant_url' => SITE_URL . '/payment/merchant?type=2',
        );*/

        $this->_config_notify = require(APPPATH . '/config/payment/notify.php');
        $this->_config_alipay = require(APPPATH . '/config/payment/alipay.php');
    }

    /**
     * 获取授权码
     */
    protected function getAuthToken()
    {
        //请求号，须保证每次请求都是唯一
        $req_id = $this->_requestId = date('Ymdhis');

        //通知url参数
        $config = $this->_config_notify;

        //订单信息
        $order = $this->_order;

        //请求业务参数详细
        $req_data = '<direct_trade_create_req><notify_url>' . $config['notify_url'] . '</notify_url><call_back_url>' . $config['call_back_url'] . '</call_back_url><seller_account_name>' . $this->_config_alipay['seller_mail'] . '</seller_account_name><out_trade_no>' . $order->tradeNo . '</out_trade_no><subject>' . $order->subject . '</subject><total_fee>' . $order->gross . '</total_fee><merchant_url>' . $config['merchant_url'] . '</merchant_url></direct_trade_create_req>';

        /************************************************************/

        //构造要请求的参数数组，无需改动
        $para_token = array(
            "service" => "alipay.wap.trade.create.direct",
            "partner" => trim($this->_config_alipay['partner']),
            "sec_id" => trim($this->_config_alipay['sign_type']),
            "format" => self::ALIPAY_FORMAT,
            "v" => self::ALIPAY_VERSION,
            "req_id" => $req_id,
            "req_data" => $req_data,
            "_input_charset" => trim(strtolower($this->_config_alipay['input_charset']))
        );

        //建立请求
        $alipaySubmit = new AlipaySubmit($this->_config_alipay);
        $html_text = $alipaySubmit->buildRequestHttp($para_token);

        //URLDECODE返回的信息
        $html_text = urldecode($html_text);

        //解析远程模拟提交后返回的信息
        $para_html_text = $alipaySubmit->parseResponse($html_text);

        //获取request_token
        return $this->_authToken = $para_html_text['request_token'];
    }

    /**
     * 提交授权订单
     */
    protected function submit($authToken)
    {
        //业务详细
        $req_data = '<auth_and_execute_req><request_token>' . $authToken . '</request_token></auth_and_execute_req>';
        //必填

        //构造要请求的参数数组，无需改动
        $parameter = array(
            "service" => "alipay.wap.auth.authAndExecute",
            "partner" => trim($this->_config_alipay['partner']),
            "sec_id" => trim($this->_config_alipay['sign_type']),
            "format" => self::ALIPAY_FORMAT,
            "v" => self::ALIPAY_VERSION,
            "req_id" => $this->_requestId,
            "req_data" => $req_data,
            "_input_charset" => trim(strtolower($this->_config_alipay['input_charset']))
        );

        //建立请求
        $alipaySubmit = new AlipaySubmit($this->_config_alipay);
        $html_form = $alipaySubmit->buildRequestForm($parameter, 'get', '确认');

        echo $html = <<< EOF
        <!doctype html>
        <html lang="en"><head><meta charset="UTF-8"></head>
        <body>$html_form</body></html>
EOF;
    }

    /**
     * 执行支付交易
     */
    public function disburse($data = null)
    {
        if ($data !== null) {
            $this->setOrder($data);
        }
        $token = $this->getAuthToken();
        $this->submit($token);
    }


    public function verify()
    {
        $config = $this->_config_alipay;
        $notify = new AlipayNotify($config);
        return $verify_result = $notify->verifyReturn();
    }

    /**
     * 支付通知接口
     */
    public function notify($postData)
    {
        $_notifyData = array();

        //计算得出通知验证结果
        $config = $this->_config_alipay;
        $notify = new AlipayNotify($config);
        $verify_result = $notify->verifyNotify();

        if ($verify_result) { //验证成功
            //解析notify_data
            $doc = new DOMDocument();
            if ($config['sign_type'] == 'MD5') {
                $doc->loadXML($postData);
            }

            if ($config['sign_type'] == '0001') {
                $doc->loadXML($notify->decrypt($postData));
            }

            if (!empty($doc->getElementsByTagName("notify")->item(0)->nodeValue)) {
                //商户订单号
                $out_trade_no = $doc->getElementsByTagName("out_trade_no")->item(0)->nodeValue;
                //支付宝交易号
                $trade_no = $doc->getElementsByTagName("trade_no")->item(0)->nodeValue;
                //交易金额
                $gross = $doc->getElementsByTagName("total_fee")->item(0)->nodeValue;
                //交易状态
                $trade_status = $doc->getElementsByTagName("trade_status")->item(0)->nodeValue;

                $_notifyData['status'] = 0;
                $_notifyData['paySn'] = $out_trade_no;
                $_notifyData['tradeSn'] = $trade_no;
                $_notifyData['gross'] = $gross;

                if ($trade_status == 'TRADE_FINISHED') {
                    //判断该笔订单是否在商户网站中已经做过处理
                    //如果没有做过处理，根据订单号（out_trade_no）在商户网站的订单系统中查到该笔订单的详细，并执行商户的业务程序
                    //如果有做过处理，不执行商户的业务程序

                    //注意：
                    //该种交易状态只在两种情况下出现
                    //1、开通了普通即时到账，买家付款成功后。
                    //2、开通了高级即时到账，从该笔交易成功时间算起，过了签约时的可退款时限（如：三个月以内可退款、一年以内可退款等）后。

                    //调试用，写文本函数记录程序运行情况是否正常
                    //logResult("这里写入想要调试的代码变量值，或其他运行的结果记录");

                    echo "success"; //请不要修改或删除
                    $_notifyData['status'] = 1;
                    return $_notifyData;
                } else if ($trade_status == 'TRADE_SUCCESS') {
                    //判断该笔订单是否在商户网站中已经做过处理
                    //如果没有做过处理，根据订单号（out_trade_no）在商户网站的订单系统中查到该笔订单的详细，并执行商户的业务程序
                    //如果有做过处理，不执行商户的业务程序

                    //注意：
                    //该种交易状态只在一种情况下出现——开通了高级即时到账，买家付款成功后。

                    //调试用，写文本函数记录程序运行情况是否正常
                    //logResult("这里写入想要调试的代码变量值，或其他运行的结果记录");

                    echo "success"; //请不要修改或删除
                } else {
                    $_notifyData['status'] = -1;
                    return $_notifyData;
                }
            }
        }
        return false;

    }
}
