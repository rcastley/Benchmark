<?php

class SettingsController extends Zend_Controller_Action
{
    protected $_user;

    protected $_key;

    protected $_secret;

    public function preDispatch()
    {
        if (!Zend_Auth::getInstance()->hasIdentity()) {
            // If the user is logged in, we don't want to show the login form;
            // however, the logout action should still be available
            $this->_helper->redirector('index', 'login');
        }
    }

    public function init()
    {
        $this->_user = Zend_Auth::getInstance()->getIdentity();
        $this->view->user = $this->_user;

        $user = new Application_Model_UsersMapper();

        $result = $user->fetchAll($this->_user);
        $this->_key = base64_decode($result->key);
        $this->_secret = base64_decode($result->secret);

        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);
    }

    public function indexAction()
    {
        $key = (!$this->_getParam('key')) ? $this->_key : $this->_getParam('key');
        $secret = (!$this->_getParam('secret')) ? $this->_secret : $this->_getParam('secret');

        $settings = new Application_Model_Users(
                array(
                    'user' => $this->_user,
                    'key' => $key,
                    'secret' => $secret,
                    'cid' => $this->_getParam('cid')
                ));

        $mapper = new Application_Model_UsersMapper();

        $mapper->settings($settings);

        $this->_helper->redirector('index', 'index');
    }
}
