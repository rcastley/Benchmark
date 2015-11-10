<?php

class Application_Model_Users
{
    protected $_id;

    protected $_user;

    protected $_key;

    protected $_secret;

    protected $_cid;

    public function __construct(array $options = null)
    {
        if (is_array($options)) {
            $this->setOptions($options);
        }
    }

    public function __set($name, $value)
    {
        $method = 'set'.$name;
        if (('mapper' == $name) || !method_exists($this, $method)) {
            throw new Exception('Invalid property');
        }
        $this->$method($value);
    }

    public function __get($name)
    {
        $method = 'get'.$name;
        if (('mapper' == $name) || !method_exists($this, $method)) {
            throw new Exception('Invalid property');
        }

        return $this->$method();
    }

    public function setOptions(array $options)
    {
        $methods = get_class_methods($this);
        foreach ($options as $key => $value) {
            $method = 'set'.ucfirst($key);
            if (in_array($method, $methods)) {
                $this->$method($value);
            }
        }
        print_r ($this);
        return $this;
    }

    public function setUser($text)
    {
        $this->_user = (string) $text;

        return $this;
    }

    public function getUser()
    {
        return $this->_user;
    }

    public function setId($id)
    {
        $this->_id = (int) $id;

        return $this;
    }

    public function getId()
    {
        return $this->_id;
    }

    public function setKey($ts)
    {
        $this->_key = $ts;

        return $this;
    }

    public function getKey()
    {
        return $this->_key;
    }

    public function setSecret($ts)
    {
        $this->_secret = $ts;

        return $this;
    }

    public function getSecret()
    {
        return $this->_secret;
    }

    public function setCid($cid)
    {
        $this->_cid = (int) $cid;

        return $this;
    }

    public function getCid()
    {
        return $this->_cid;
    }
}
