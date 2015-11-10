<?php

class Application_Model_BenchmarkCharts
{
    protected $_id;

    protected $_cid;

    protected $_name;

    protected $_modified;

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

        return $this;
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

    public function setCid($cid)
    {
        $this->_cid = (int) $cid;

        return $this;
    }

    public function getCid()
    {
        return $this->_cid;
    }

    public function setName($name)
    {
        $this->_name = (string) $name;

        return $this;
    }

    public function getName()
    {
        return $this->_name;
    }

    public function setModified($date)
    {
        $this->_modified = (string) $date;

        return $this;
    }

    public function getModified()
    {
        return $this->_modified;
    }
}
