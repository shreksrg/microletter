<?php

require(dirname(__FILE__) . '/MicroBase.php');

class Micro extends MicroBase
{
    public $ci = null;

    static public function init()
    {
        $micro = new self();
        $micro->setPathOfAlias('application', APPPATH);
        self::import('application.core.*');
        self::import('application.controllers.*');
        self::import('application.models.*');
        self::import('application.libraries.*');
        self::import('application.libraries.components.*');
        self::import('application.libraries.web.*');

        // self::initSession();
    }

    public static function openSession()
    {
        @session_start();
    }


}
