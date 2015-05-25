<?php

namespace Application\Forms;

use Application\libs\General;
use Application\Models\Ads\Ad;
use Zend\Stdlib\Hydrator\ClassMethods as ClassMethodsHydrator;

class OffersForm extends AbstractForm
{
    public function create($categories)
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
                'required' => true,
                'group' => array('size' => 'col-lg-3 col-md-3 col-sm-3 col-xs-12', 'type' => 'start'),
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
                'required' => true,
                'group' => array('size' => 'col-lg-3 col-md-3 col-sm-3 col-xs-12', 'type' => ''),
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
                'group' => array('size' => 'col-lg-3 col-md-3 col-sm-3 col-xs-12', 'type' => ''),
            ),
        ));
        $this->add(array(
            'type' => 'select',
            'name' => 'state',
            'options' => array(
                'label' => 'Judet',
                'options' => General::getFromSession('states')
            ),
            'attributes' => array(
//                'id' => 'year_end',
                'group' => array('size' => 'col-lg-3 col-md-3 col-sm-3 col-xs-12', 'type' => 'end'),
                'required' => true,
//                'noLabel' => true,
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
            'type' => 'select',
            'name' => 'car_category',
            'options' => array(
                'label' => 'Piesa Pentru',
                'options' => ['0' => 'Alege Categoria'] + $categories,
            ),
            'attributes' => array(
                'id' => 'select2CarMake',
//                'group' => array('size' => 'col-sm-8 col-md-4', 'sizeLabel' => 'col-sm-4 col-md-2', 'type' => 'start'),
                'group' => array('size' => 'col-lg-4 col-md-4 col-sm-4 col-xs-12', 'type' => 'start'),
                'required' => true,
//                'extraInfo' => 'Sau selecteaza din cei adaugati deja in sistem'

            ),
        ));
        $this->add(array(
            'type' => 'select',
            'name' => 'car_make',
            'options' => array(
                'label' => 'Marca',
                'options' => ['' => 'Marca'],
                //'empty_option' => '--- Selecteaza Parinte ---',
                'disable_inarray_validator' => true
            ),
            'attributes' => array(
//                'noLabel' => true,
//                'disabled' => 'disabled',
                'id' => 'select2CarModels',
                'group' => array('size' => 'col-lg-4 col-md-4 col-sm-4 col-xs-12', 'type' => ''),
                'required' => true,
//                'extraInfo' => 'Sau selecteaza din cei adaugati deja in sistem'
            ),
        ));
        $this->add(array(
            'type' => 'text',
            'name' => 'car_model',
            'options' => array(
                'label' => 'Model',
//                'disable_inarray_validator' => true
            ),
            'attributes' => array(
                'placeholder' => 'Model',
//                'disabled' => 'disabled',
//                'noLabel' => true,
                'id' => 'select2CarModels2',
                'group' => array('size' => 'col-lg-4 col-md-4 col-sm-4 col-xs-12', 'type' => 'end'),
                'required' => true,
//                'extraInfo' => 'Sau selecteaza din cei adaugati deja in sistem'

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
                'group' => array('size' => 'col-lg-4 col-md-4 col-sm-4 col-xs-12', 'type' => 'start'),
//                'extraInfo' => 'ex: 2012-2015'
            ),
        ));
        $this->add(array(
            'type' => 'text',
            'name' => 'sasiu',
            'options' => array(
                'label' => 'Serie Sasiu',
            ),
            'attributes' => array(
                'placeholder' => 'Serie Sasiu',
                'id' => 'select2CarModels2',
                'group' => array('size' => 'col-lg-8 col-md-8 col-sm-8 col-xs-12', 'type' => 'end'),
//                'extraInfo' => 'optional'

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
                'containerClass' => 'piesa'
            )
        ));

        $this->add(array(
            'name' => 'part_name[]',
            'type' => 'text',
            'options' => array(
                'label' => 'Nume piesa',
            ),
            'attributes' => array(
                'group' => array('size' => 'col-lg-6 col-md-6 col-sm-6 col-xs-12', 'type' => 'start'),
                'containerClass' => 'piesa'
            ),
        ));
        $this->add(array(
            'name' => 'part_code[]',
            'type' => 'text',
            'options' => array(
                'label' => 'Cod piesa',
            ),
            'attributes' => array(
                'group' => array('size' => 'col-lg-6 col-md-6 col-sm-6 col-xs-12', 'type' => 'end'),
            ),
        ));

        $this->add(array(
            'name' => 'part_descr[]',
            'type' => 'textarea',
            'options' => array(
                'label' => 'Descriere',
            ),
            'attributes' => array(
                'rows' => 2,
                "containerClass" => 'piesa'
            ),
        ));
        $this->add(array(
            'name' => 'x6',
            'type' => 'hidden',
            'attributes' => array(
                'pureHtml' => '<a href="javascript:;" data-clone="piesa" class="btn btn-success pull-right">adauga piesa noua</a>',
                'custom_form_spacer' => true,
            )
        ));
        ////

        // --- FOTOGRAFII ---
        $this->add(array(
            'name' => 'x5',
            'type' => 'hidden',
            'attributes' => array(
                'textAbove' => 'Poze',
                'custom_form_spacer' => true,
            )
        ));
        $this->add(array(
            'name' => 'photosMultiUpload',
            'type' => 'text',
            'options' => [
                'label' => '',
            ],
            'attributes' => array(
                'maxFileSize' => '2MB',
                'maxNumberOfFiles' => 10,
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
                'value' => 'Trimite Cererea',
                'id' => 'submitbutton',
                'cancelLink' => $this->_cancel_route
            ),
        ));
    }
}
