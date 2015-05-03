<?php

namespace Application\Forms;

use Application\Models\Ads\Ad;
use Zend\Stdlib\Hydrator\ClassMethods as ClassMethodsHydrator;

class AdForm extends AbstractForm
{
    public function create($resourceObj, $carMake, $years)
    {
        $this->setHydrator(new ClassMethodsHydrator(true))
            ->setObject($resourceObj);

        $this->setAttribute('method', 'post');
        $this->setName('fileupload');
        $this->setAttribute('id', 'fileupload');


        //--------------------------------------------------------- DETALII PIESA--------------

        /*$this->add(array(
            'type' => 'select',
            'name' => 'part_categ',
            'options' => array(
                'label' => 'Categorie',
                'options' => ['' => ''] + $partsMain,
            ),
            'attributes' => array(
                'id' => 'select2CarPartsMain',
                    'group' => array('size' => 'col-sm-8 col-md-4', 'sizeLabel' => 'col-sm-4 col-md-2', 'type' => 'start'),
//                    'extraInfo' => 'Sau selecteaza din cei adaugati deja in sistem'

            ),
        ));*/




//-------------------------- DETALII MASINA--------------------
        $this->add(array(
            'type' => 'hidden',
            'name' => 'x1',
            'attributes' => array(
                'textAbove' => 'Detalii masina',
                'custom_form_spacer' => true,
            )
        ));

        $this->add(array(
            'type' => 'select',
            'name' => 'car_category',
            'options' => array(
                'label' => 'Piesa Pentru',
                'options' => ['0' => 'Alege Categoria'] + $carMake,
            ),
            'attributes' => array(
                'id' => 'select2CarMake',
//                'group' => array('size' => 'col-sm-8 col-md-4', 'sizeLabel' => 'col-sm-4 col-md-2', 'type' => 'start'),
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
                'group' => array('size' => 'col-lg-4 col-md-4 col-sm-4 col-xs-12', 'type' => 'start'),
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

        for ($i=date('Y'); $i>1960; $i--) {
            $years[$i] = $i;
        }

        $this->add(array(
            'type' => 'select',
            'name' => 'year_start',
            'options' => array(
                'label' => 'An Fabricatie',
                'options' => ['' => 'de la '] + $years,
                //'empty_option' => '--- Selecteaza Parinte ---',
//                'disable_inarray_validator' => true
            ),
            'attributes' => array(
//                'noLabel' => true,
//                'disabled' => 'disabled',
                'id' => 'year_start',
                'group' => array('size' => 'col-lg-2 col-md-2 col-sm-2 col-xs-12', 'type' => 'start'),
                'required' => false,
//                'extraInfo' => 'Sau selecteaza din cei adaugati deja in sistem'
            ),
        ));
        $this->add(array(
            'type' => 'select',
            'name' => 'year_end',
            'options' => array(
                'label' => '-',
                'options' => ['' => 'pana la '] + $years,
                //'empty_option' => '--- Selecteaza Parinte ---',
//                'disable_inarray_validator' => true
            ),
            'attributes' => array(
                'noLabel' => true,
//                'disabled' => 'disabled',
                'id' => 'year_end',
                'group' => array('size' => 'col-lg-2 col-md-2 col-sm-2 col-xs-12', 'type' => ''),
                'required' => false,
//                'extraInfo' => 'Sau selecteaza din cei adaugati deja in sistem'
            ),
        ));

        $this->add(array(
            'type' => 'select',
            'name' => 'stare',
            'options' => array(
                'label' => 'Stare',
                'options' => ['nou' => 'Nou', 'second' => 'Second'],
            ),
            'attributes' => array(
//                'id' => 'year_end',
                'group' => array('size' => 'col-lg-4 col-md-4 col-sm-4 col-xs-12', 'type' => 'end'),
                'required' => false,
            ),
        ));

//--------------------------------------------------------- DETALII ANUNT--------------


        $this->add(array(
            'name' => 'x2',
            'type' => 'hidden',
            'attributes' => array(
                'textAbove' => 'Detalii Anunt',
                'custom_form_spacer' => true,
            )
        ));

        $this->add(array(
            'type' => 'text',
            'name' => 'part_name',
            'options' => array(
                'label' => 'Piesa',
//                'options' => [''=>''],
            ),
            'attributes' => array(
                'id' => 'select2CarPartsSub',
//                'group' => array('size' => 'col-sm-8 col-md-4', 'sizeLabel' => 'col-sm-4 col-md-2', 'type' => 'end'),
//                'extraInfo' => 'Sau selecteaza din cei adaugati deja in sistem'

            ),
        ));


        $this->add(array(
            'name' => 'description',
            'type' => 'textarea',
            'options' => array(
                'label' => 'Descriere',
            ),
            'attributes' => array(
                'extraInfo' => 'Detalii masina daca este cazul. Ex: Audi A4 sedan 1.9TDI, sau: piesa e compatibila cu
                toate modele Audi A4 2001-2005'
                //'group' => array('size'=> 'col-sm-4','type'=>'end'),
//				'id' => 'last_name',
            ),
        ));

        /*$this->add(array(
            'name' => 'phone',
            'options' => array(
                'label' => 'Stare produs',
            ),
            'attributes' => array(
                'group' => array('size'=> 'col-sm-8 col-md-2', 'sizeLabel'=>'col-sm-4 col-md-2', 'type'=>'start'),
                'type' => 'text',
            ),
        ));*/
        $this->add(array(
            'name' => 'price',
            'options' => array(
                'label' => 'Pret',
            ),
            'attributes' => array(
                'group' => array(
                    'size' => 'col-lg-2 col-md-3 col-sm-4 col-xs-8',
                    'sizeLabel' => 'col-lg-2 col-md-2 col-sm-2 col-xs-12',
                    'type' => 'start'
                ),
                'type' => 'text',
            ),
        ));
        $this->add(array(
            'type' => 'select',
            'name' => 'currency',
            'options' => array(
//                'label' => '-',
                'options' => ['RON' => 'RON', 'EUR' => 'EUR', 'USD' => 'USD'],
                //'empty_option' => '--- Selecteaza Parinte ---',
//                'disable_inarray_validator' => true
            ),
            'attributes' => array(
                'noLabel' => true,
//                'disabled' => 'disabled',
//                'id' => 'year_end',
                'group' => array(
                    'size' => 'col-lg-2 col-md-2 col-sm-2 col-xs-4',
                    'sizeLabel' => 'col-lg-2 col-md-2 col-sm-2 col-xs-12',
                    'type' => 'end'
                ),
                'required' => true,
//                'extraInfo' => 'Sau selecteaza din cei adaugati deja in sistem'
            ),
        ));

//--------------------------------------------------------- POZE ANUNT--------------

        $this->add(array(
            'type' => 'hidden',
            'name' => 'x4',
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
                'acceptedFileType' => 'jpeg, png, gif'
//                'noLabel' => true,
                //'textAbove' => 'Detalii Anunt',
            )
        ));

        /*$this->add(array(
            'name' => 'custom_form_spacer',
            'type' => 'hidden',
            'attributes' => array(
                'name' => 'sdad',
                'id' => 'x2',
                'textAbove' => 'zzz',
            )
        ));*/

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