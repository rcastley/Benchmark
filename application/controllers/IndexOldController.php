<?php

class IndexController extends Zend_Controller_Action
{
    protected $_user;

    protected $_state;

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

        $user = new Application_Model_UsersMapper();

        $result = $user->fetchAll($this->_user);
        $this->view->user = $result->user;
        $this->_state = $result->state;
        $this->view->state = $result->state;
        $this->view->key = (empty($result->key)) ? '' : base64_decode($result->key);
        $this->view->secret = (empty($result->secret)) ? '' : base64_decode($result->secret);
        $this->view->chartId = (empty($result->chart_id)) ? '' : $result->chart_id;

        $siteOptions = array(
                'default' => array('refresh', 'Default'),
                'validation_error' => array('flash', 'Validation Error'),
                'css_error' => array('file-text-o', 'CSS Error'),
                'js_error' => array('file-code-o', 'JavsScript Error'),
                '404' => array('info-circle', '40x Error'),
                '500' => array('warning', '50x Error'),
                'delay' => array('circle-o-notch fa-spin', 'Content Delay'),
                'timeout' => array('spinner fa-spin', 'Timeout'),
                'cookies' => array('globe', 'Cookies'),
                'hosts_and_zones' => array('filter', 'Hosts &amp; Zones'),
                'chrome' => array('chrome', 'Chrome'),
                'glimpse' => array('users', 'Glimpse'),
        );

        $this->view->menu = $siteOptions;
    }

    public function setAction()
    {
        $this->state = $this->_getParam('state');

        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);

        $site = new Application_Model_Site(
                array(
                        'user' => $this->_user,
                        'state' => $this->state,
                ));

        $mapper = new Application_Model_SiteMapper();

        $mapper->save($site);
    }

    public function indexAction()
    {
        $this->_helper->viewRenderer->setNoRender(true);
        $this->renderScript('site/'.$this->_state.'.phtml');
    }

    public function stateAction()
    {
        $state = $this->_getParam('type');
        $this->renderScript('site/'.$state.'.phtml');
        $site = new Application_Model_Site(
                array(
                        'user' => $this->_user,
                        'state' => $state,
                ));

        $mapper = new Application_Model_SiteMapper();

        $mapper->save($site);

        $this->_helper->redirector('index', 'index');
    }
}
