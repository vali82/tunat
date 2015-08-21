<?php

namespace Application\Forms;

use Zend\Form\Form;
use Application\Models\Ads\Ad;
use Zend\Stdlib\Hydrator\ClassMethods as ClassMethodsHydrator;

class ChangePasswordForm extends AbstractForm
{
    public function resetPass()
    {
        $this->setAttribute('method', 'post');


        $this->add(array(
            'name' => 'email',
            'attributes' => array(
                'type'  => 'email',
                //'required' => true
                'readonly'=>true,
            ),
            'options' => array(
                'label' => 'Email',
            ),
        ));


        $this->add(array(
            'name' => 'password',
            'attributes' => array(
                'type'  => 'password',
            ),
            'options' => array(
                'label' => 'Parola Noua',
            ),
        ));
        $this->add(array(
            'name' => 'password_retype',
            'attributes' => array(
                'type'  => 'password',
            ),
            'options' => array(
                'label' => 'Confirma Parola',
            ),
        ));



        $this->add(array(
            'name' => 'submit',
            'attributes' => array(
                'type'  => 'submit',
                'value' => 'Salveaza',
                'id' => 'submitbutton',
                'cancelLink' => $this->_cancel_route
            ),
        ));
    }
}