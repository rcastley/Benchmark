<?php

class LoginController extends Zend_Controller_Action
{
    public function init()
    {
        $this->_helper->layout->setLayout('login');
    }

    public function getForm()
    {
        return new Application_Form_Login(
                array(
                        'action' => '/login/process',
                        'method' => 'post',
                ));
    }

    public function preDispatch()
    {
        if (Zend_Auth::getInstance()->hasIdentity()) {
            // If the user is logged in, we don't want to show the login form;
            // however, the logout action should still be available
            if ('logout' != $this->getRequest()->getActionName()) {
                $this->_helper->redirector('index', 'index');
            }
        } else {
            // If they aren't, they can't logout, so that action should
            // redirect to the login form
            if ('logout' == $this->getRequest()->getActionName()) {
                $this->_helper->redirector('index');
            }
        }
    }

    public function indexAction()
    {
        $this->view->form = $this->getForm();
    }

    public function processAction()
    {
        $request = $this->getRequest();

        // Check if we have a POST request
        if (!$request->isPost()) {
            return $this->_helper->redirector('index');
        }

        // Get our form and validate it
        $form = $this->getForm();
        if (!$form->isValid($request->getPost())) {
            // Invalid entries
            $this->view->form = $form;

            return $this->render('index'); // re-render the login form
        }

        $auth = Zend_Auth::getInstance();
        $result = $auth->authenticate(new Catchpoint_Auth_Adapter($form->getValues()));

        if (!$result->isValid()) {
            // Invalid credentials
            $form->setDescription('Invalid credentials provided');
            $this->view->form = $form;

            return $this->render('index'); // re-render the login form
        }

        // We're authenticated! Redirect to the home page
        $this->_helper->redirector('index', 'index');
    }

    public function logoutAction()
    {
        Zend_Auth::getInstance()->clearIdentity();
        Zend_Session::namespaceUnset('Catchpoint');
        $this->_helper->redirector('index'); // back to login page
    }
}
