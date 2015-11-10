<?php

class Application_Form_Login extends Zend_Form
{
    public function init()
    {
        $this->setAttribs(
                array(
                        'class' => 'form-signin',
                        'action' => '/login/process',
                        'role' => 'form',
                        'enctype' => null,
                ));

        $this->setDecorators(array(
                'FormElements',
                'Form',
        ));

        $username = $this->addElement('text', 'username',
                array(
                        'filters' => array(
                                'StringTrim',
                        ),
                        'required' => true,
                        'placeholder' => 'Username',
                        'class' => 'form-control',
                ));

        $login = $this->addElement('button', 'login',
                array(
                        'required' => false,
                        'ignore' => true,
                        'label' => 'Login',
                        'type' => 'submit',
                        'class' => 'btn btn-lg btn-primary btn-block',
                ));

        $this->username->removeDecorator('HtmlTag');
        $this->username->removeDecorator('Label');
        $this->login->removeDecorator('DtDdWrapper');
    }
}
