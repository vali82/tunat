<?php

namespace Application\Forms;


use Application\Models\Autoparks\Park;
use Zend\Stdlib\Hydrator\ClassMethods as ClassMethodsHydrator;

class MyAccountForm extends AbstractForm
{
    public function changeMyAccount()
    {
        $this->setHydrator(new ClassMethodsHydrator(false))
            ->setObject(new Park());

        $this->setAttribute('method', 'post');


        $this->add(array(
            'name' => 'email',
            'attributes' => array(
                'type' => 'text',
                'readonly' => 'readonly',
                //'required' => true
            ),
            'options' => array(
                'label' => 'Email',
            ),
        ));


        $this->add(array(
            'name' => 'name',
            'options' => array(
                'label' => 'Nume Parc Auto',
            ),
            'attributes' => array(
//                'group' => array('size' => 'col-sm-4', 'type' => 'start'),
                'type' => 'text',
                'id' => 'name',
                //'required' => true
            ),
        ));

        $this->add(array(
            'name' => 'tel1',
            'options' => array(
                'label' => 'Telefon 1',
            ),
            'attributes' => array(
                'group' => array('size' => 'col-sm-2', 'type' => 'start'),
                'type' => 'text',
            ),
        ));
        $this->add(array(
            'name' => 'tel2',
            'options' => array(
                'label' => 'Telefon 2',
            ),
            'attributes' => array(
                'group' => array('size' => 'col-sm-2', 'type' => ''),
                'type' => 'text',
            ),
        ));
        $this->add(array(
            'name' => 'tel3',
            'options' => array(
                'label' => 'Telefon 3',
            ),
            'attributes' => array(
                'group' => array('size' => 'col-sm-2', 'type' => 'end'),
                'type' => 'text',
            ),
        ));

        $this->add(array(
            'name' => 'address',
            'type' => 'text',
            'options' => array(
                'label' => 'Adressa',
            ),
            'attributes' => array(
                'placeholder' => 'Adresa: strada, nr.',
                'group' => array('size' => 'col-sm-4', 'type' => 'start'),
            ),
        ));
        $this->add(array(
            'name' => 'city',
            'type' => 'text',
            'options' => array(
                'label' => 'Oras',
            ),
            'attributes' => array(
//                'noLabel' => true,
                'group' => array('sizeLabel' => 'col-sm-1', 'size' => 'col-sm-3', 'type' => ''),
            ),
        ));
        $this->add(array(
            'name' => 'state',
            'type' => 'text',
            'options' => array(
                'label' => 'Adressa',
            ),
            'attributes' => array(
                'noLabel' => true,
                'group' => array('size' => 'col-sm-2', 'type' => 'end'),
            ),
        ));

        $this->add(array(
            'name' => 'url',
            'options' => array(
                'label' => 'Site web',
            ),
            'attributes' => array(
                'placeholder' => 'http://',
//                'group' => array('size' => 'col-sm-4', 'type' => 'start'),
                'type' => 'text',
                'id' => 'url',
                //'required' => true
            ),
        ));

        $this->add(array(
            'name' => 'description',
            'options' => array(
                'label' => 'Descriere',
            ),
            'attributes' => array(
                'type' => 'textarea',
                'id' => 'description',
                //'required' => true
            ),
        ));

        $this->add(array(
            'name' => 'imagefile',
            'type' => 'file',
            'options' => array(
                'label' => 'Logo',
            ),
            'attributes' => array(
                'id' => 'image-file',
                'class' => 'x'
            ),
        ));


        $this->add(array(
            'name' => 'submit',
            'attributes' => array(
                'type' => 'submit',
                'value' => 'Salveaza',
                'id' => 'submitbutton',
                'cancelLink' => $this->_cancel_route
            ),
        ));
    }

}