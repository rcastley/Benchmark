<?php

class SettingsController extends Zend_Controller_Action
{
    protected $_user;

    protected $_key;

    protected $_secret;

    protected $_k;

    protected $_s;

    public function preDispatch()
    {
    }

    public function init()
    {
        if (!Zend_Auth::getInstance()->hasIdentity()) {
            // If the user is logged in, we don't want to show the login form;
          // however, the logout action should still be available
          $this->_helper->redirector('index', 'login');
        }

        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);

        $this->_user = Zend_Auth::getInstance()->getIdentity();
        $this->view->user = $this->_user;

        $user = new Application_Model_UsersMapper();

        $result = $user->fetchAll($this->_user);

        $this->_k = ($result->key);
        $this->_s = ($result->secret);
        $this->_key = base64_decode($result->key);
        $this->_secret = base64_decode($result->secret);

    }

    public function indexAction()
    {
        //$key = (empty($this->_getParam('key'))) ? $this->_key : $this->_getParam('key');
        //$secret = (empty($this->_getParam('secret'))) ? $this->_secret : $this->_getParam('secret');


        $key = ($this->_k != $this->_getParam('key')) ? $this->_getParam('key') : $this->_key;
        $secret = ($this->_s != $this->_getParam('secret')) ? $this->_getParam('secret') : $this->_secret;

        $settings = new Application_Model_Users(
          array(
            'user' => $this->_user,
            'key' => $key,
            'secret' => $secret,
            'cid' => $this->_getParam('cid')
          )
        );

        $mapper = new Application_Model_UsersMapper();

        $mapper->save($settings);

        $this->_helper->redirector('index', 'index');
    }
}
