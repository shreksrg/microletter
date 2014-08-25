<?php

class CCApplication extends CComponent implements ArrayAccess
{

    /**
     * 获取CI实例
     */
    public function getCI()
    {
        return self::ci_instance();
    }

    /**
     * 静态获取CI实例
     */
    static protected function ci_instance()
    {
        return get_instance();
    }

    public function offsetExists($offset)
    {
        return array_key_exists($offset, $this->d);
    }

    public function offsetGet($offset)
    {
        return isset($this->d[$offset]) ? $this->d[$offset] : null;
    }

    public function offsetSet($offset, $value)
    {
        $this->d[$offset] = $value;
    }

    public function offsetUnset($offset)
    {
        if (isset($this->d[$offset])) {
            unset($this->d[$offset]);
            return true;
        }
        return false;
    }
}