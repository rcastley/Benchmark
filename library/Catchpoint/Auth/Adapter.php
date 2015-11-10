<?php

class Catchpoint_Auth_Adapter implements Zend_Auth_Adapter_Interface
{
    protected $_user;

    public function __construct($params)
    {
        $this->_user = $params ['username'];
    }

    public function authenticate()
    {
        $mapper = new Application_Model_UsersMapper();
        $checkUser = $mapper->fetchAll($this->_user);

        if (!$checkUser) {
            $newUser = new Application_Model_Users(array(
                    'user' => $this->_user
            ));

            $result = $mapper->create($newUser);
        }

        return new Zend_Auth_Result(Zend_Auth_Result::SUCCESS, $this->_user);
    }
}
