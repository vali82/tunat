<?php

namespace Application\Forms;

use Application\Models\Ads\Ad;
use Zend\Stdlib\Hydrator\ClassMethods as ClassMethodsHydrator;

class ContactForm extends AbstractForm
{
    public function contact()
    {
        $this->setAttribute('method', 'post');
        $this->setName('contact');
        $this->setAttribute('id', 'contact');


        $this->add(array(
            'name' => 'x2',
            'type' => 'hidden',
            'attributes' => array(
                'textAbove' => 'Trimite-ne mesajul tau si iti vom raspunde in maxim 24 ore!',
                'custom_form_spacer' => true,
            )
        ));

        $this->add(array(
            'type' => 'text',
            'name' => 'name',
            'options' => array(
                'label' => 'Nume',
//                'options' => [''=>''],
            ),
            'attributes' => array(
                'group' => array('size' => 'col-lg-6 col-md-6 col-sm-6 col-xs-12', 'type' => 'start'),
            ),
        ));

        $this->add(array(
            'type' => 'email',
            'name' => 'email',
            'options' => array(
                'label' => 'E-mail',
            ),
            'attributes' => array(
                'required' => true,
                'group' => array('size' => 'col-lg-6 col-md-6 col-sm-6 col-xs-12', 'type' => 'end'),
            ),
        ));

        $this->add(array(
            'type' => 'text',
            'name' => 'subject',
            'options' => array(
                'label' => 'Subiect',
            ),
            'attributes' => array(
                'required' => true,
            ),
        ));


        $this->add(array(
            'name' => 'message',
            'type' => 'textarea',
            'options' => array(
                'label' => 'Descriere',
            ),
            'attributes' => array(
                'rows' => 6
            ),
        ));



        $this->add(array(
            'name' => 'submit',
            'attributes' => array(
                'type' => 'submit',
                'value' => 'Trimite',
                'id' => 'submitbutton',
                'cancelLink' => $this->_cancel_route
            ),
        ));
    }
}
