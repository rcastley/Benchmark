<?php

class Application_Model_UsersMapper
{
    protected $_dbTable;

    public function setDbTable($dbTable)
    {
        if (is_string($dbTable)) {
            $dbTable = new $dbTable();
        }
        if (!$dbTable instanceof Zend_Db_Table_Abstract) {
            throw new Exception('Invalid table data gateway provided');
        }
        $this->_dbTable = $dbTable;

        return $this;
    }

    public function getDbTable()
    {
        if (null === $this->_dbTable) {
            $this->setDbTable('Application_Model_DbTable_Users');
        }

        return $this->_dbTable;
    }

    public function create(Application_Model_Users $user)
    {
        $data = array(
                'user' => $user->getUser()
        );

        $this->getDbTable()->insert($data);
    }

    public function save(Application_Model_Users $user)
    {
        /*
        $data = array(
                'user' => $user->getUser(),
        );

        $this->getDbTable()->update($data,
                array(
                  'user = ?' => $user->getUser(),
                ));
        */
        $data = array(
          'user' => $user->getUser(),
          'key' => base64_encode($user->getKey()),
          'secret' => base64_encode($user->getSecret()),
          'cid' => $user->getCid(),
        );

        $this->getDbTable()->update($data, array('user = ?' => $user->getUser()));
    }

    public function settings(Application_Model_Users $user)
    {
        $data = array(
                'user' => $user->getUser(),
                'key' => base64_encode($user->getKey()),
                'secret' => base64_encode($user->getSecret()),
                'cid' => $user->getCid(),
        );

        $this->getDbTable()->update($data,
                array(
                        'user = ?' => $user->getUser(),
                ));
    }

    public function fetchAll($user)
    {
        $query = $this->getDbTable()
            ->select()
            ->where('user = ?', $user);
        $resultSet = $this->getDbTable()->fetchRow($query);

        return $resultSet;
    }
}
