<?php

class CUser extends CCApplication
{
    private $_keyPrefix;

    public function setStateKeyPrefix($value)
    {
        $this->_keyPrefix = $value;
    }

    public function setState($key, $value, $defaultValue = null)
    {
        $key = $this->getStateKeyPrefix() . $key;
        if ($value === $defaultValue)
            unset($_SESSION[$key]);
        else
            $_SESSION[$key] = $value;
    }

    public function getIsGuest()
    {
        return $this->getState('__id') === null;
    }

    public function getId()
    {
        return $this->getState('__id');
    }


    public function setId($value)
    {
        $this->setState('__id', $value);
    }

    /**
     * 获取状态键名前缀
     */
    public function getStateKeyPrefix()
    {
        if ($this->_keyPrefix !== null)
            return $this->_keyPrefix;
        else
            return $this->_keyPrefix = sprintf('%x', crc32(get_class($this)));
    }

    public function getState($key, $defaultValue = null)
    {
        $key = $this->getStateKeyPrefix() . $key;
        return isset($_SESSION[$key]) ? $_SESSION[$key] : $defaultValue;
    }

    public function hasState($key)
    {
        $key = $this->getStateKeyPrefix() . $key;
        return isset($_SESSION[$key]);
    }

    public function clearStates()
    {
        $keys = array_keys($_SESSION);
        $prefix = $this->getStateKeyPrefix();
        $n = strlen($prefix);
        foreach ($keys as $key) {
            if (!strncmp($key, $prefix, $n))
                unset($_SESSION[$key]);
        }
    }

}