<?php

class CCApplication extends CComponent implements ArrayAccess
{
    protected $_dry;

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
        return array_key_exists($offset, $this->_dry);
    }

    public function offsetGet($offset)
    {
        return isset($this->_dry[$offset]) ? $this->_dry[$offset] : null;
    }

    public function offsetSet($offset, $value)
    {
        $this->_dry[$offset] = $value;
    }

    public function offsetUnset($offset)
    {
        if (isset($this->_dry[$offset])) {
            unset($this->_dry[$offset]);
            return true;
        }
        return false;
    }
}