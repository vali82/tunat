<?php

namespace Application\Forms;

use Application\Models\Ads\Ad;
use Zend\Stdlib\Hydrator\ClassMethods as ClassMethodsHydrator;

class OffersForm extends AbstractForm
{
    public function create()
    {
        $this->setAttribute('method', 'post');
        $this->setName('fileupload');
        $this->setAttribute('id', 'fileupload');


        // --- DETALII DE CONTACT ---
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
        ////


        // --- DATE MASINA ---
        $this->add(array(
            'name' => 'x3',
            'type' => 'hidden',
            'attributes' => array(
                'textAbove' => 'Detalii Masina',
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
        $years = [];
        for ($i=date('Y'); $i>1960; $i--) {
            $years[$i] = $i;
        }
        $this->add(array(
            'type' => 'select',
            'name' => 'year_start',
            'options' => array(
                'label' => 'An Fabricatie',
                'options' => $years,
                //'empty_option' => '--- Selecteaza Parinte ---',
//                'disable_inarray_validator' => true
            ),
            'attributes' => array(
                'required' => true,
                'group' => array('size' => 'col-lg-4 col-md-4 col-sm-4 col-xs-12', 'type' => 'end'),
//                'extraInfo' => 'ex: 2012-2015'
            ),
        ));
        ////

        // --- DATE PIESA ---
        $this->add(array(
            'name' => 'x4',
            'type' => 'hidden',
            'attributes' => array(
                'textAbove' => 'Detalii Piesa',
                'custom_form_spacer' => true,
            )
        ));

        $this->add(array(
            'name' => 'part_name',
            'type' => 'text',
            'options' => array(
                'label' => 'Nume piesa',
            ),
            'attributes' => array(
                'group' => array('size' => 'col-lg-6 col-md-6 col-sm-6 col-xs-12', 'type' => 'start'),
            ),
        ));
        $this->add(array(
            'name' => 'part_code',
            'type' => 'text',
            'options' => array(
                'label' => 'Cod piesa',
            ),
            'attributes' => array(
                'group' => array('size' => 'col-lg-6 col-md-6 col-sm-6 col-xs-12', 'type' => 'end'),
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
            'name' => 'photosMultiUpload',
            'type' => 'text',
            'options' => [
                'label' => '',
            ],
            'attributes' => array(
                'maxFileSize' => '2MB',
                'maxNumberOfFiles' => 2,
                'acceptedFileType' => 'jpeg, png, gif'
//                'noLabel' => true,
                //'textAbove' => 'Detalii Anunt',
            )
        ));
        ////


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
