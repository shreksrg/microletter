<?php

class CModel extends CCApplication
{
    static protected $_queue = array();

    static public function get($key)
    {
        $queue = self::$_queue;
        return isset($queue[$key]) ? $queue[$key] : null;
    }

    static public function set($key, $model)
    {
        self::$_queue[$key] = $model;
    }

    static public function make($file, $alias = '')
    {
        $key = $file;
        if (($aliasLen = ($alias)) > 0) $key = $alias;

        if (!isset(self::$_queue[$key])) {
            $ci = self::ci_instance();
            $aliasLen > 0 ? $ci->load->model($file, $alias) : $ci->load->model($file);
            self::set($key, $ci->$key);
        }
        return self::$_queue[$key];
    }
}