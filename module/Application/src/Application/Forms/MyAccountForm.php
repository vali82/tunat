<?php

namespace Application\Forms;


use Application\Models\Autoparks\Park;
use Zend\Stdlib\Hydrator\ClassMethods as ClassMethodsHydrator;

class MyAccountForm extends AbstractForm
{
    public function changeMyAccount($states)
    {
        $this->setHydrator(new ClassMethodsHydrator(true))
            ->setObject(new Park());

        $this->setAttribute('method', 'post');


        $this->add(array(
            'type' => 'checkbox',
            'name' => 'account_type',
            'attributes' => array(
                'switcher' => true,
                'id' => 'accountType',
                'data-on-label' => '&nbsp;Parc-Auto&nbsp;',
                'data-off-label' => '&nbsp;Particular&nbsp;',
                'class' => 'switch-large',
                'selected' => 'true',
            ),
            'options' => array(
                'label' => 'Tip Cont',
            ),
        ));

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
            'name' => 'name2',
            'options' => array(
                'label' => 'Nume',
            ),
            'attributes' => array(
//                'group' => array('size' => 'col-sm-4', 'type' => 'start'),
                'type' => 'text',
                'id' => 'name2',
//                'extraInfo' => 'obligatoriu: va aparea in detaliile anuntului'
                //'required' => true
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
                'extraInfo' => 'obligatoriu: va aparea in detaliile anuntului'
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
                'extraInfo' => 'optional'
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
                'extraInfo' => 'optional'
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
                'class' => 'x',
                'extraInfo' => 'optional: imagine png/jpg'
            ),
        ));

        $this->add(array(
            'name' => 'tel1',
            'options' => array(
                'label' => 'Telefon',
            ),
            'attributes' => array(
                'group' => array('sizeLabel' => 'col-sm-2', 'size' => 'col-sm-4', 'type' => 'start'),
                'type' => 'text',
                'extraInfo' => 'obligatoriu'
            ),
        ));
        $this->add(array(
            'name' => 'tel2',
            'options' => array(
                'label' => 'Telefon 2',
            ),
            'attributes' => array(
                'group' => array('sizeLabel' => 'col-sm-1', 'size' => 'col-sm-3', 'type' => ''),
                'type' => 'text',
                'noLabel' => true,
                'extraInfo' => 'optional'
            ),
        ));
        $this->add(array(
            'name' => 'tel3',
            'options' => array(
                'label' => 'Telefon 3',
            ),
            'attributes' => array(
                'group' => array('sizeLabel' => 'col-sm-1', 'size' => 'col-sm-3', 'type' => 'end'),
                'type' => 'text',
                'noLabel' => true,
                'extraInfo' => 'optional'
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
                'extraInfo' => 'optional'
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
                'extraInfo' => 'obligatoriu'
            ),
        ));
        $this->add(array(
            'type' => 'select',
            'name' => 'state',
            'options' => array(
                'label' => 'Stare',
                'options' => $states,
            ),
            'attributes' => array(
//                'id' => 'year_end',
                'group' => array('size' => 'col-sm-2', 'type' => 'end'),
                'required' => true,
                'noLabel' => true,
                'extraInfo' => 'obligaroriu'
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