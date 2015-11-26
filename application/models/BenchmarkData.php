<?php

class Application_Model_BenchmarkData
{
    protected $_id;

    protected $_cid;

    protected $_name;

    protected $_dns;

    protected $_wait;

    protected $_load;

    protected $_bytes;

    protected $_docComplete;

    protected $_webpageResponse;

    protected $_items;

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
        $this->_name =  (string) $name;

        return $this;
    }

    public function getName()
    {
        return $this->_name;
    }

    public function setDns($dns)
    {
        $this->_dns = $dns;

        return $this;
    }

    public function getDns()
    {
        return $this->_dns;
    }
    public function setWait($wait)
    {
        $this->_wait = $wait;

        return $this;
    }

    public function getWait()
    {
        return $this->_wait;
    }
    public function setLoad($load)
    {
        $this->_load = $load;

        return $this;
    }

    public function getLoad()
    {
        return $this->_load;
    }
    public function setBytes($bytes)
    {
        $this->_bytes = $bytes;

        return $this;
    }

    public function getBytes()
    {
        return $this->_bytes;
    }

    public function setDocComplete($doc_complete)
    {
        $this->_docComplete = $doc_complete;

        return $this;
    }

    public function getDocComplete()
    {
        return $this->_docComplete;
    }

    public function setWebpageResponse($webpage_response)
    {
        $this->_webpageResponse = $webpage_response;

        return $this;
    }

    public function getWebpageResponse()
    {
        return $this->_webpageResponse;
    }
    public function setItems($items)
    {
        $this->_items = $items;

        return $this;
    }

    public function getItems()
    {
        return $this->_items;
    }
}
