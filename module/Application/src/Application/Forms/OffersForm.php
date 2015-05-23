<?php

namespace Application\Forms;

use Application\Models\Ads\Ad;
use Zend\Stdlib\Hydrator\ClassMethods as ClassMethodsHydrator;

class OffersForm extends AbstractForm
{
    public function create()
    {
        $this->setAttribute('method', 'post');
        $this->setName('contact');
        $this->setAttribute('id', 'contact');


        $this->add(array(
            'name' => 'x2',
            'type' => 'hidden',
            'attributes' => array(
                'textAbove' => 'Date de contact',
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
                'group' => array('size' => 'col-lg-4 col-md-4 col-sm-4 col-xs-12', 'type' => 'start'),
            ),
        ));
        $this->add(array(
            'type' => 'email',
            'name' => 'email',
            'options' => array(
                'label' => 'E-mail',
//                'options' => [''=>''],
            ),
            'attributes' => array(
                'group' => array('size' => 'col-lg-4 col-md-4 col-sm-4 col-xs-12', 'type' => ''),
            ),
        ));
        $this->add(array(
            'type' => 'text',
            'name' => 'phone',
            'options' => array(
                'label' => 'Telefon',
            ),
            'attributes' => array(
                'required' => true,
                'group' => array('size' => 'col-lg-4 col-md-4 col-sm-4 col-xs-12', 'type' => 'end'),
            ),
        ));


        $this->add(array(
            'name' => 'x3',
            'type' => 'hidden',
            'attributes' => array(
                'textAbove' => 'Detalii Piesa',
                'custom_form_spacer' => true,
            )
        ));


        $this->add(array(
            'type' => 'text',
            'name' => 'make',
            'options' => array(
                'label' => 'Marca',
            ),
            'attributes' => array(
                'required' => true,
                'group' => array('size' => 'col-lg-4 col-md-4 col-sm-4 col-xs-12', 'type' => 'start'),
                'extraInfo' => 'ex: Camion Volvo VNX 430'
            ),
        ));
        $this->add(array(
            'type' => 'text',
            'name' => 'model',
            'options' => array(
                'label' => 'Model',
            ),
            'attributes' => array(
                'required' => true,
                'group' => array('size' => 'col-lg-4 col-md-4 col-sm-4 col-xs-12', 'type' => ''),
                'extraInfo' => 'ex: Camion Volvo VNX 430'
            ),
        ));
        $this->add(array(
            'type' => 'text',
            'name' => 'year',
            'options' => array(
                'label' => 'An fabricatie',
            ),
            'attributes' => array(
                'required' => true,
                'group' => array('size' => 'col-lg-4 col-md-4 col-sm-4 col-xs-12', 'type' => 'end'),
                'extraInfo' => 'ex: 2012-2015'
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
