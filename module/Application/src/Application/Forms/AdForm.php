<?php

namespace Application\Forms;

use Application\Models\Ads\Ad;
use Zend\Stdlib\Hydrator\ClassMethods as ClassMethodsHydrator;

class AdForm extends AbstractForm
{
    public function create($resourceObj, $carMake, $carburant, $cilindree, $partsMain)
    {
        $this->setHydrator(new ClassMethodsHydrator(true))
            ->setObject($resourceObj);

        $this->setAttribute('method', 'post');
        $this->setName('fileupload');
        $this->setAttribute('id', 'fileupload');


        //--------------------------------------------------------- DETALII PIESA--------------

        $this->add(array(
            'name' => 'custom_form_spacer',
            'type' => 'hidden',
            'attributes' => array(
                'textAbove' => 'Detalii Piesa',
            )
        ));

        $this->add(array(
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
                'group' => array('size' => 'col-sm-8 col-md-4', 'sizeLabel' => 'col-sm-4 col-md-2', 'type' => 'end'),
//                'extraInfo' => 'Sau selecteaza din cei adaugati deja in sistem'

            ),
        ));

//-------------------------- DETALII MASINA--------------------
        $this->add(array(
            'name' => 'custom_form_spacer',
            'type' => 'hidden',
            'attributes' => array(
                'textAbove' => 'Detalii masina',
            )
        ));

        $this->add(array(
            'type' => 'select',
            'name' => 'categories',
            'options' => array(
                'label' => 'Marca Masina',
                'options' => ['' => ''] + $carMake,
            ),
            'attributes' => array(
                'id' => 'select2CarMake',
                'group' => array('size' => 'col-sm-8 col-md-4', 'sizeLabel' => 'col-sm-4 col-md-2', 'type' => 'start'),
                'required' => true,
//                'extraInfo' => 'Sau selecteaza din cei adaugati deja in sistem'

            ),
        ));

        $this->add(array(
            'type' => 'select',
            'name' => 'car_class',
            'options' => array(
                'label' => 'Model Masina',
                'options' => ['' => 'Clasa'],
                //'empty_option' => '--- Selecteaza Parinte ---',
                'disable_inarray_validator' => true
            ),
            'attributes' => array(
                'noLabel' => true,
                'disabled' => 'disabled',
                'id' => 'select2CarModels',
                'group' => array('size' => 'col-sm-6 col-md-3', 'type' => ''),
                'required' => true,
//                'extraInfo' => 'Sau selecteaza din cei adaugati deja in sistem'

            ),
        ));
        $this->add(array(
            'type' => 'select',
            'name' => 'car_model',
            'options' => array(
                'options' => ['' => 'Model'],
                'disable_inarray_validator' => true
            ),
            'attributes' => array(
                'disabled' => 'disabled',
                'noLabel' => true,
                'id' => 'select2CarModels2',
                'group' => array('size' => 'col-sm-6 col-md-3', 'type' => 'end'),
                'required' => true,
//                'extraInfo' => 'Sau selecteaza din cei adaugati deja in sistem'

            ),
        ));


        $this->add(array(
            'type' => 'select',
            'name' => 'car_carburant',
            'options' => array(
                'label' => 'Motorizare',
                'options' => ['' => 'Oricare'] + $carburant,
            ),
            'attributes' => array(
                'group' => array('size' => 'col-sm-4 col-md-2', 'sizeLabel' => 'col-sm-4 col-md-2', 'type' => 'start'),
            ),
        ));


        $this->add(array(
            'type' => 'select',
            'name' => 'car_cilindree',
            'options' => array(
                'placeholder' => 'Cilindree',
                'options' => ['' => 'Oricare'] + $cilindree,
            ),
            'attributes' => array(
                'noLabel' => true,
                'group' => array('size' => 'col-sm-4 col-md-2', 'sizeLabel' => 'col-sm-4 col-md-2', 'type' => 'end'),
            ),
        ));
//--------------------------------------------------------- POZE ANUNT--------------

        $this->add(array(
            'name' => 'custom_form_spacer',
            'type' => 'hidden',
            'attributes' => array(
                'textAbove' => 'Poze',
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

//--------------------------------------------------------- DETALII ANUNT--------------


        $this->add(array(
            'name' => 'custom_form_spacer',
            'type' => 'hidden',
            'attributes' => array(
                'textAbove' => 'Detalii Anunt',
            )
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
                'group' => array('size' => 'col-sm-6 col-md-2', 'sizeLabel' => 'col-sm-4 col-md-2', 'type' => 'start'),
                'type' => 'text',
            ),
        ));
        $this->add(array(
            'name' => 'phone',
            'options' => array(
                'label' => 'RON',
            ),
            'attributes' => array(
                'justText' => true,
                'noLabel' => true,
                'group' => array('size' => 'col-sm-2 col-md-1', 'type' => 'end'),
                'type' => 'text',
            ),
        ));

        $this->add(array(
            'name' => 'custom_form_spacer',
            'type' => 'hidden',
            'attributes' => array(// 'textAbove' => 'Detalii Anunt',
            )
        ));


        $this->add(array(
            'name' => 'submit',
            'attributes' => array(
                'type' => 'submit',
                'value' => 'Adauga Anunt',
                'id' => 'submitbutton',
                'cancelLink' => $this->_cancel_route
            ),
        ));
    }

}